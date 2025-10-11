<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TableNumber extends Model
{
    use HasFactory;
    protected $table = 'table_numbers';
    protected $fillable = [
        'number',
        'status',
    ];

    public function cart(): HasMany{
        return $this->hasMany(Cart::class, 'table_number_id', 'id');
    }
}
