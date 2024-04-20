<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'user_id',
        'status',
        'note',
        'ship',
        'total',
    ];

    public function products()
    {
        return $this->hasMany(ProductOrder::class, 'order_id');
    }
}
