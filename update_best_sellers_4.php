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
echo "║   🌟 UPDATE BEST SELLERS (4 products)      ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

try {
    // Show current best sellers
    echo "📊 CURRENT BEST SELLERS:\n";
    $current = Capsule::table('products')
        ->where('is_best_seller', true)
        ->select('name')
        ->get();
    
    foreach ($current as $p) {
        echo "   🌟 {$p->name}\n";
    }
    echo "\n";
    
    // Add Chicken Alfredo as 4th best seller
    echo "➕ Adding 4th Best Seller...\n";
    
    $updated = Capsule::table('products')
        ->where('name', 'Chicken Alfredo')
        ->update(['is_best_seller' => true]);
    
    if ($updated) {
        echo "✅ Chicken Alfredo → Best Seller\n\n";
    }
    
    echo str_repeat("═", 50) . "\n";
    echo "✅ SUCCESS! Now have 4 Best Sellers\n";
    echo str_repeat("═", 50) . "\n";
    
    // Show updated best sellers
    echo "\n🌟 UPDATED BEST SELLERS (4 products):\n";
    $bestSellers = Capsule::table('products')
        ->where('is_best_seller', true)
        ->select('id', 'name', 'price')
        ->orderBy('id')
        ->get();
    
    foreach ($bestSellers as $product) {
        echo "   🌟 {$product->name} - \${$product->price}\n";
    }
    
    echo "\n📊 Total Best Sellers: " . count($bestSellers) . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";
?>
