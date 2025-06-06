<?php

namespace App\Repositories\Orders\POS;

use App\Repositories\Orders\POSOrderRepository;
use App\Traits\Expedition\HasDelivery;
use App\Traits\Payments\HasLinkPayment;

class DeliveryLinkPaymentOrder extends POSOrderRepository
{
    use HasDelivery;
    use HasLinkPayment;
}
