<?php

namespace App\Repositories\Orders\POS;

use App\Repositories\Orders\POSOrderRepository;
use App\Traits\Expedition\HasDineIn;
use App\Traits\Payments\HasLinkPayment;

class DineinLinkPaymentOrder extends POSOrderRepository
{
    use HasDineIn;
    use HasLinkPayment;
}
