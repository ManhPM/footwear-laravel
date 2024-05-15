<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CategorySeeder::class,
            RoleDatabaseSeeder::class,
            UserDatabaseSeeder::class,
            CouponSeeder::class,
            ProductSeeder::class,
            CartProductSeeder::class,
            PaymentMethodSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
