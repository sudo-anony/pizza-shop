<?php

namespace App\Http\Controllers;

use Akaunting\Module\Facade as Module;
use App\Order;
use Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function view(Request $request): View
    {
        $total = Money(Cart::getSubTotal(), config('settings.cashier_currency'), config('settings.do_convertion'))->format();

        //Clear cart
        Cart::clear();

        return view('payment.payment', [
            'total' => $total,
        ]);
    }

    public function handleOrderPaymentStripe(Request $request, Order $order): RedirectResponse
    {
        if ($request->success.'' == 'true') {
            dd($request->all());
            $order->payment_status = 'paid';
            $order->update();

            return redirect()->route('order.success', ['order' => $order]);
        } else {
            return redirect()->route('vendor', $order->restorant->subdomain)->withMesswithErrorage($request->message)->withInput();
        }
    }

    public function selectPaymentGateway(Order $order): View
    {
        $paymentMethods = [];

        $vendor = $order->restorant;

        //Payment methods
        foreach (Module::all() as $key => $module) {
            if ($module->get('isPaymentModule')) {
                if ($vendor->getConfig($module->get('alias').'_enable', 'false') == 'true') {
                    $vendorHasOwnPayment = $module->get('alias');
                    $paymentMethods[$module->get('alias')] = ucfirst($module->get('alias'));
                }
            }
        }

        return view('orders.multypay',
            [
                'paymentMethods' => $paymentMethods,
                'order' => $order,
                'showWhatsApp' => false,
                'whatsappurl' => '',
            ]);
    }

    public function selectedPaymentGateway(Order $order, $paymentMethod): RedirectResponse
    {
        $order->payment_method = $paymentMethod;
        $className = '\Modules\\'.ucfirst($paymentMethod).'\Http\Controllers\App';
        $ref = new \ReflectionClass($className);
        $ref->newInstanceArgs([$order, $order->restorant])->execute();

        return redirect()->route('order.success', ['order' => $order, 'redirectToPayment' => true]);
    }
}
