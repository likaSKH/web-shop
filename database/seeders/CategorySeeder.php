<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Electronics' => ['Phones', 'Laptops', 'TVs'],
            'Clothing' => ['Men', 'Women', 'Kids'],
            'Books' => ['Fiction', 'Non-fiction', 'Comics'],
            'Home & Kitchen' => ['Furniture', 'Appliances', 'Decor'],
        ];

        foreach ($categories as $parentName => $subcategories) {
            $parent = Category::firstOrCreate(['name' => $parentName, 'parent_id' => null]);

            foreach ($subcategories as $childName) {
                Category::firstOrCreate([
                    'name' => $childName,
                    'parent_id' => $parent->id,
                ]);
            }
        }
    }
}
