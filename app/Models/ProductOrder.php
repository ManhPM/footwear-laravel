<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_size',
        'product_quantity',
        'product_price',
        'product_id',
        'order_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
