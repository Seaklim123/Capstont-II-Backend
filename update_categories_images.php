<?php
// Check categories table structure and add sample image data
require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

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

echo "Checking categories table structure...\n";

try {
    // Check table structure
    $columns = Capsule::select('DESCRIBE categories');
    echo "Categories table columns:\n";
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type}) " . ($column->Null === 'YES' ? 'NULL' : 'NOT NULL') . "\n";
    }
    
    echo "\nCurrent categories data:\n";
    $categories = Capsule::table('categories')->get();
    foreach ($categories as $category) {
        echo "- ID: {$category->id}, Name: {$category->name}, Image: " . ($category->image ?? 'NULL') . "\n";
    }
    
    // Add sample images to categories
    echo "\nAdding sample image paths to categories...\n";
    
    $categoryImages = [
        'Appetizers' => 'images/categories/appetizers.jpg',
        'Main Course' => 'images/categories/main-course.jpg', 
        'Desserts' => 'images/categories/desserts.jpg',
        'Beverages' => 'images/categories/beverages.jpg',
        'Salads' => 'images/categories/salads.jpg'
    ];
    
    foreach ($categoryImages as $name => $imagePath) {
        Capsule::table('categories')
            ->where('name', $name)
            ->update(['image' => $imagePath]);
        echo "✅ Updated $name with image: $imagePath\n";
    }
    
    echo "\n🎉 Categories table updated with image fields!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>