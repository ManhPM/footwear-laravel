<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $carts = [
            ['id' => '1', 'user_id' => '1'],
        ];

        $cart_products = [
            ['product_size' => '40', 'product_quantity' => '5', 'product_price' => '500000', 'product_id' => '1', 'cart_id' => '1'],
            ['product_size' => '40', 'product_quantity' => '2', 'product_price' => '1000000', 'product_id' => '5', 'cart_id' => '1'],
            ['product_size' => '40', 'product_quantity' => '5', 'product_price' => '1000000', 'product_id' => '9', 'cart_id' => '1'],
        ];

        foreach ($carts as $item) {
            Cart::updateOrCreate($item);
        }

        foreach ($cart_products as $item) {
            CartProduct::updateOrCreate($item);
        }
    }
}
