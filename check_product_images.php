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
echo "║   📸 PRODUCT IMAGES CHECK                   ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

try {
    $products = Capsule::table('products')
        ->select('id', 'name', 'image')
        ->orderBy('id')
        ->get();
    
    $hasImage = 0;
    $noImage = 0;
    
    foreach ($products as $product) {
        $status = $product->image ? '✅' : '❌';
        $imageInfo = $product->image ? $product->image : 'NO IMAGE';
        
        if ($product->image) {
            $hasImage++;
        } else {
            $noImage++;
        }
        
        echo sprintf("%-3s ID: %-3d %-30s -> %s\n", 
            $status, 
            $product->id, 
            substr($product->name, 0, 30),
            $imageInfo
        );
    }
    
    echo "\n" . str_repeat("─", 50) . "\n";
    echo "📊 SUMMARY:\n";
    echo "   Total Products: " . count($products) . "\n";
    echo "   ✅ With Images: $hasImage\n";
    echo "   ❌ Without Images: $noImage\n";
    
    if ($noImage > 0) {
        echo "\n⚠️  Some products are missing images!\n";
        echo "   You need to add image field to products table.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";
?>
