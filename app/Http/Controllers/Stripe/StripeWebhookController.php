<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        $endpointSecret = config('services.stripe.webhook_secret'); // Set your webhook secret in .env

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);

            // Handle the event
            switch ($event->type) {
                case 'charge.refunded':
                    $charge = $event->data->object; // contains a \Stripe\Charge
                    // Handle refund logic here
                    $order = Order::where('stripe_charge_id', $charge->id)->first();
                    if(!$order->status->contains(22)){
                        $order->status()->attach(22);
                    }
                    Log::info('Charge was Refunded!');
                    break;
                case 'charge.succeeded':
                    Log::info('Charge was successful!');
                    Log::info($event);
                    break;
                case 'payment_intent.succeeded':
                    Log::info('Payment Intent was successful!');
                    Log::info($event);
                    break;
                case 'payment_intent.payment_failed':
                    Log::info('Payment Intent failed!');
                    Log::info($event);
                    break;
                default:
                    // Unexpected event type
                    http_response_code(400);
                    exit();
            }

            http_response_code(200);
        } catch (\Exception $e) {
            // Invalid payload or signature
            http_response_code(400);
            exit();
        }
    }
}