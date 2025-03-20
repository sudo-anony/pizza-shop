<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Refund;
use Stripe\Charge;
use App\Order;

class RefundController extends Controller
{
    
    public function __construct()
    {
        // Set your Stripe API key
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Issue a refund for a specific charge.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refundCharge(Request $request)
    {
        $request->validate([
            'charge_id' => 'required|string', // The charge ID from Stripe
            'amount' => 'nullable|integer|min:1', // Optional amount to refund (in cents)
        ]);

        dd($request->all());

        $chargeId = $request->input('charge_id');
        $refundAmount = $request->input('amount'); // Optional, if not provided full refund is issued

        try {
            // Create the refund
            $refund = Refund::create([
                'charge' => $chargeId,
                'amount' => $refundAmount, // If null, it will refund the full amount
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Refund successful!',
                'refund' => $refund,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Refund failed!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Retrieve a refund by its ID.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRefund(Request $request)
    {
        $request->validate([
            'refund_id' => 'required|string', // The refund ID from Stripe
        ]);

        $refundId = $request->input('refund_id');

        try {
            // Retrieve the refund
            $refund = Refund::retrieve($refundId);

            return response()->json([
                'success' => true,
                'refund' => $refund,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Refund not found!',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Test Get the Charge Id from the payment id.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChargeId(Request $request, Order $order)
    {
        $request->validate([
            // 'payment_id' => 'required|string', // The payment ID from Stripe
        ]);

        
        $paymentId = $order->stripe_payment_intent;
        
        // dd($paymentId);
        // $paymentId = 'pi_3PK2cHBH6H1eak0Y17EDGqws'; // Test payment ID
        try {
            // Retrieve the charge
            // $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentId);

            
            $charge = Charge::retrieve($order->stripe_charge_id);

            if($charge->refunded == true){
                if(!$order->status->contains(22)){
                    $order->status()->attach(22, ['comment' => 'Refund Requested!', 'user_id' => auth()->user()->id, 'created_at' => now()]);
                }
                return redirect()->back()->with('success', 'Refund Requested!');
            }

            $refund = Refund::create([
                'charge' => $charge->id,
                'amount' => $charge->amount_captured, // If null, it will refund the full amount
            ]);

            if($refund !== null){
                $order->refund_requested = now();
                $order->save();
                // 
                if(!$order->status->contains(22)){
                    $order->status()->attach(22, ['comment' => 'Refund Requested!', 'user_id' => auth()->user()->id, 'created_at' => now()]);
                }
            }

            return redirect()->back()->with('success', 'Refund Requested!');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Charge not found!',
                'error' => $e->getMessage(),
            ], 404);
        }
    }
}

