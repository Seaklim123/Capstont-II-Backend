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
echo "║   🔄 USE CATEGORY IMAGES FOR PRODUCTS       ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

// Map category_id to existing image files
$categoryImages = [
    1 => 'Image/Appetizers.jpg',    // Appetizers
    2 => 'Image/Main_course.jpg',   // Main Course
    3 => 'Image/Dessert.jpg',       // Desserts
    4 => 'Image/Beverages.jpg',     // Beverages
    5 => 'Image/Salads.jpg',        // Salads
];

try {
    $products = Capsule::table('products')->get();
    
    foreach ($products as $product) {
        $categoryId = $product->category_id;
        $imagePath = $categoryImages[$categoryId] ?? 'Image/Appetizers.jpg';
        
        Capsule::table('products')
            ->where('id', $product->id)
            ->update(['image' => $imagePath]);
        
        echo "✅ {$product->name} (Category $categoryId) -> $imagePath\n";
    }
    
    echo "\n" . str_repeat("═", 50) . "\n";
    echo "✅ SUCCESS! All products now use category images\n";
    echo "📸 These images actually exist in public/Image/\n";
    echo str_repeat("═", 50) . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";
?>
