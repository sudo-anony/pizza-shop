<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Restorant;

class EmailLog extends Model
{
    protected $table = 'email_logs';

    protected $fillable = [
        'receiver',
        'subject',
        'content',
        'restaurant_id',
    ];

    protected $appends = ['restaurant_name'];

    public function restaurant()
    {
        return $this->belongsTo(Restorant::class, 'restaurant_id', 'id');
    }

    // restaurant_name attribute
    public function getRestaurantNameAttribute()
    {
        return $this->restaurant ? $this->restaurant->name : null;
    }
}
