<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static find(int $id)
 * @method static create(OrderList $orderList)
 */
class OrderList extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $table = 'order_lists';
//    protected $fillable = [
//        'numberOrder',
//        'note',
//        'quantity',
//        'status',
//        'cart_id'
//    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }


    public function ordersinformation(): HasOne{
        return $this->hasOne(OrderInformation::class, 'ordersinformation_id', 'id');
    }
}
