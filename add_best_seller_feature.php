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
echo "║   🌟 ADD BEST SELLER FEATURE                ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

try {
    // Check if is_best_seller column exists
    $columns = Capsule::select("SHOW COLUMNS FROM products LIKE 'is_best_seller'");
    
    if (empty($columns)) {
        echo "📝 Adding 'is_best_seller' column to products table...\n";
        
        Capsule::schema()->table('products', function ($table) {
            $table->boolean('is_best_seller')->default(false)->after('image');
        });
        
        echo "✅ Column added successfully!\n\n";
    } else {
        echo "✅ Column 'is_best_seller' already exists!\n\n";
    }
    
    // Mark some products as best sellers
    echo "🌟 Marking products as Best Sellers...\n\n";
    
    $bestSellers = [
        'Beef Steak',
        'Grilled Salmon',
        'Chocolate Cake',
    ];
    
    foreach ($bestSellers as $productName) {
        $updated = Capsule::table('products')
            ->where('name', $productName)
            ->update(['is_best_seller' => true]);
        
        if ($updated) {
            echo "✅ {$productName} → Best Seller\n";
        }
    }
    
    echo "\n" . str_repeat("═", 50) . "\n";
    echo "✅ SUCCESS! Best Seller feature added!\n";
    echo str_repeat("═", 50) . "\n";
    
    // Show all best sellers
    echo "\n📊 Current Best Sellers:\n";
    $bestSellerProducts = Capsule::table('products')
        ->where('is_best_seller', true)
        ->select('id', 'name', 'price')
        ->get();
    
    foreach ($bestSellerProducts as $product) {
        echo "   🌟 {$product->name} - \${$product->price}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";
?>
