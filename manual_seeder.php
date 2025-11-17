<?php
// Manual seeder runner to bypass Laravel bootstrap issues
require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\TableNumber;

// Setup database connection
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'port'      => 3307,
    'database'  => 'capstone2',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "Starting database seeding...\n";

try {
    // Create admin user
    $user = Capsule::table('users')->insert([
        'name' => 'Admin',
        'email' => 'admin@restaurant.com',
        'password' => password_hash('admin123', PASSWORD_DEFAULT),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);
    echo "✅ Created admin user (admin@restaurant.com / admin123)\n";

    // Create test user
    Capsule::table('users')->insert([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => password_hash('password', PASSWORD_DEFAULT),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);
    echo "✅ Created test user (test@example.com / password)\n";

    // Create Categories
    $categories = [
        ['name' => 'Appetizers', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ['name' => 'Main Course', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ['name' => 'Desserts', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ['name' => 'Beverages', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ['name' => 'Salads', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ];

    foreach ($categories as $category) {
        Capsule::table('categories')->insert($category);
    }
    echo "✅ Created 5 categories\n";

    // Create Products
    $products = [
        // Appetizers (category_id = 1)
        ['name' => 'Spring Rolls', 'description' => 'Fresh vegetables wrapped in rice paper', 'price' => 8.99, 'category_id' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ['name' => 'Chicken Wings', 'description' => 'Spicy buffalo wings with ranch dip', 'price' => 12.99, 'category_id' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ['name' => 'Mozzarella Sticks', 'description' => 'Golden fried mozzarella with marinara sauce', 'price' => 9.99, 'category_id' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        
        // Main Course (category_id = 2)
        ['name' => 'Grilled Salmon', 'description' => 'Fresh Atlantic salmon with lemon butter', 'price' => 24.99, 'category_id' => 2, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ['name' => 'Beef Steak', 'description' => 'Prime ribeye steak cooked to perfection', 'price' => 32.99, 'category_id' => 2, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ['name' => 'Chicken Alfredo', 'description' => 'Creamy pasta with grilled chicken', 'price' => 18.99, 'category_id' => 2, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ['name' => 'Vegetable Curry', 'description' => 'Mixed vegetables in coconut curry sauce', 'price' => 16.99, 'category_id' => 2, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        
        // Desserts (category_id = 3)
        ['name' => 'Chocolate Cake', 'description' => 'Rich chocolate cake with vanilla ice cream', 'price' => 7.99, 'category_id' => 3, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ['name' => 'Tiramisu', 'description' => 'Classic Italian coffee-flavored dessert', 'price' => 8.99, 'category_id' => 3, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ['name' => 'Cheesecake', 'description' => 'New York style cheesecake with berry sauce', 'price' => 6.99, 'category_id' => 3, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        
        // Beverages (category_id = 4)
        ['name' => 'Fresh Orange Juice', 'description' => 'Freshly squeezed orange juice', 'price' => 4.99, 'category_id' => 4, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ['name' => 'Coffee', 'description' => 'Premium roasted coffee', 'price' => 3.99, 'category_id' => 4, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ['name' => 'Iced Tea', 'description' => 'Refreshing iced tea with lemon', 'price' => 2.99, 'category_id' => 4, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        
        // Salads (category_id = 5)
        ['name' => 'Caesar Salad', 'description' => 'Classic Caesar with croutons and parmesan', 'price' => 11.99, 'category_id' => 5, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ['name' => 'Greek Salad', 'description' => 'Fresh vegetables with feta cheese and olives', 'price' => 10.99, 'category_id' => 5, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
    ];

    foreach ($products as $product) {
        Capsule::table('products')->insert($product);
    }
    echo "✅ Created 15 products\n";

    // Create Table Numbers (1-20)
    for ($i = 1; $i <= 20; $i++) {
        Capsule::table('table_numbers')->insert([
            'table_number' => $i,
            'is_available' => true,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
    echo "✅ Created 20 table numbers\n";

    echo "\n🎉 Database seeded successfully!\n";
    echo "\nLogin credentials:\n";
    echo "Admin: admin@restaurant.com / admin123\n";
    echo "Test User: test@example.com / password\n";

} catch (Exception $e) {
    echo "❌ Seeding failed: " . $e->getMessage() . "\n";
}
?>