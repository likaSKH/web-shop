<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'price',
        'quantity',
        'description',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function orders() : HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }

    public function cartProducts()
    {
        return $this->hasMany(CartProduct::class);
    }
}
