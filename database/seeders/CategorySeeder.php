<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categories;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
                ["name" => "Work"],
                ["name" => "Personal"],
                ["name" => "Shopping"],
                ["name" => "Study"],
            ];

            foreach ($categories as $category) {
                Categories::create($category);
            }
    }
}
