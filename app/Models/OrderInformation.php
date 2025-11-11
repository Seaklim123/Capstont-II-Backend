<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderInformation extends Model
{
    use HasFactory;
    protected $table = 'order_informations';
    protected $fillable = [
        'numberOrder',
        'totalPrice',
        'discount',
        'status',
        'payment',
        'note',
        'refund',
        'phone_number',
        'user_id'
    ];

    public function orderLists()
    {
        return $this->hasMany(OrderList::class, 'numberOrder', 'numberOrder');
    }
    public function users()
    {
        return $this->hasOne(User::class);
    }
    public function payment()
    {
        return $this->hasOne(\App\Models\Payment::class);
    }

}
