<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['name' => 'Phạm Minh Mạnh', 'email' => 'phammanhbeo2001@gmail.com', 'phone' => '0961592551', 'address' => 'Địa chỉ', 'gender' => 'Nam', 'email_verified_at' => '2024-01-01', 'password' => '$2a$10$pVN6f.l9WXqsQxifG89kTOewLKmN6BxXjFoqIUra5MIBcc6Z8yhtW', 'remember_token' => 'null'],
        ];

        foreach ($items as $item) {
            User::updateOrCreate($item);
        }

        // $cartProduct = [
        //     ['product_size' => '44', 'product_quantity' => '5', 'product_price' => '500000', 'product_id' => '41', 'cart_id' => '1'],
        //     ['product_size' => '44', 'product_quantity' => '2', 'product_price' => '800000', 'product_id' => '40', 'cart_id' => '1'],
        //     ['product_size' => '44', 'product_quantity' => '5', 'product_price' => '700000', 'product_id' => '33', 'cart_id' => '1'],
        // ];

        // foreach ($cartProduct as $item) {
        //     CartProduct::updateOrCreate($item);
        // }
    }
}
