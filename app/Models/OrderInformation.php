<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderInformation extends Model
{
    use HasFactory;
    protected $table = 'order_information';
    protected $fillable = [
        'numberOrder',
        'totalPrice',
        'discount',
        'status',
        'payment'
    ];

    public function orderlist(): BelongsTo{
        return $this->belongsTo(OrderList::class, 'orderlist_id', 'id');
    }
}
