<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $subcategories = Category::whereNotNull('parent_id')->get();

        for ($i = 1; $i <= 50; $i++) {
            $name = "Product {$i}";

            $product = Product::firstOrCreate(
                ['name' => $name],
                [
                    'price' => fake()->randomFloat(2, 10, 500),
                    'quantity' => fake()->numberBetween(5, 50),
                ]
            );

            $categoryIds = $subcategories->random(rand(1, 3))->pluck('id')->toArray();
            $product->categories()->syncWithoutDetaching($categoryIds);
        }
    }
}
