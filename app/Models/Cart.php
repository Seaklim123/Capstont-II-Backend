<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    protected $fillable = [
        'note',
        'quantity',
        'product_id',
        'table_id',
    ];

    public function product(): BelongsTo{
        return $this->belongsTo(Product::class , 'product_id', 'id');
    }
    public function table(): BelongsTo{
        return $this->belongsTo(TableNumber::class , 'table_id', 'id');
    }

}
