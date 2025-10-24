<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = [
        'note',
        'quantity',
        'product_id',
        'table_id',
        'status'
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function tableNumber()
    {
        return $this->belongsTo(TableNumber::class, 'table_id');
    }

}
