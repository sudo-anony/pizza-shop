<?php

namespace Modules\Coupons\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Coupons\Models\Company;
use Modules\Coupons\Models\Coupons;
use Carbon\Carbon;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponsController extends Controller
{
    /**
     * Provide class.
     */
    private $provider = Coupons::class;

    /**
     * Web RoutePath for the name of the routes.
     */
    private $webroute_path = 'coupons.';

    /**
     * View path.
     */
    private $view_path = 'coupons::coupons.';

    /**
     * Parameter name.
     */
    private $parameter_name = 'coupon';

    /**
     * Title of this crud.
     */
    private $title = 'coupon';

    /**
     * Title of this crud in plural.
     */
    private $titlePlural = 'coupons';

    public function getLocalCompany()
    {
        $company= $this->getCompany()->id;
        return Company::findOrFail($company);
    }

    private function getFields()
    {
        return [
            ['class'=>'col-md-4', 'ftype'=>'input', 'name'=>'Name', 'id'=>'name', 'placeholder'=>'Enter coupon code name', 'required'=>true],
            ['class'=>'col-md-4', 'ftype'=>'input', 'type'=>'text', 'name'=>'Code', 'id'=>'code', 'placeholder'=>'Enter coupon code number', 'required'=>true],
            ['ftype'=>'select', 'name'=>'Price', 'id'=>'type', 'placeholder'=>'Select type', 'data'=>['Fixed', 'Percentage'], 'required'=>true],
            ['ftype'=>'select', 'name'=>'Active from', 'id'=>'type', 'placeholder'=>'Select type', 'data'=>['Fixed', 'Percentage'], 'required'=>true],
            ['ftype'=>'select', 'name'=>'Active to', 'id'=>'type', 'placeholder'=>'Select type', 'data'=>['Fixed', 'Percentage'], 'required'=>true],
            ['ftype'=>'select', 'name'=>'Limit number', 'id'=>'type', 'placeholder'=>'Select type', 'data'=>['Fixed', 'Percentage'], 'required'=>true],
            ['ftype'=>'select', 'name'=>'Used from', 'id'=>'type', 'placeholder'=>'Select type', 'data'=>['Fixed', 'Percentage'], 'required'=>true],
        ];
    }
    private function getFilterFields(){
        $fields=$this->getFields();
        $fields[0]['required']=false;
        $fields[1]['required']=false;
        unset($fields[2]);
        unset($fields[3]);
        unset($fields[4]);
        unset($fields[5]);
        unset($fields[6]);

        return $fields;
    }

    /**
     * Auth checker functin for the crud.
     */
    private function authChecker($theCoupon=null)
    {
        //If coupons is null, validate owner and staff only
        $this->ownerAndStaffOnly();
        if($theCoupon != null){
            if($theCoupon->company_id != $this->getLocalCompany()->id){
                abort(403);
            }
        }
       
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authChecker();
        $items=$this->getLocalCompany()->coupons();
        if(isset($_GET['name'])){
            $items=$items->where('name', 'like', '%'.$_GET['name'].'%');
        }
        if(isset($_GET['code'])){
            $items=$items->where('code', 'like', '%'.$_GET['code'].'%');
        }
        $items=$items->paginate(config('settings.paginate'));

        return view($this->view_path.'index', ['setup' => [
            'usefilter'=>true,
            'title'=>__('crud.item_managment', ['item'=>__($this->titlePlural)]),
            'action_link'=>route($this->webroute_path.'create'),
            'action_name'=>__('crud.add_new_item', ['item'=>__($this->title)]),
            'items'=>$items,
            'item_names'=>$this->titlePlural,
            'webroute_path'=>$this->webroute_path,
            'fields'=>$this->getFields(),
            'filterFields'=>$this->getFilterFields(),
            'custom_table'=>true,
            'parameter_name'=>$this->parameter_name,
            'parameters'=>count($_GET) != 0,
        ]]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authChecker();

        return view($this->view_path.'create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authChecker();
        $item = $this->provider::create([
            'name' => $request->name,
            'code' => strtoupper(substr($this->getLocalCompany()->name, 0, 2).(Str::random(6))),
            'type' => $request->type,
            'price' => $request->type == 0 ? $request->price_fixed : $request->price_percentage,
            'active_from' => $request->active_from,
            'active_to' => $request->active_to,
            'limit_to_num_uses' => $request->limit_to_num_uses,
            'company_id' => $this->getLocalCompany()->id,
        ]);

        $item->save();

        return redirect()->route($this->webroute_path.'index')->withStatus(__('crud.item_has_been_added', ['item'=>__($this->title)]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Coupons  $coupons
     * @return \Illuminate\Http\Response
     */
    public function show(Coupons $coupons)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Coupons  $coupons
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupons $coupon)
    {
        $this->authChecker($coupon);
        return view($this->view_path.'create', ['coupon' => $coupon]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Coupons  $coupons
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = $this->provider::findOrFail($id);
        $this->authChecker($item);
        $item->name = $request->name;
        $item->code = $request->code;
        $item->type = $request->type;
        $item->price = $request->type == 0 ? $request->price_fixed : $request->price_percentage;
        $item->active_from = $request->active_from;
        $item->active_to = $request->active_to;
        $item->limit_to_num_uses = $request->limit_to_num_uses;

        $item->update();

        return redirect()->route($this->webroute_path.'index')->withStatus(__('crud.item_has_been_updated', ['item'=>__($this->title)]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Coupons  $coupons
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = $this->provider::findOrFail($id);
        $this->authChecker($item);
        $item->delete();
        return redirect()->route($this->webroute_path.'index')->withStatus(__('crud.item_has_been_removed', ['item'=>__($this->title)]));
    }

    public function use($id)
    {
        $item = $this->provider::findOrFail($id);
        $this->authChecker($item);
        $item->used_count=$item->limit_to_num_uses;
        $item->update();
        return redirect()->route($this->webroute_path.'index')->withStatus(__('crud.item_has_been_updated', ['item'=>__($this->title)]));
    }

    public function apply(Request $request)
    {
        $coupon = Coupons::where(['code' => $request->code])->get()->first();
        if($coupon){
            $deduct=$coupon->calculateDeduct($request->cartValue);
            if($deduct){
                //$coupon->decrement('limit_to_num_uses');
                //$coupon->increment('used_count');
                return response()->json([
                    'deduct' => $deduct,
                    'status' => true,
                    'msg' => __('Coupon code applied successfully.'),
                ]);
            }
        }
        return response()->json([
            'status' => false,
            'msg' => __('The coupon promotion code has been expired or the limit is exceeded.'),
        ]);
    }
}
