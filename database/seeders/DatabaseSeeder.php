<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// database/seeds/DatabaseSeeder.php
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
            ProductDetailSeeder::class,
        ]);
    }
}
