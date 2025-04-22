<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public static function boot()
    {
        parent::boot();

        static::saving(function ($order) {
            $order->total_price = $order->quantity * $order->product->price;
        });
    }
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'name',
        'email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
