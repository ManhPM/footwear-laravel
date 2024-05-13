<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\ProductOrder;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['customer_name' => 'Khách mua tại cửa hàng', 'customer_email' => 'khachmuataicuahang@gmail.com', 'customer_phone' => '0111111111', 'customer_address' => 'Cửa hàng', 'total' => '1500000'],
            ['customer_name' => 'Khách mua tại cửa hàng', 'customer_email' => 'khachmuataicuahang@gmail.com', 'customer_phone' => '0111111111', 'customer_address' => 'Cửa hàng', 'total' => '500000'],
            ['customer_name' => 'Khách mua tại cửa hàng', 'customer_email' => 'khachmuataicuahang@gmail.com', 'customer_phone' => '0111111111', 'customer_address' => 'Cửa hàng', 'total' => '1000000'],
            ['customer_name' => 'Khách mua tại cửa hàng', 'customer_email' => 'khachmuataicuahang@gmail.com', 'customer_phone' => '0111111111', 'customer_address' => 'Cửa hàng', 'total' => '3000000'],
        ];

        foreach ($items as $item) {
            Order::updateOrCreate($item);
        }

        $order_products = [
            ['product_size' => '40', 'product_quantity' => '1', 'product_price' => '500000', 'order_id' => '1', 'product_id' => '1'],
            ['product_size' => '40', 'product_quantity' => '1', 'product_price' => '1000000', 'order_id' => '1', 'product_id' => '3'],
            ['product_size' => '40', 'product_quantity' => '1', 'product_price' => '500000', 'order_id' => '2', 'product_id' => '1'],
            ['product_size' => '40', 'product_quantity' => '1', 'product_price' => '1000000', 'order_id' => '3', 'product_id' => '3'],
            ['product_size' => '40', 'product_quantity' => '2', 'product_price' => '500000', 'order_id' => '4', 'product_id' => '1'],
            ['product_size' => '40', 'product_quantity' => '2', 'product_price' => '1000000', 'order_id' => '4', 'product_id' => '3'],
        ];

        foreach ($order_products as $item) {
            ProductOrder::updateOrCreate($item);
        }
    }
}
