<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\TableNumber;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@restaurant.com',
            'password' => Hash::make('admin123'),
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Appetizers'],
            ['name' => 'Main Course'],
            ['name' => 'Desserts'],
            ['name' => 'Beverages'],
            ['name' => 'Salads'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create Products
        $products = [
            // Appetizers
            ['name' => 'Spring Rolls', 'description' => 'Fresh vegetables wrapped in rice paper', 'price' => 8.99, 'category_id' => 1],
            ['name' => 'Chicken Wings', 'description' => 'Spicy buffalo wings with ranch dip', 'price' => 12.99, 'category_id' => 1],
            ['name' => 'Mozzarella Sticks', 'description' => 'Golden fried mozzarella with marinara sauce', 'price' => 9.99, 'category_id' => 1],
            
            // Main Course
            ['name' => 'Grilled Salmon', 'description' => 'Fresh Atlantic salmon with lemon butter', 'price' => 24.99, 'category_id' => 2],
            ['name' => 'Beef Steak', 'description' => 'Prime ribeye steak cooked to perfection', 'price' => 32.99, 'category_id' => 2],
            ['name' => 'Chicken Alfredo', 'description' => 'Creamy pasta with grilled chicken', 'price' => 18.99, 'category_id' => 2],
            ['name' => 'Vegetable Curry', 'description' => 'Mixed vegetables in coconut curry sauce', 'price' => 16.99, 'category_id' => 2],
            ['name' => 'Pasta', 'description' => 'Creamy tomato penne pasta', 'price' => 9.99, 'category_id' => 2],

            
            // Desserts
            ['name' => 'Chocolate Cake', 'description' => 'Rich chocolate cake with vanilla ice cream', 'price' => 7.99, 'category_id' => 3],
            ['name' => 'Tiramisu', 'description' => 'Classic Italian coffee-flavored dessert', 'price' => 8.99, 'category_id' => 3],
            ['name' => 'Cheesecake', 'description' => 'New York style cheesecake with berry sauce', 'price' => 6.99, 'category_id' => 3],
            
            // Beverages
            ['name' => 'Fresh Orange Juice', 'description' => 'Freshly squeezed orange juice', 'price' => 4.99, 'category_id' => 4],
            ['name' => 'Coffee', 'description' => 'Premium roasted coffee', 'price' => 3.99, 'category_id' => 4],
            ['name' => 'Iced Tea', 'description' => 'Refreshing iced tea with lemon', 'price' => 2.99, 'category_id' => 4],
            
            // Salads
            ['name' => 'Caesar Salad', 'description' => 'Classic Caesar with croutons and parmesan', 'price' => 11.99, 'category_id' => 5],
            ['name' => 'Greek Salad', 'description' => 'Fresh vegetables with feta cheese and olives', 'price' => 10.99, 'category_id' => 5],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Create Table Numbers (1-20)
        for ($i = 1; $i <= 20; $i++) {
            TableNumber::create([
                'table_number' => $i,
                'is_available' => true,
            ]);
        }

        echo "Database seeded successfully!\n";
        echo "- Created 2 users (test@example.com and admin@restaurant.com)\n";
        echo "- Created 5 categories\n";
        echo "- Created 15 products\n";
        echo "- Created 20 table numbers\n";
    }
}
