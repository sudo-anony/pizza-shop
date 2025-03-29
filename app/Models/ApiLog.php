<?php

namespace App\Models;
use App\Order;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $table = 'api_logs';

    protected $fillable = [
        'api_endpoint',
        'request_payload',
        'status_code',
        'order_id',
        'orderId',
        'counter',
        'broker',
        'request_response'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    

}
