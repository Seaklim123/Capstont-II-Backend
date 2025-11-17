<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'port' => '3307',
    'database' => 'capstone2',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "╔══════════════════════════════════════════════╗\n";
echo "║   🍝 ADDING PASTA PRODUCT                   ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

try {
    // Check if Pasta already exists
    $existing = Capsule::table('products')->where('name', 'Pasta')->first();
    
    if ($existing) {
        echo "⚠️  Pasta already exists in database!\n";
        echo "   ID: {$existing->id}\n";
        echo "   Name: {$existing->name}\n";
        echo "   Price: \${$existing->price}\n";
    } else {
        // Insert new Pasta product
        $id = Capsule::table('products')->insertGetId([
            'name' => 'Pasta',
            'description' => 'Creamy tomato penne pasta',
            'price' => 9.99,
            'category_id' => 2, // Main Course
            'image' => 'Image/Main_course.jpg', // Using category image
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
        echo "✅ SUCCESS! Added new product:\n";
        echo "   ID: $id\n";
        echo "   Name: Pasta\n";
        echo "   Price: $9.99\n";
        echo "   Category: Main Course (ID: 2)\n";
        echo "   Image: Image/pasta.jpg\n";
    }
    
    echo "\n" . str_repeat("═", 50) . "\n";
    
    // Show all products
    $total = Capsule::table('products')->count();
    echo "📊 Total Products Now: $total\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";
?>
