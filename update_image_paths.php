<?php
// Update categories with correct image paths from public folder
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

echo "Updating categories with correct image paths from public folder...\n";

try {
    // Map category names to your actual image files
    $categoryImages = [
        'Appetizers' => 'Image/Appetizers.jpg',
        'Main Course' => 'Image/Main_course.jpg', 
        'Desserts' => 'Image/Dessert.jpg',
        'Beverages' => 'Image/Beverages.jpg',
        'Salads' => 'Image/Salads.jpg'
    ];
    
    foreach ($categoryImages as $name => $imagePath) {
        $updated = Capsule::table('categories')
            ->where('name', $name)
            ->update(['image' => $imagePath]);
            
        if ($updated) {
            echo "✅ Updated '$name' with image: $imagePath\n";
        } else {
            echo "⚠️ Category '$name' not found\n";
        }
    }
    
    echo "\nVerifying updated data:\n";
    $categories = Capsule::table('categories')->get();
    foreach ($categories as $category) {
        echo "- {$category->name}: {$category->image}\n";
    }
    
    echo "\n🎉 Categories updated with correct image paths!\n";
    echo "Images are now pointing to public/Image/ folder\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>