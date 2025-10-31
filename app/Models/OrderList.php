<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderList extends Model
{
    use HasFactory;
    protected $table = 'order_lists';
    protected $fillable = [
        'numberOrder',
        'status',
        'cart_id'
    ];

   public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
    public function orderInformation()
    {
        return $this->belongsTo(OrderInformation::class, 'numberOrder', 'numberOrder');
    }
}
