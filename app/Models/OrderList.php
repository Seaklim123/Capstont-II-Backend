<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderList extends Model
{
    use HasFactory;
    protected $table = 'order_list';
    protected $fillable = [
        'numberOrder',
        'note',
        'quantity',
        'status',
        'card_id'
    ];

    public function cart(): HasMany{
        return $this->hasMany(Cart::class, 'table_number_id', 'id');
    }

    public function ordersinformation(): HasOne{
        return $this->hasOne(OrderInformation::class, 'ordersinformation_id', 'id');
    }
}
