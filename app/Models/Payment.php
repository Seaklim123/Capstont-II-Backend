<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
use HasFactory;

    protected $fillable = [
        'order_information_id',
        'paypal_order_id',
        'amount',
        'currency',
        'status',
    ];

    public function orderInformation()
    {
        return $this->belongsTo(OrderInformation::class);
    }
}
