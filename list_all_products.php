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
echo "║   📋 ALL PRODUCTS IN DATABASE               ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

try {
    $products = Capsule::table('products')
        ->select('id', 'name', 'price', 'category_id', 'image')
        ->orderBy('id')
        ->get();
    
    foreach ($products as $product) {
        $imageStatus = $product->image ? '✅' : '❌';
        echo sprintf("%s ID: %-3d %-30s $%.2f (Cat: %d)\n", 
            $imageStatus,
            $product->id, 
            $product->name,
            $product->price,
            $product->category_id
        );
    }
    
    echo "\n" . str_repeat("═", 50) . "\n";
    echo "📊 Total Products: " . count($products) . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";
?>
