<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Products extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'name',
         'image_path',  
        'price',
        'discount',
        'description',
        'status',
        'category_id',
    ];

    /**
     * Get the full image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return null;
    }

    /**
     * Add image_url to JSON output
     */
    protected $appends = ['image_url'];

    public function category(): BelongsTo{
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function cart (): HasMany{
        return $this->hasMany(Cart::class, 'cart_id', 'id');
    }
}
