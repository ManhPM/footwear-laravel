<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
    ];

    public function products()
    {
        return $this->hasMany(CartProduct::class, 'cart_id');
    }

    public function getBy($userId)
    {
        return $this->whereUserId($userId)->first();
    }

    public function getCart($userId)
    {
        $cart = $this->getBy($userId);

        if (!$cart) {
            $cart = $this->cart->create(['user_id' => $userId]);
        }

        return $cart;
    }

    public function getTotalPriceAttribute()
    {
        return $this->products->reduce(function ($carry, $item) {
            $item->load('product');
            $price = $item->product_quantity * ($item->product->sale ? $item->product->sale_price : $item->product->price);
            return $carry + $price;
        }, 0);
    }
}
