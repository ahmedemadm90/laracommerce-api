<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'demo@laracommerce.test'],
            ['name' => 'Demo Customer', 'password' => Hash::make('password')]
        );

        foreach ([
            ['name' => 'Developer Tools', 'description' => 'Tools for productive engineering teams.'],
            ['name' => 'Desk Essentials', 'description' => 'Everyday equipment for a focused workspace.'],
            ['name' => 'Learning', 'description' => 'Resources for continuous professional growth.'],
        ] as $categoryData) {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($categoryData['name'])],
                $categoryData
            );

            $products = match ($category->slug) {
                'developer-tools' => [
                    ['name' => 'Mechanical Keyboard', 'price' => 89.00, 'stock' => 25, 'sku' => 'DEV-KEY-001'],
                    ['name' => 'USB-C Docking Station', 'price' => 129.00, 'stock' => 18, 'sku' => 'DEV-DOCK-001'],
                ],
                'desk-essentials' => [
                    ['name' => 'Ergonomic Desk Lamp', 'price' => 49.00, 'stock' => 40, 'sku' => 'DESK-LAMP-001'],
                    ['name' => 'Laptop Stand', 'price' => 39.00, 'stock' => 30, 'sku' => 'DESK-STAND-001'],
                ],
                default => [
                    ['name' => 'Laravel API Patterns', 'price' => 24.00, 'stock' => 100, 'sku' => 'LEARN-LAR-001'],
                    ['name' => 'Flutter in Practice', 'price' => 29.00, 'stock' => 100, 'sku' => 'LEARN-FLU-001'],
                ],
            };

            foreach ($products as $productData) {
                Product::updateOrCreate(
                    ['sku' => $productData['sku']],
                    array_merge($productData, [
                        'category_id' => $category->id,
                        'slug' => Str::slug($productData['name']),
                        'description' => "A carefully selected {$productData['name']} for the LaraCommerce demo catalog.",
                        'is_active' => true,
                    ])
                );
            }
        }
    }
}
