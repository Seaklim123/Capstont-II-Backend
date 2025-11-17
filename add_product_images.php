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
echo "║   📸 ADDING IMAGES TO PRODUCTS              ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

// Product images mapping
$productImages = [
    1 => 'Image/spring-rolls.jpg',
    2 => 'Image/chicken-wings.jpg',
    3 => 'Image/mozzarella-sticks.jpg',
    4 => 'Image/grilled-salmon.jpg',
    5 => 'Image/beef-steak.jpg',
    6 => 'Image/chicken-alfredo.jpg',
    7 => 'Image/vegetable-curry.jpg',
    8 => 'Image/chocolate-cake.jpg',
    9 => 'Image/tiramisu.jpg',
    10 => 'Image/cheesecake.jpg',
    11 => 'Image/orange-juice.jpg',
    12 => 'Image/coffee.jpg',
    13 => 'Image/iced-tea.jpg',
    14 => 'Image/caesar-salad.jpg',
    15 => 'Image/greek-salad.jpg',
];

try {
    $updated = 0;
    
    foreach ($productImages as $id => $imagePath) {
        $product = Capsule::table('products')->where('id', $id)->first();
        
        if ($product) {
            Capsule::table('products')
                ->where('id', $id)
                ->update(['image' => $imagePath]);
            
            echo "✅ Updated: {$product->name} -> $imagePath\n";
            $updated++;
        }
    }
    
    echo "\n" . str_repeat("═", 50) . "\n";
    echo "✅ SUCCESS! Updated $updated products with images\n";
    echo str_repeat("═", 50) . "\n";
    
    // Verify
    echo "\n📊 VERIFICATION:\n";
    $products = Capsule::table('products')->select('id', 'name', 'image')->orderBy('id')->get();
    
    $hasImage = 0;
    $noImage = 0;
    
    foreach ($products as $product) {
        if ($product->image) {
            $hasImage++;
            echo "✅ ID {$product->id}: {$product->name}\n";
        } else {
            $noImage++;
            echo "❌ ID {$product->id}: {$product->name} - STILL NULL\n";
        }
    }
    
    echo "\n📈 SUMMARY:\n";
    echo "   Total Products: " . count($products) . "\n";
    echo "   ✅ With Images: $hasImage\n";
    echo "   ❌ Without Images: $noImage\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";
?>
