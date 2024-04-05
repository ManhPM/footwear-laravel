<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['name' => 'Giày', 'parent_id' => null],
            ['name' => 'Dép', 'parent_id' => null],
            ['name' => 'Giày thể thao', 'parent_id' => '1'],
            ['name' => 'Giày đế bằng', 'parent_id' => '1'],
            ['name' => 'Giày cao gót', 'parent_id' => '1'],
            ['name' => 'Giày chạy bộ', 'parent_id' => '1'],
            ['name' => 'Dép xỏ ngón', 'parent_id' => '2'],
            ['name' => 'Dép lê', 'parent_id' => '2'],
            ['name' => 'Dép quai hậu', 'parent_id' => '2'],
        ];

        foreach ($items as $item) {
            Category::updateOrCreate($item);
        }
    }
}
