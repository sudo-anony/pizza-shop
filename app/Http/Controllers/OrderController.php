<?php

namespace App\Http\Controllers;

use Akaunting\Module\Facade as Module;
use App\Coupons;
use App\Events\OrderAcceptedByAdmin;
use App\Events\OrderAcceptedByVendor;
use App\Events\UpdateOrder;
use App\Exports\OrdersExport;
use App\Models\Orderitems;
use App\Items;
use App\Models\SimpleDelivery;
use App\Notifications\OrderNotification;
use App\Order;
use App\Address;
use App\Repositories\Orders\OrderRepoGenerator;
use App\Restorant;
use App\Settings;
use App\Services\ConfChanger;
use App\Status;
use App\User;
use Carbon\Carbon;
use Cart;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use willvincent\Rateable\Rating;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Stripe\Coupon;
use App\Models\ApiLog;

use App\Mail\OrderPaymentConfirmation;

class OrderController extends Controller
{

    private $api_url = "https://osp.expertorder.de/testPush";
    private $api_key = "9615d48a-cc88-4c3e-8e43-102047366a71";


    public function orderSuccess(Request $request){
        $sessionId = $request->query('session_id');
        $random = $request->query('id');
        if (!$sessionId) {
            return redirect('/')->with('error', 'Session ID missing.');
        }
        Stripe::setApiKey(env('STRIPE_SECRET'));
    
        try {
            // Retrieve the checkout session
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            
            // Fetch the PaymentIntent to get the PaymentMethod ID
            if (!empty($session->payment_intent)) {
                $paymentIntent = \Stripe\PaymentIntent::retrieve($session->payment_intent);
                $paymentIntentId = $paymentIntent->id ?? null;
                $paymentMethodId = $paymentIntent->payment_method ?? null;
                $chargeId = $paymentIntent->latest_charge ?? null;
                // dd($paymentIntentId, $paymentMethodId, $chargeId);

            } else {
                return redirect('/')->with('error', 'No Payment Intent found.');
            }
    
            return view('cart.success', [
                'session' => $session,
                'paymentMethodId' => $paymentMethodId,
                'paymentIntent' => $paymentIntentId,
                'chargeId' => $chargeId,
		'id'=> $random
            ]);
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Failed to retrieve payment details.');
        }
    }


       public function checkout(Request $request){
        if($request->deliveryMethod !== 'pickup'){
            if(array_key_exists('addressID', $request->formDetails)){
                $address = Address::findOrFail($request->formDetails['addressID']);
            }else{
                $address = null;
            }
        }else{
            $address = null;
        }
    
        $restaurant_id = $request->restaurant_id;
        $totalOrders = Order::count() + 1;
        $randomID = str_pad($restaurant_id, 3, '0', STR_PAD_LEFT) . '000' . $totalOrders; 
        
        $email = $address && $address->email ? $address->email : auth()->user()->email;
    
        $items = [];
    
        foreach($request->cartItems as $item){
            $items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => $item['price'] * 100,
                ],
                'quantity' => $item['quantity'],
            ];
        }
    
        if($request->tip){
            $tip = $this->moneyToFloat($request->tip) * 100;
            $items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Tip',
                        'description' => 'Tip',
                    ],
                    'unit_amount' => $tip,
                ],
                'quantity' => 1,
            ];
        }

        
    
        Stripe::setApiKey(env('STRIPE_SECRET')); 
    
        $couponId = null;
        $totalAmount = 0;

        if (!empty($request->pickup_dicsount)) {
            $totalAmount += (float) $request->pickup_dicsount;
            $couponName = 'Pickup Discount';
        }
        
        if (!empty($request->discount)) {
            $totalAmount += (float) $request->discount;
            $couponName = empty($couponName) ? 'Coupon Discount' : 'Pickup + Coupon Discount';
        }
        
        $totalAmount = (int) round($totalAmount * 100);
        
        if ($totalAmount > 0) {
            try {
                $coupon = Coupon::create([
                    'amount_off' => $totalAmount,
                    'currency' => 'eur',
                    'duration' => 'once',
                    'name' => $couponName
                ]);
                $couponId = $coupon->id;
            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'code' => $e->getCode()
                ], 500);
            }
        }
    
        try {
            $checkoutSessionData = [
                'payment_method_types' => ['card', 'klarna', 'sepa_debit', 'paypal', 'revolut_pay'],
                'mode' => 'payment',
                'line_items' => $items,
                'customer_email' => $email,
                'success_url' => url('/completeOrder') . '?session_id={CHECKOUT_SESSION_ID}&id=' . $randomID,
                'cancel_url' => route('cart.checkout'),
                'metadata' => [
                    'order_id' => $randomID,
                    'customer_email' => $email,
                ],
            ];
    
            if ($couponId) {
                $checkoutSessionData['discounts'] = [
                    ['coupon' => $couponId]
                ];
            }
    
            $checkoutSession = Session::create($checkoutSessionData);
    
            return response()->json(['url' => $checkoutSession->url]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    
    

    private function format_price($value) {
        return floatval(number_format($value, 2, '.', '')); // Exactly 2 decimal places
    }

    public function migrateStatuses()
    {
        if (Status::count() < 13) {
            $statuses = ['Just created', 'Accepted by admin', 'Accepted by restaurant', 'Assigned to driver', 'Prepared', 'Picked up', 'Delivered', 'Rejected by admin', 'Rejected by restaurant', 'Updated', 'Closed', 'Rejected by driver', 'Accepted by driver'];
            foreach ($statuses as $key => $status) {
                Status::updateOrCreate(['name' => $status], ['alias' => str_replace(' ', '_', strtolower($status))]);
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->migrateStatuses();

        $restorants = Restorant::where(['active' => 1])->get();
        $drivers = auth()->user()->myDrivers();
        $clients = User::role('client')->where(['active' => 1])->get();

        $driversData = [];
        foreach ($drivers as $key => $driver) {
            $driversData[$driver->id] = $driver->name;
        }

        $orders = Order::orderBy('created_at', 'desc')->whereNotNull('restorant_id');

        //Get client's orders
        if (auth()->user()->hasRole('client')) {
            $orders = $orders->where(['client_id' => auth()->user()->id]);
        } elseif (auth()->user()->hasRole('driver')) {
            $orders = $orders->where(['driver_id' => auth()->user()->id]);
        } elseif (auth()->user()->hasRole('owner')) {

            //Change currency
            ConfChanger::switchCurrency(auth()->user()->restorant);

            $orders = $orders->where(['restorant_id' => auth()->user()->restorant->id]);
        } elseif (auth()->user()->hasRole('staff')) {

            //Change currency
            ConfChanger::switchCurrency(auth()->user()->restaurant);

            $orders = $orders->where(['restorant_id' => auth()->user()->restaurant_id]);
        } elseif (auth()->user()->hasRole('manager')) {
            $orders = $orders->whereIn(['restorant_id' => auth()->user()->getManagerVendors()]);
        }

        //FILTER BT RESTORANT
        if (isset($_GET['restorant_id'])) {
            $orders = $orders->where(['restorant_id' => $_GET['restorant_id']]);
        }
        //If restorant owner, get his restorant orders only
        if (auth()->user()->hasRole('owner')) {
            //Current restorant id
            $restorant_id = auth()->user()->restorant->id;
            $orders = $orders->where(['restorant_id' => $restorant_id]);
        }

        //BY CLIENT
        if (isset($_GET['client_id'])) {
            $orders = $orders->where(['client_id' => $_GET['client_id']]);
        }

        //BY DRIVER
        if (isset($_GET['driver_id'])) {
            $orders = $orders->where(['driver_id' => $_GET['driver_id']]);
        }

        //BY DATE FROM
        if (isset($_GET['fromDate']) && strlen($_GET['fromDate']) > 3) {
            $orders = $orders->whereDate('created_at', '>=', $_GET['fromDate']);
        }

        //BY DATE TO
        if (isset($_GET['toDate']) && strlen($_GET['toDate']) > 3) {
            $orders = $orders->whereDate('created_at', '<=', $_GET['toDate']);
        }

        //FILTER BT status
        if (isset($_GET['status_id'])) {
            $orders = $orders->whereHas('laststatus', function ($q) {
                $q->where('status_id', $_GET['status_id']);
            });
        } else {
            $orders = $orders->whereHas('laststatus', function ($q) {
                $q->whereNotIn('status_id', [8, 9]);
            });
        }

        //FILTER BT payment status
        if (isset($_GET['payment_status']) && strlen($_GET['payment_status']) > 2) {
            $orders = $orders->where('payment_status', $_GET['payment_status']);
        }

        //With downloaod
        if (isset($_GET['report'])) {
            $items = [];
            foreach ($orders->get() as $key => $order) {
                $item = [
                    'order_id' => $order->id,
                    'restaurant_name' => $order->restorant->name,
                    'restaurant_id' => $order->restorant_id,
                    'created' => $order->created_at,
                    'last_status' => $order->status->pluck('alias')->last(),
                    'client_name' => $order->client ? $order->client->name : '',
                    'client_id' => $order->client ? $order->client_id : null,
                    'table_name' => $order->table ? $order->table->name : '',
                    'table_id' => $order->table ? $order->table_id : null,
                    'area_name' => $order->table && $order->table->restoarea ? $order->table->restoarea->name : '',
                    'area_id' => $order->table && $order->table->restoarea ? $order->table->restoarea->id : null,
                    'address' => $order->address ? $order->address->address : '',
                    'address_id' => $order->address_id,
                    'driver_name' => $order->driver ? $order->driver->name : '',
                    'driver_id' => $order->driver_id,
                    'order_value' => $order->order_price_with_discount,
                    'order_delivery' => $order->delivery_price,
                    'order_total' => $order->delivery_price + $order->order_price_with_discount,
                    'payment_method' => $order->payment_method,
                    'srtipe_payment_id' => $order->srtipe_payment_id,
                    'order_fee' => $order->fee_value,
                    'restaurant_fee' => $order->fee,
                    'restaurant_static_fee' => $order->static_fee,
                    'vat' => $order->vatvalue,
                ];
                array_push($items, $item);
            }

            return Excel::download(new OrdersExport($items), 'orders_'.time().'.xlsx');
        }

        $orders = $orders->paginate(10);

        return view('orders.index', [
            'statuses' => Status::pluck('name', 'id')->toArray(),
            'orders' => $orders,
            'restorants' => $restorants,
            'drivers' => $drivers,
            'fields' => [['class' => 'col-12', 'classselect' => 'noselecttwo', 'ftype' => 'select', 'name' => 'Driver', 'id' => 'driver', 'placeholder' => 'Assign Driver', 'data' => $driversData, 'required' => true]],
            'clients' => $clients,
            'parameters' => count($_GET) != 0,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    private function toiDriveMobileLike(Request $request)
    {
        //Data
        $driver = User::findOrFail($request->driver_id);
        $vendor_id = null;

        $requestData = [
            'issd' => true,
            'vendor_id' => $vendor_id,
            'driver_id' => $request->driver_id,
            'delivery_method' => 'delivery',
            'payment_method' => 'cod',
            'address_id' => null,
            'pickup_address' => $request->pickup_address,
            'pickup_lat' => $request->pickup_lat,
            'pickup_lng' => $request->pickup_lng,
            'delivery_address' => $request->delivery_address,
            'delivery_lat' => $request->delivery_lat,
            'delivery_lng' => $request->delivery_lng,
            'timeslot' => '',
            'items' => [],
            'comment' => $request->comment,
            'phone' => $request->phoneclient,
        ];

        return new Request($requestData);

    }

    private function toRideMobileLike(Request $request)
    {
        //Data
        $driver = User::findOrFail($request->driver_id);
        $vendor_id = $driver->restaurant_id;

        $requestData = [
            'issd' => true,
            'vendor_id' => $vendor_id,
            'driver_id' => $request->driver_id,
            'delivery_method' => 'delivery',
            'payment_method' => 'cod',
            'address_id' => null,
            'pickup_address' => $request->pickup_address,
            'pickup_lat' => $request->pickup_lat,
            'pickup_lng' => $request->pickup_lng,
            'delivery_address' => $request->delivery_address,
            'delivery_lat' => $request->delivery_lat,
            'delivery_lng' => $request->delivery_lng,
            'timeslot' => '',
            'items' => [],
            'comment' => $request->comment,
            'phone' => $request->phoneclient,
        ];

        return new Request($requestData);

    }

    private function toMobileLike(Request $request)
    {
        //Find vendor id
        $vendor_id = null;
        foreach (Cart::getContent() as $key => $item) {
            $vendor_id = $item->attributes->restorant_id;
        }
        $restorant = Restorant::findOrFail($vendor_id);

        //Organize the item
        $items = [];
        foreach (Cart::getContent() as $key => $item) {
            $extras = [];
            foreach ($item->attributes->extras as $keyExtra => $extra_id) {
                array_push($extras, ['id' => $extra_id]);
            }
            array_push($items, [
                'id' => $item->attributes->id,
                'qty' => $item->quantity,
                'variant' => $item->attributes->variant,
                'extrasSelected' => $extras,
            ]);
        }

        //stripe token
        // TODO: Need to save charge id in order

        $stripe_token = null;
        $stripe_payment_intent = null;
        $stripe_charge_id = null;
        if ($request->has('stripePaymentId')) {
            $stripe_token = $request->stripePaymentId;
            $stripe_payment_intent = $request->stripePaymentIntent;
            $stripe_charge_id = $request->stripeChargeId;
        }

        //Custom fields
        $customFields = [];
        if ($request->has('custom')) {
            $customFields = $request->custom;
        }

        //DELIVERY METHOD
        //Default - pickup - since available everywhere
        $delivery_method = 'pickup';

        //Delivery method - deliveryType - ft
        if ($request->has('deliveryType')) {
            $delivery_method = $request->deliveryType;
        } elseif ($restorant->can_pickup == 0 && $restorant->can_deliver == 1) {
            $delivery_method = 'delivery';
        }

        //Delivery method  - dineType - qr
        if ($request->has('dineType')) {
            $delivery_method = $request->dineType;
        }

        //In case it is QR, and there is no dineInType, and pickup is diabled, it is dine in
        if (config('app.isqrsaas') && ! $request->has('dineType') && ! (config('settings.is_whatsapp_ordering_mode') || config('settings.is_agris_mode'))) {
            $delivery_method = 'dinein';
        }
        //takeaway is pickup
        if ($delivery_method == 'takeaway') {
            $delivery_method = 'pickup';
        }

        //Table id
        $table_id = null;
        if ($request->has('table_id')) {
            $table_id = $request->table_id;
        }

        //Phone
        $phone = null;
        if ($request->has('phone')) {
            $phone = $request->phone;
        }

        //Delivery area
        $deliveryAreaId = $request->has('delivery_area') ? $request->delivery_area : null;
        if ($deliveryAreaId) {
            //Set this in custom field
            $deliveryAreaName = '';
            $deliveryAreaElement = SimpleDelivery::find($request->delivery_area);
            if ($deliveryAreaElement) {
                $deliveryAreaName = $deliveryAreaElement->name;
            }
            $customFields['delivery_area_name'] = $deliveryAreaName;
        }

        Log::info('Tip: ======================= ToMobileLink ' . $request->tip);

        $requestData = [
            'vendor_id' => $vendor_id,
            'delivery_method' => $delivery_method,
            'payment_method' => $request->paymentType ? $request->paymentType : 'cod',
            'address_id' => $request->addressID,
            'timeslot' => $request->timeslot,
            'items' => $items,
            'comment' => $request->comment,
            'stripe_token' => $stripe_token,
            'stripe_payment_intent' => $stripe_payment_intent,
            'stripe_charge_id' => $stripe_charge_id,
            'dinein_table_id' => $table_id,
            'phone' => $phone,
            'customFields' => $customFields,
            'deliveryAreaId' => $deliveryAreaId,
            'tip' => $request->has('tipapplied') && $request->tipapplied.'' == '1' ? $request->tip : 0,
            'coupon_code' => $request->has('coupon_code') && strlen($request->coupon_code) > 0 ? $request->coupon_code : null,
        ];

        return new Request($requestData);
    }

    protected function moneyToFloat($money)
    {
        $userLang = Cookie::get('lang') ? Cookie::get('lang') : config('app.locale');
        if($userLang == 'en'){
            $value = str_replace(['€', ' '], '', $money); // Corrected $request->tip to $ammountString
            $value = str_replace(',', '', $value);
            $value = floatval($value);
        }else{
            $value = str_replace(['€', ' '], '', $money);
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
            $value = floatval($value);
        }
        
        return $value;
    }

    public function store(Request $request)
    {       
        if ($request->has('tip')) {
            $tip = $request->input('tip');  
            $numericTip = $this->moneyToFloat($tip); 

            Log::info('Tip: ============= in Store method ' . $numericTip);
            Log::info('Tip: ============= in Store method ' . $tip);

            $request->merge(['tip' => $numericTip]); 
        }
        //Convert web request to mobile like request
        if (config('app.isdrive', false)) {
            //iDrive ride
            $mobileLikeRequest = $this->toiDriveMobileLike($request);
        } elseif (config('app.issd', false) || $request->has('issd')) {
            //Web ride
            $mobileLikeRequest = $this->toRideMobileLike($request);
        } else {
            //Web order
            $mobileLikeRequest = $this->toMobileLike($request);
        }

        //Data
        $vendor_id = $mobileLikeRequest->vendor_id;
        $expedition = $mobileLikeRequest->delivery_method;
        $hasPayment = $mobileLikeRequest->payment_method != 'cod';
        $isStripe = $mobileLikeRequest->payment_method == 'stripe';

        $vendorHasOwnPayment = null;
        if (config('settings.social_mode') || config('app.issd', false)) {
            //Find the vendor, and check if he has payment

            $vendor = Restorant::findOrFail($mobileLikeRequest->vendor_id);

            //Payment methods
            foreach (Module::all() as $key => $module) {
                if ($module->get('isPaymentModule')) {
                    if ($vendor->getConfig($module->get('alias').'_enable', 'false') == 'true') {
                        $vendorHasOwnPayment = $module->get('alias');
                    }
                }
            }

            if ($vendorHasOwnPayment == null) {
                $hasPayment = false;
            } else {
                //Since v3, don't auto select payment model, show all the  options to  user
                $vendorHasOwnPayment = 'all';
            }
        }

        //Repo Holder
        $orderRepo = OrderRepoGenerator::makeOrderRepo($vendor_id, $mobileLikeRequest, $expedition, $hasPayment, $isStripe, false, $vendorHasOwnPayment);
        //Proceed with validating the data
        $validator = $orderRepo->validateData();
        if ($validator->fails()) {
            notify()->error($validator->errors()->first());

            return $orderRepo->redirectOrInform();
        }

        //Proceed with making the order
        $validatorOnMaking = $orderRepo->makeOrder();
        if ($validatorOnMaking->fails()) {
            notify()->error($validatorOnMaking->errors()->first());

            return $orderRepo->redirectOrInform();
        }
        $user = auth()->user();
        $latestOrder = $user->orders()->latest()->first();
	if ($latestOrder instanceof Order && $request->has('randomID')) {
            $randomID = $request->randomID;
            $latestOrder->randomID = $randomID;
            $latestOrder->save();
        }
        if ($request->has('specialCod')){
            $latestOrder->codOnPickUp = 1;
            
        }
        if ($request->has('pickup_discount')){
            $latestOrder->pickup_discount = (float) $request->pickup_discount;
        }
        $broker = $latestOrder->restorant; 
        $setting = Settings::where('id',1)->first();
       	if ($latestOrder->payment_method == 'cod'){
            if ($latestOrder instanceof Order ) {
                $totalOrders = Order::count();
                $randomID = str_pad($broker->id, 3, '0', STR_PAD_LEFT) . '000' . $totalOrders; 
                $latestOrder->randomID = $randomID;
                $latestOrder->save();
            }
        }
        if ($broker->counter == 'none'){
            $status = Status::where('id', 2)->first();
            $latestOrder->status()->attach($status->id, ['user_id' => $user->id]); 
		ApiLog::create([
                'status_code' => 200,
                'order_id' => $latestOrder->id,
                'api_endpoint' => 'NA',
                'request_payload' => 'NA',
                'request_response' => 'OK',
                'counter' => $broker->counter,
                'broker' => $broker->broker ? $broker->broker : $broker->name,
                'orderId' => $latestOrder->randomID
            ]); 
        } else if ($broker->counter == 'expert-order' && !empty($broker->api_key) && !empty($setting->expertOrder)) {
            $this->submitOrder($user , $latestOrder , $broker,$setting->expertOrder);
        }        
        return $orderRepo->redirectOrInform();
    }

    public function submitOrder($user , $latestOrder , $broker, $url) {
        $orderTime = $latestOrder->created_at->format("Y-m-d\TH:i:s\Z");
        $deliveryTime = $this->generateDeliveryTime($latestOrder->delivery_pickup_interval);
        // $deliveryTime = $latestOrder->created_at->addHour()->format("Y-m-d\TH:i:s\Z");
        $orderItems = Orderitems::where('order_id', $latestOrder->id)->get();
        $address = Address::find($latestOrder->address_id);
        $addressObj = $address ? [
            "addressInfo" => $address->addressinfo,
            "location" => $address->city ?? "NA",
            "street" => $address->street,
            "zip" => $address->zip,
            "name" => $address->name,
            "email" => $address->email,
            "phone" => $address->phone,
            "mobileFormat" => $address->mobileFormat,
            "departmentname" => $address->departmentname,
            "companyname" => $address->companyname
        ] : [
            "addressInfo" => 'NA 1',
            "location" => 'NA 1',
            "street" => 'NA 1',
            "zip" => '00000',
            "name" => $user->name,
            "email" => $user->email,
            "phone" => $user->phone,
            "mobileFormat" => '',
            "departmentname" => 'NA',
            "companyname" => 'NA'
        ];

        $total_prepaid_amount = round( $this->format_price($latestOrder->order_price)  + ($this->format_price($latestOrder->tip) ?: 0)  - ($this->format_price($latestOrder->discount) ?: 0),2);

        Log::info('Submitting order for user: '.$user->id.' with total amount: '.$total_prepaid_amount);
        Log::info('Order items: '.json_encode($orderItems));

        $itemsArray = [];
        foreach ($orderItems as $item) {
            $itemId = $item->item_id;
            $findItem = Items::find($itemId);
            
            if ($findItem) {
                $group = $findItem->category->name;
                $name = $findItem->name;
                ($item->variant_name) ? $name .= ' (' . $item->variant_name . ')' : '';
                ($item->extras) ? $name .= ' (' . implode(', ', json_decode($item->extras, true)) . ')' : '';
                // Update price according to the variant selected + extras
                // $price = $findItem->price;
                $price = round($item->variant_price, 2);
                $type = $findItem->type;
                $uid = $findItem->uid;
                
                $formattedPrice = $this->format_price($price);
                
                $itemsArray[] = [
                    "uid" => $uid,
                    "count" => $item->qty, 
                    "name" => $name, 
                    "price" => $formattedPrice,
                    "info" => implode(', ', json_decode($item->extras ?? '[]', true)),
                    "group" => $group, 
                    "type" => $type 
                ];
            }
        }
    
        $unique_id = "ORDER_" . time();
        $orderOnCode = $latestOrder->codOnPickUp;

        if($latestOrder->payment_method == 'cod'){
            $setStatus = ($orderOnCode == 1) ? 1 : 0;
        } else {
            $setStatus = 3;
        }
        $publicIP = file_get_contents('https://api64.ipify.org?format=json');
        $publicIP = json_decode($publicIP)->ip;
        // $setStatus = ($orderOnCode == 1) ? 1 : (($latestOrder->payment_method == 'cod') ? 0 : 3);
      
        $order_data = [
            "version" => 1,
            "broker" => $broker->broker ? $broker->broker : $broker->name,
            "fromMobile" => false, 
            "clientIp" => $publicIP,
            "id" => $unique_id,
            "ordertime" => $orderTime,
            "deliverytime" => $deliveryTime,
            "orderprice" => $this->format_price($total_prepaid_amount),
            "orderdiscount" => $this->format_price(-$latestOrder->discount),
            "deliverycost" => $this->format_price($latestOrder->delivery_price),
            "tip" => $this->format_price($latestOrder->tip), 
            "customer" => [
                "name" => $addressObj['name'],
                "phone" => (strpos($addressObj['phone'], '+') !== false) ? $addressObj['phone'] : $addressObj['mobileFormat'] . $addressObj['phone'],
                "email" => $addressObj['email'],
                "street" => $addressObj['street'],
                "zip" => strval($addressObj['zip']),
                "location" => $addressObj['location'],
                "addressinfo" => $addressObj['addressInfo'],
                "departmentname" => ($latestOrder->delivery_method == 1) ? $addressObj['departmentname'] : '',
                "companyname" => $addressObj['companyname']
            ],
            "payment" => [
                "type" =>  $setStatus,
                "provider" => $latestOrder->payment_method,
                "transactionid" => "TX" . time(),
                "prepaid" => $total_prepaid_amount
            ],
            "items" => $itemsArray,
            "bonuscard" => $latestOrder->coupon ?? '',
            "notification" => ($latestOrder->delivery_method == 2) ? true : false,
            "customerinfo" =>$latestOrder->comment,
            // "info" => $latestOrder->comment
        ];
        $json_data = json_encode($order_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); 
       
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'API_KEY: ' . $broker->api_key
        ]);
        $response = curl_exec($ch);
       
        
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code == 200) {
  
                $status = Status::where('id', 14)->first();
                $latestOrder->status()->attach($status->id, ['user_id' => $user->id]); 
            
            
        } else {
            $status = Status::where('name', $http_code)->first();
            if (!$status) {
                $status = new Status;
                $status->name = (string) $http_code;
                $status->alias = (string) $http_code;
                $status->save();
            }
            $latestOrder->status()->attach($status->id, ['user_id' => $user->id]); 
        }
        curl_close($ch);
        $api_response = json_decode($response, true);
        $api_log = ApiLog::create([
            'status_code' => $http_code,
            'order_id' => $latestOrder->id,
            'api_endpoint' => $url,
            'request_payload' => $json_data,
            'request_response' => empty($response) ? 'OK' : $response,
            'counter' => $broker->counter,
            'broker' => $broker->broker ? $broker->broker : $broker->name,
            'orderId' => $latestOrder->randomID
        ]); 
    }

    protected function generateDeliveryTime($time)
    {
        $interval = explode('_', $time);
        $startTimeInMinutes = (int)$interval[0];
        $hours = intdiv($startTimeInMinutes, 60);
        $minutes = $startTimeInMinutes % 60;
        $startTime = Carbon::createFromTime($hours, $minutes, 0)->format("Y-m-d\TH:i:s\Z");
        return $startTime;
    }

    public function orderLocationAPI(Order $order): JsonResponse
    {
        if ($order->status->pluck('alias')->last() == 'picked_up') {
            return response()->json(
                [
                    'status' => 'tracing',
                    'lat' => $order->lat,
                    'lng' => $order->lng,
                ]
            );
        } else {
            return response()->json(['status' => 'not_tracing']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($orderNumber)
    {
        //Do we have pdf invoice
 	$order = Order::where('id',$orderNumber)->first();
        if (!$order){
            $order = Order::all()->firstWhere('randomID', $orderNumber);
        }
        $pdFInvoice = Module::has('pdf-invoice');

        //Change currency
        ConfChanger::switchCurrency($order->restorant);

        //Change language
        ConfChanger::switchLanguage($order->restorant);

        if ($order->restorant) {
            //Set config based on restaurant
            config(['app.timezone' => $order->restorant->getConfig('time_zone', config('app.timezone'))]);
        }

        $drivers = auth()->user()->myDrivers();

        $driversData = [];
        foreach ($drivers as $key => $driver) {
            $driversData[$driver->id] = $driver->name;
        }

        if (auth()->user()->hasRole('client') && auth()->user()->id == $order->client_id ||
            auth()->user()->hasRole('owner') && auth()->user()->id == $order->restorant->user->id ||
            auth()->user()->hasRole('staff') && auth()->user()->restaurant_id == $order->restorant->id ||
                auth()->user()->hasRole('driver') && auth()->user()->id == $order->driver_id || auth()->user()->hasRole('admin')
        ) {

            $orderModules = [];
            foreach (Module::all() as $key => $module) {
                if ($module->get('isOrderModule')) {
                    array_push($orderModules, $module->get('alias'));
                }
            }

            return view('orders.show', [
                'order' => $order,
                'pdFInvoice' => $pdFInvoice,
                'custom_data' => $order->getAllConfigs(),
                'statuses' => Status::pluck('name', 'id'),
                'drivers' => $drivers,
                'orderModules' => $orderModules,
                'fields' => [['class' => 'col-12', 'classselect' => 'noselecttwo', 'ftype' => 'select', 'name' => 'Driver', 'id' => 'driver', 'placeholder' => 'Assign Driver', 'data' => $driversData, 'required' => true]],
            ]);
        } else {
            return redirect()->route('orders.index')->withStatus(__('No Access.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        //
    }

    /**
     * Update the order item count
     *
     * @param  int  $id
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        if (auth()->user()->hasRole('owner') && auth()->user()->id == $order->restorant->user->id ||
            auth()->user()->hasRole('staff') && auth()->user()->restaurant_id == $order->restorant->id ||
            auth()->user()->hasRole('admin')
        ) {

            //Don't allow all 0 qty
            $zeroQty = 0;
            foreach ($order->items()->get() as $key => $item) {
                if ($item->pivot->id == $request->pivot_id) {

                    if ($request->item_qty.'' == '0') {
                        $zeroQty++;
                    }
                } else {
                    if ($item->pivot->qty == 0) {
                        $zeroQty++;
                    }
                }
            }
            if ($zeroQty == $order->items()->count()) {
                return redirect()->route('orders.show', $order->id)->withStatus(__('Can not set all qty to 0. You can reject order instead'));
            }

            //Directly find the pivot
            foreach ($order->items()->get() as $key => $item) {
                if ($item->pivot->id == $request->pivot_id) {
                    $oi = Orderitems::findOrFail($item->pivot->id);
                    $oi->qty = $request->item_qty;
                    $oi->update();

                    //$order->items()->updateExistingPivot($item, array('qty' => $request->item_qty), false);
                    $totalRecaluclatedVAT = $request->item_qty * ($item->vat > 0 ? $item->pivot->variant_price * ($item->vat / 100) : 0);

                    $oi->vatvalue = $request->$totalRecaluclatedVAT;
                    $oi->update();
                    //$order->items()->updateExistingPivot($item, array('vatvalue' => $totalRecaluclatedVAT), false);
                }
            }

            //After we have updated the list of items, we need to update the order price
            $order_price = 0;
            $total_order_vat = 0;
            foreach ($order->items()->get() as $key => $item) {
                $order_price += $item->pivot->qty * $item->pivot->variant_price;
                $total_order_vat += $item->pivot->vatvalue;
            }
            $order->order_price = $order_price;
            $order->vatvalue = $total_order_vat;
            $order->update();

            //If this order have discount, recaluclate deduct, it can be percentage based
            if (strlen($order->coupon) > 0) {
                $coupon = Coupons::where(['code' => $order->coupon])->get()->first();
                if ($coupon) {
                    $deduct = $coupon->calculateDeduct($order->order_price);
                    if ($deduct) {
                        $order->discount = $deduct;
                    }
                }
            }
            $order->update();

            return redirect()->route('orders.show', $order->id)->withStatus(__('Order updated.'));
        //You can update the order
        } else {
            return redirect()->route('orders.show', $order->id)->withStatus(__('No Access.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
    }

    public function liveapi(): JsonResponse
    {

        if (auth()->user()->hasRole('client')) {
            abort(404, 'Not allowed as client');
        }

        //Today only
        $orders = Order::where('created_at', '>=', Carbon::today())->orderBy('created_at', 'desc');

        //If owner, only from his restorant
        if (auth()->user()->hasRole('owner') || auth()->user()->hasRole('staff')) {
            $resto = auth()->user()->restorant;

            $orders = $orders->where(['restorant_id' => $resto->id]);

            //Change currency
            ConfChanger::switchCurrency($resto);

            //Set config based on restaurant
            config(['app.timezone' => $resto->getConfig('time_zone', config('app.timezone'))]);

            //Change language
            //ConfChanger::switchLanguage($order->restorant);
        }
        $orders = $orders->with(['status', 'client', 'restorant', 'table.restoarea'])->get()->toArray();

        $newOrders = [];
        $acceptedOrders = [];
        $doneOrders = [];

        $items = [];
        foreach ($orders as $key => $order) {
            $client = '';
            if (config('app.isft')) {
                $client = $order['client']['name'];

                if ($order['table'] && $order['table']['restoarea'] && $order['table']['restoarea']['name'] && $order['table']['name']) {
                    $client = $order['table']['restoarea']['name'].' - '.$order['table']['name'];
                } elseif ($order['table'] && $order['table']['name']) {
                    $client = $order['table']['name'];
                } else {
                    //If the order was made by a registered user, returns his name
                    $client = $order['client']['name'];
                }
            } else {
                if (! config('settings.is_whatsapp_ordering_mode')) {
                    //QR
                    if ($order['table'] && $order['table']['restoarea'] && $order['table']['restoarea']['name'] && $order['table']['name']) {
                        $client = $order['table']['restoarea']['name'].' - '.$order['table']['name'];
                    } elseif ($order['table'] && $order['table']['name']) {
                        $client = $order['table']['name'];
                    }
                } else {
                    //WhatsApp
                    $client = $order['phone'];
                }
            }
            array_push($items, [
                'id' => $order['id'],
                'id_formated' => $order['id_formated'],
                'restaurant_name' => $order['restorant']['name'],
                'last_status' => count($order['status']) > 0 ? __($order['status'][count($order['status']) - 1]['name']) : 'Just created',
                'last_status_id' => count($order['status']) > 0 ? $order['status'][count($order['status']) - 1]['pivot']['status_id'] : 1,
                'time' => Carbon::create($order['updated_at'])->setTimezone(Carbon::now()->tzName)->locale(config('app.locale'))->isoFormat('LLLL'),
                'client' => $client,
                'link' => '/orders/'.$order['id'],
                'price' => money($order['order_price'], config('settings.cashier_currency'), config('settings.do_convertion')).'',
            ]);
        }

        //----- ADMIN ------
        if (auth()->user()->hasRole('admin')) {
            foreach ($items as $key => $item) {
                //Box 1 - New Orders
                //Today orders that are just created ( Needs approvment or rejection )
                //Box 2 - Accepted
                //Today orders approved by Restaurant , or by admin( Needs assign to driver )
                //Box 3 - Done
                //Today orders assigned with driver, or rejected
                if ($item['last_status_id'] == 1) {
                    $item['pulse'] = 'blob green';
                    array_push($newOrders, $item);
                } elseif ($item['last_status_id'] == 2 || $item['last_status_id'] == 3) {
                    $item['pulse'] = 'blob orangestatic';
                    if ($item['last_status_id'] == 3) {
                        $item['pulse'] = 'blob orange';
                    }
                    array_push($acceptedOrders, $item);
                } elseif ($item['last_status_id'] > 3) {
                    $item['pulse'] = 'blob greenstatic';
                    if ($item['last_status_id'] == 9 || $item['last_status_id'] == 8) {
                        $item['pulse'] = 'blob redstatic';
                    }
                    array_push($doneOrders, $item);
                }
            }
        }

        //----- Restaurant ------
        if (auth()->user()->hasRole('owner') || auth()->user()->hasRole('staff')) {
            foreach ($items as $key => $item) {

                //Box 1 - New Orders
                //Today orders that are approved by admin ( Needs approvment or rejection )
                //Box 2 - Accepted
                //Today orders approved by Restaurant ( Needs change of status to done )
                //Box 3 - Done
                //Today completed or rejected
                $last_status = $item['last_status_id'];
                if ($last_status == 2 || $last_status == 10 || ($item['last_status_id'] == 1)) {
                    $item['pulse'] = 'blob green';
                    array_push($newOrders, $item);
                } elseif ($last_status == 3 || $last_status == 4 || $last_status == 5) {
                    $item['pulse'] = 'blob orangestatic';
                    if ($last_status == 3) {
                        $item['pulse'] = 'blob orange';
                    }
                    array_push($acceptedOrders, $item);
                } elseif ($last_status > 5 && $last_status != 8) {
                    $item['pulse'] = 'blob greenstatic';
                    if ($last_status == 9 || $last_status == 8) {
                        $item['pulse'] = 'blob redstatic';
                    }
                    array_push($doneOrders, $item);
                }
            }
        }

        $toRespond = [
            'neworders' => $newOrders,
            'accepted' => $acceptedOrders,
            'done' => $doneOrders,
        ];

        return response()->json($toRespond);
    }

    public function live(): View
    {
        return view('orders.live');
    }

    public function autoAssignToDriver(Order $order)
    {
        //The restaurant id
        $restaurant_id = $order->restorant_id;
        if ($order->restorant->self_deliver.'' == '1') {
            //Don't use self assign when restaurant deliver on their own
            return null;
        }

        //1. Get all the working drivers, where active and working
        $theQuery = User::role('driver')->where(['active' => 1, 'working' => 1])->whereNull('restaurant_id');

        //2. Get Drivers with their assigned order, where payment_status is unpaid yet, this order is still not delivered and not more than 1
        $theQuery = $theQuery->whereHas('driverorders', function (Builder $query) {
            $query->where('payment_status', '!=', 'paid')->where('created_at', '>=', Carbon::today());
        }, '<=', 1);

        //Get Restaurant lat / lng
        $restaurant = Restorant::findOrFail($restaurant_id);
        $lat = $restaurant->lat;
        $lng = $restaurant->lng;

        //3. Sort drivers by distance from the restaurant
        $driversWithGeoIDS = $this->scopeIsWithinMaxDistance($theQuery, $lat, $lng, config('settings.driver_search_radius'), 'users')->pluck('id')->toArray();

        //4. The top driver gets the order
        if (count($driversWithGeoIDS) == 0) {
            //No driver found -- this will appear in  the admin list also in the list of free order so driver can get an order
        } else {
            //Driver found
            $order->driver_id = $driversWithGeoIDS[0];
            $order->update();
            $order->status()->attach([4 => ['comment' => 'System', 'user_id' => $driversWithGeoIDS[0]]]);

            //Now increment the driver orders
            $theDriver = User::findOrFail($order->driver_id);
            $theDriver->numorders = $theDriver->numorders + 1;
            $theDriver->update();
        }
    }

    public function updateStatus($alias, Order $order): RedirectResponse
    {
        if (isset($_GET['driver'])) {
            $order->driver_id = $_GET['driver'];
            $order->update();

            //Now increment the driver orders
            $theDriver = User::findOrFail($order->driver_id);
            $theDriver->numorders = $theDriver->numorders + 1;
            $theDriver->update();
        }

        if (isset($_GET['time_to_prepare'])) {
            $order->time_to_prepare = $_GET['time_to_prepare'];
            $order->update();
        }

        $status_id_to_attach = Status::where('alias', $alias)->value('id');

        //Check access before updating
        /**
         * 1 - Super Admin
         * accepted_by_admin
         * assigned_to_driver
         * rejected_by_admin.
         *
         * 2 - Restaurant
         * accepted_by_restaurant - 3
         * prepared
         * rejected_by_restaurant
         * picked_up
         * delivered
         *
         * 3 - Driver
         * picked_up
         * delivered
         */
        //

        $rolesNeeded = [
            'accepted_by_admin' => 'admin',
            'assigned_to_driver' => ['admin', 'owner', 'staff'],
            'rejected_by_admin' => 'admin',
            'accepted_by_restaurant' => ['owner', 'staff'],
            'prepared' => ['owner', 'staff'],
            'rejected_by_restaurant' => ['owner', 'staff'],
            'picked_up' => ['driver', 'owner', 'staff'],
            'delivered' => ['driver', 'owner', 'staff'],
            'closed' => ['owner', 'staff'],
            'accepted_by_driver' => ['driver'],
            'rejected_by_driver' => ['driver'],
        ];

        if (! auth()->user()->hasRole($rolesNeeded[$alias])) {
            abort(403, 'Unauthorized action. You do not have the appropriate role');
        }

        //For owner - make sure this is his order
        if (auth()->user()->hasRole('owner')) {
            //This user is owner, but we must check if this is order from his restaurant
            if (auth()->user()->id != $order->restorant->user_id) {
                abort(403, 'Unauthorized action. You are not owner of this order restaurant');
            }
        }

        if (auth()->user()->hasRole('staff')) {
            //This user is owner, but we must check if this is order from his restaurant
            if (auth()->user()->restaurant_id != $order->restorant->id) {
                abort(403, 'Unauthorized action. You are not owner of this order restaurant');
            }
        }

        //For driver - make sure he is assigned to this order
        if (auth()->user()->hasRole('driver')) {
            //This user is owner, but we must check if this is order from his restaurant
            if (auth()->user()->id != $order->driver->id) {
                abort(403, 'Unauthorized action. You are not driver of this order');
            }
        }

        /**
         * IF status
         * Accept  - 3
         * Prepared  - 5
         * Rejected - 9.
         */
        if ($order->client) {
            if ($status_id_to_attach.'' == '3' || $status_id_to_attach.'' == '5' || $status_id_to_attach.'' == '9') {
                $order->client->notify(new OrderNotification($order, $status_id_to_attach));
            }

            if ($status_id_to_attach.'' == '4') {
                $order->driver->notify(new OrderNotification($order, $status_id_to_attach));
            }
        }

        //Picked up - start tracing
        if ($status_id_to_attach.'' == '6') {
            $order->lat = $order->restorant->lat;
            $order->lng = $order->restorant->lng;
            $order->update();
        }

        if (config('app.isft') && $alias.'' == 'delivered') {
            $order->payment_status = 'paid';
            $order->update();
        }

        if (config('app.isqrsaas') && $alias.'' == 'closed') {
            //Check if order is not paid  before, payment method should be cash
            if ($order->payment_status != 'paid' && $order->payment_method != 'cod') {
                $order->payment_method = 'cod';
            }
            $order->payment_status = 'paid';
            $order->update();
        }

        if ($alias.'' == 'rejected_by_admin' || $alias.'' == 'rejected_by_restaurant') {
            $order->payment_status = '';
            $order->update();
        }

        if (config('app.isft')) {
            //When orders is accepted by restaurant, auto assign to driver
            if ($status_id_to_attach.'' == '3') {
                if (config('settings.allow_automated_assign_to_driver')) {
                    $this->autoAssignToDriver($order);
                }
            }
        }

        $order->status()->attach([$status_id_to_attach => ['comment' => '', 'user_id' => auth()->user()->id]]);

        //Dispatch event
        if ($alias == 'accepted_by_restaurant') {
            OrderAcceptedByVendor::dispatch($order);
        }
        if ($alias == 'accepted_by_admin') {
            //IN FT send email
            if (config('app.isft')) {
                $order->restorant->user->notify((new OrderNotification($order))->locale(strtolower(config('settings.app_locale'))));
            }

            OrderAcceptedByAdmin::dispatch($order);
        }

        UpdateOrder::dispatch(['id'=>$order->id],'',$order->restorant->user->id, $order->status()->get()->toArray());

        return redirect()->route('orders.index')->withStatus(__('Order status succesfully changed.'));
    }

    public function rateOrder(Request $request, Order $order): RedirectResponse
    {
        $restorant = $order->restorant;

        $rating = new Rating;
        $rating->rating = $request->ratingValue;
        $rating->user_id = auth()->user()->id;
        $rating->order_id = $order->id;
        $rating->comment = $request->comment;

        $restorant->ratings()->save($rating);

        return redirect()->route('orders.show', ['order' => $order])->withStatus(__('Order succesfully rated!'));
    }

    public function checkOrderRating(Order $order): JsonResponse
    {
        $rating = DB::table('ratings')->select('rating')->where(['order_id' => $order->id])->get()->first();
        $is_rated = false;

        if (! empty($rating)) {
            $is_rated = true;
        }

        return response()->json(
            [
                'rating' => $rating->rating,
                'is_rated' => $is_rated,
            ]
        );
    }

    public function guestOrders(): View
    {
        $previousOrders = Cookie::get('orders') ? Cookie::get('orders') : '';
        $previousOrderArray = array_filter(explode(',', $previousOrders));

        //Find the orders
        $orders = Order::whereIn('id', $previousOrderArray)->orderBy('id', 'desc')->get();
        $backUrl = url()->previous();
        $lastOrder = null;
        foreach ($orders as $key => $order) {
            //Change currency
            $lastOrder = $order;
            $backUrl = route('vendor', $order->restorant->subdomain);
        }

        $showWhatsApp = config('settings.whatsapp_ordering_enabled');
        if ($lastOrder) {
            ConfChanger::switchCurrency($lastOrder->restorant);
            //Should we show whatsapp send order
            if ($showWhatsApp) {
                //Disable when WhatsApp Mode
                if (config('settings.is_whatsapp_ordering_mode')) {
                    $showWhatsApp = false;
                }

                //In QR, if owner phone is not set, hide the button
                //In FT, we use owner phone to have the number
                if (strlen($order->restorant->whatsapp_phone) < 3) {
                    $showWhatsApp = false;
                }
            }
        }

        return view('orders.guestorders', ['showWhatsApp' => $showWhatsApp, 'backUrl' => $backUrl, 'orders' => $orders, 'statuses' => Status::pluck('name', 'id')]);
    }

    public function generateOrderMsg($address, $comment, $price)
    {
        $title = 'New order #'.strtoupper(Str::random(5))."\n\n";

        $price = '*Price*: '.$price.' '.config('settings.cashier_currency')."\n\n";

        $items = '*Order:*'."\n";
        foreach (Cart::getContent() as $key => $item) {
            $items .= strval($item->quantity).' x '.$item->name."\n";
        }
        $items .= "\n";
        $final = $title.$price.$items;

        if ($address != null) {
            $final .= '*Address*:'."\n".$address."\n\n";
        }

        if ($comment != null) {
            $final .= '*Comment:*'."\n".$comment."\n\n";
        }

        return urlencode($final);
    }

    public function fbOrderMsg(Request $request): JsonResponse
    {
        $orderPrice = Cart::getSubTotal();

        $title = 'New order #'.strtoupper(Str::random(5))."\n\n";

        $price = '*Price*: '.$orderPrice.' '.config('settings.cashier_currency')."\n\n";

        $items = '*Order:*'."\n";
        foreach (Cart::getContent() as $key => $item) {
            $items .= strval($item->quantity).' x '.$item->name."\n";
        }
        $items .= "\n";
        $final = $title.$price.$items;

        if ($request->address != null) {
            $final .= '*Address*:'."\n".$request->address."\n\n";
        }

        if ($request->comment != null) {
            $final .= '*Comment:*'."\n".$request->comment."\n\n";
        }

        return response()->json(
            [
                'status' => true,
                'msg' => $final,
            ]
        );
    }

    public function storeWhatsappOrder(Request $request): RedirectResponse
    {
        $restorant_id = null;
        foreach (Cart::getContent() as $key => $item) {
            $restorant_id = $item->attributes->restorant_id;
        }

        $restorant = Restorant::findOrFail($restorant_id);

        $orderPrice = Cart::getSubTotal();

        if ($request->exists('deliveryType')) {
            $isDelivery = $request->deliveryType == 'delivery';
        }

        $text = $this->generateWhatsappOrder($request->exists('addressID') ? $request->addressID : null, $request->exists('comment') ? $request->comment : null, $orderPrice);

        $url = 'https://wa.me/'.$restorant->whatsapp_phone.'?text='.$text;

        Cart::clear();

        return Redirect::to($url);
    }

    public function cancel(Request $request): View
    {
        $order = Order::findOrFail($request->order);

        return view('orders.cancel', ['order' => $order]);
    }

    public function silentWhatsAppRedirect(Request $request): View
    {
        $order = Order::findOrFail($request->order);
        $message = $order->getSocialMessageAttribute(true);
        $url = 'https://api.whatsapp.com/send?phone='.$order->restorant->whatsapp_phone.'&text='.$message;

        return view('orders.success', ['order' => $order, 'showWhatsApp' => false, 'whatsappurl' => $url]);
    }

    public function success(Request $request)
    {
        $order = Order::findOrFail($request->order);
        //If order is not paid - redirect to payment
        if ($request->redirectToPayment.'' == '1' && $order->payment_status != 'paid' && strlen($order->payment_link) > 5) {
            //Redirect to payment
            return redirect($order->payment_link);
        }

        //If we have whatsapp send
        if ($request->has('whatsapp')) {
            $message = $order->getSocialMessageAttribute(true);
            $url = 'https://api.whatsapp.com/send?phone='.$order->restorant->whatsapp_phone.'&text='.$message;

            return Redirect::to($url);
        }

        //Should we show whatsapp send order
        $showWhatsApp = config('settings.whatsapp_ordering_enabled') && ! config('app.isdrive', false);

        if ($showWhatsApp) {
            //Disable when WhatsApp Mode
            if (config('settings.is_whatsapp_ordering_mode')) {
                $showWhatsApp = false;
            }

            //In QR, if owner phone is not set, hide the button
            //In FT, we use owner phone to have the number
            if (strlen($order->restorant->whatsapp_phone) < 3) {
                $showWhatsApp = false;
            }
        }

        // TODO: Check if the order is paid
        // Send Email to the customer about order
        // We should use Webhooks to send payment confirmation email
	if($order->payment_status == 'paid' || $order->payment_method == 'cod'){
        	$order->client->notify((new OrderNotification($order, 200, $order->client))->locale(strtolower(config('settings.app_locale'))));
        	$order->restorant->user->notify((new OrderNotification($order, 1, $order->restorant->user))->locale(strtolower(config('settings.app_locale'))));

    	}   

        return view('orders.success', ['order' => $order, 'showWhatsApp' => $showWhatsApp]);
    }
}
