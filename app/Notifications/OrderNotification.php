<?php

namespace App\Notifications;

use Akaunting\Money\Currency;
use App\NotificationChannels\Expo\ExpoChannel;
use App\NotificationChannels\Expo\ExpoMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;
use NotificationChannels\OneSignal\OneSignalWebButton;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
use App\Address;
use Illuminate\Support\HtmlString;

class OrderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $order;

    protected $status;

    protected $user;

    public function __construct($order, $status = '1', $user = null)
    {
        $this->order = $order;
        $this->status = $status;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     */
    public function via($notifiable): array
    {
        $notificationClasses = ['database'];

        //Mail notification on vendor email
        if ($this->order->restorant->getConfig('enable_email_order_notification', false)) {
            array_push($notificationClasses, 'mail');
        } elseif (config('settings.send_order_email_to_vendor', false)) {
            array_push($notificationClasses, 'mail');
        }

        if (config('settings.onesignal_app_id')) {
            array_push($notificationClasses, OneSignalChannel::class);
        }
        if (config('settings.twilio_account_sid') && config('settings.send_sms_notifications')) {
            if ($this->order->client && strlen($this->order->client->phone) > 4) {
                array_push($notificationClasses, TwilioChannel::class);
            }
        }
        if ($this->user != null && strlen($this->user->expotoken) > 3) {
            array_push($notificationClasses, ExpoChannel::class);
        }

        return $notificationClasses;
    }

    public function toExpo($notifiable)
    {
        $messages = $this->getMessages();
        $greeting = $messages[0];
        $line = $messages[1];
        try {
            return ExpoMessage::create()
                ->title($greeting)
                ->body($line)
                ->badge(1);
        } catch (\Throwable $th) {
            //throw $th;
        }

    }

    public function toTwilio($notifiable)
    {
        if ($this->status.'' == '1') {
            //Created
            $line = $this->order->delivery_method.'' == '3' ? __('You have just received an order on table').' '.$this->order->table->name : __('You have just received an order');
        } elseif ($this->status.'' == '3') {
            //Accepted
            $line = __('Your order has been accepted. We are now working on it!');
        } elseif ($this->status.'' == '4') {
            //Assigned to driver
            $line = __('There is new order assigned to you.');
        } elseif ($this->status.'' == '5') {
            //Prepared
            $line = $this->order->delivery_method && $this->order->delivery_method.'' == '1' ? __('Your order is ready for delivery. Expect us soon.') : __('Your order is ready for pickup. We are expecting you.');
        } elseif ($this->status.'' == '9') {
            //Rejected
            $line = __('Unfortunately your order is rejected. There where issues with the order and we need to reject it. Pls contact us for more info.');
        }

        return (new TwilioSmsMessage())->content($line);
    }

    private function getMessages()
    {
        if ($this->status.'' == '1') {
            //Created
            $greeting = __('There is new order');
            $line = $this->order->delivery_method.'' == '3' ? __('You have just received an order on table').' '.$this->order->table->name : __('You have just received an order');
        } elseif ($this->status.'' == '3') {
            //Accepted
            $greeting = __('Your order has been accepted');
            $line = __('We are now working on it!');
        } elseif ($this->status.'' == '4') {
            //Assigned to driver
            $greeting = __('There is new order for you.');
            $line = __('There is new order assigned to you.');
        } elseif ($this->status.'' == '5') {
            //Prepared
            $greeting = __('Your order is ready.');
            $line = $this->order->delivery_method && $this->order->delivery_method.'' == '1' ? __('Your order is ready for delivery. Expect us soon.') : __('Your order is ready for pickup. We are expecting you.');
        } elseif ($this->status.'' == '9') {
            //Rejected
            $greeting = __('Order rejected');
            $line = __('Unfortunately your order is rejected. There where issues with the order and we need to reject it. Pls contact us for more info.');
        }

        return [$greeting.' #'.$this->order->id, $line];
    }

    public function toOneSignal($notifiable)
    {
        $messages = $this->getMessages();
        $greeting = $messages[0];
        $line = $messages[1];

        $url = url('/orders/'.$this->order->id);

        //Inders in the db

        return OneSignalMessage::create()
            ->subject($greeting)
            ->body($line)
            ->url($url)
            ->webButton(
                OneSignalWebButton::create('link-1')
                    ->text(__('View Order'))
                    ->icon('https://upload.wikimedia.org/wikipedia/commons/4/4f/Laravel_logo.png')
                    ->url($url)
            );
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        // Change currency
        \App\Services\ConfChanger::switchCurrency($this->order->restorant);
    
        $greeting = __('Order Update');
        $line = __('Your order status has been updated.');
    
        // Handle different statuses
        switch ($this->status.'') {
            case '1':
                $address = Address::find($this->order->address_id);
                $greeting = __('There is a new order').' #'.$this->order->randomID;
                $line = $this->order->delivery_method == '3' ? 
                    __('You have just received an order on table').' '.$this->order->table->name : 
                    __('You have just received an order');
                break;
            case '3':
                $greeting = __('Your order has been accepted');
                $line = __('We are now working on it!');
                break;
            case '4':
                $greeting = __('There is a new order for you.');
                $line = __('There is a new order assigned to you.');
                break;
            case '5':
                $greeting = __('Your order is ready.');
                $line = $this->order->delivery_method == '1' ? 
                    __('Your order is ready for delivery. Expect us soon.') : 
                    __('Your order is ready for pickup. We are expecting you.');
                break;
            case '9':
                $greeting = __('Order rejected');
                $line = __('Unfortunately, your order has been rejected. Please contact us for more info.');
                break;
            case '200':
                $address = Address::find($this->order->address_id);
                if ($address) {
                    $notifiable->email_override = $address->email ?? $this->order->client->email;
                } else {
                    $notifiable->email_override = $this->order->client->email;
                }
                $greeting = __('Order Payment Confirmation');
                if ($this->order->payment_method == 'cod') {
                    
                    $line = __('Your Cash on Delivery (COD) order has been received.');
                } else {
                    $line = __('Your payment has been successfully processed. Thank you for choosing ') . config('app.name') . '!';
                }
              
                
                break;
        }
        $restaurant = $this->order->restorant;
        if (!empty($restaurant)) {
          if (!empty($restaurant->logowide)) {
              $imagePath = asset($restaurant->logowide);
          }
        }
       
        $message = (new MailMessage)
            ->greeting(new HtmlString('<img src="' . $imagePath . '" style="width: 100px; height: auto; border-radius: 10px;" alt="Restaurant Logo"><br>' . $greeting))
            ->subject(__('Order Notification') . ' #' . $this->order->randomID)
            ->line($line)
            ->action(__('View Order'), url('/orders/'.$this->order->randomID));
    
        // **Restaurant Info**
        
        // **Customer Info**
        if (!empty($address)) {
            $customerDetails = new HtmlString(
                '<strong>' . __('Customer Details:') . '</strong><br>' .
                '<hr style="margin: 5px 0;">' .
                e($address->companyname ?? __('N/A')) . '<br>' .
                e($address->departmentname ?? __('N/A')) . '<br>' .
                e($this->order->client->name) .'<br>' . 
                e($address->street ?? __('N/A')) .'<br>' . 
                e($address->zip ?? __('N/A')) .' '. e($address->city ?? __('N/A')) . '<br><br>'
            );
        
            $message->line($customerDetails);
        }
        
        // **Order Items**
        $message->line("**" .__('Order Items:'). "**")->line(__('________________'));
    
        foreach ($this->order->items as $item) {
            $variantDetails = !empty($item->pivot->variant_name) ? ' - ' . $item->pivot->variant_name : '';
            $lineprice = $item->pivot->qty . ' X ' . $item->name . $variantDetails . 
                ' (' . money($item->pivot->variant_price, config('settings.cashier_currency'), config('settings.do_convertion')) . ') = ' .
                money($item->pivot->qty * $item->pivot->variant_price, config('settings.cashier_currency'), true);
            $message->line($lineprice);
    
            if (!empty($item->pivot->extras)) {
                $extras = json_decode($item->pivot->extras, true);
                
                if (is_array($extras) && count($extras) > 0) { // Check if array is not empty
                    $extrasHtml = '<strong>' . __('Extras:') . '</strong><br>';
                    
                    foreach ($extras as $extra) {
                        $extrasHtml .= '- ' . e($extra) . '<br>';
                    }
            
                    $message->line(new HtmlString($extrasHtml));
                }
            }

        }
    
        // **Order Summary**
        $message->line(__('________________'))
                ->line(__('Sub Total: ') . money($this->order->order_price, config('settings.cashier_currency'), config('settings.do_convertion')));
    
        if ($this->order->tip) {
            $message->line(__('Tip: ') . money($this->order->tip, config('settings.cashier_currency'), config('settings.do_convertion')));
        }
    
        if ($this->order->delivery_method == '1') {
            $message->line(__('Delivery Fee: ') . money($this->order->delivery_price, config('settings.cashier_currency'), config('settings.do_convertion')));
        }
    
        if ($this->order->discount > 0) {
            $message->line(__('Discount: ') . money($this->order->discount, config('settings.cashier_currency'), config('settings.do_convertion')));
        }
    
        $message->line(__('Total: ') . money($this->order->order_price_with_discount + $this->order->delivery_price, config('settings.cashier_currency'), config('settings.do_convertion')));
        return $message;
    }
    
    

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toArray($notifiable): array
    {
        return [];
    }

    public function toDatabase($notifiable)
    {
        if ($this->status.'' == '1') {
            //Created
            $greeting = __('There is new order');
            $line = __('You have just received an order');
        } elseif ($this->status.'' == '3') {
            //Accepted
            $greeting = __('Your order has been accepted');
            $line = __('order').'#'.$this->order->id_formated.' '.__('We are now working on it!');
        } elseif ($this->status.'' == '4') {
            //Assigned to driver
            $greeting = __('There is new order for you.');
            $line = __('There is new order assigned to you.');
        } elseif ($this->status.'' == '5') {
            //Prepared
            $greeting = __('Your order is ready.');
            $line = $this->order->delivery_method && $this->order->delivery_method.'' == '1' ? __('Your order is ready for delivery. Expect us soon.') : __('Your order is ready for pickup. We are expecting you.');
        } elseif ($this->status.'' == '9') {
            //Rejected
            $greeting = __('Order rejected');
            $line = __('Unfortunately your order is rejected. There where issues with the order and we need to reject it. Pls contact us for more info.');
        } elseif ($this->status.'' == '200') {
            $greeting = __('Order Payment Confirmation');
            $line = __('We are pleased to confirm that your recent order has been successfully processed, and payment has been received. Thank you for choosing ') . ' ' . config('app.name'). ''.'!' ;
        }

        return [
            'title' => $greeting,
            'body' => $line,
        ];
    }
}
