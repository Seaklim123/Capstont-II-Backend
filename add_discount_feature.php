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
echo "║   💰 ADD DISCOUNT FEATURE                   ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

try {
    // Check if discount column exists
    $columns = Capsule::select("SHOW COLUMNS FROM products LIKE 'discount'");
    
    if (empty($columns)) {
        echo "📝 Adding 'discount' column to products table...\n";
        
        Capsule::schema()->table('products', function ($table) {
            $table->decimal('discount', 5, 2)->default(0)->after('price');
        });
        
        echo "✅ Column added successfully!\n\n";
    } else {
        echo "✅ Column 'discount' already exists!\n\n";
    }
    
    // Add discounts to some products
    echo "💰 Adding discounts to products...\n\n";
    
    $discounts = [
        'Chicken Wings' => 15.00,      // 15% off
        'Mozzarella Sticks' => 20.00,  // 20% off
        'Tiramisu' => 10.00,           // 10% off
        'Coffee' => 25.00,             // 25% off
        'Caesar Salad' => 15.00,       // 15% off
    ];
    
    foreach ($discounts as $productName => $discountPercent) {
        $updated = Capsule::table('products')
            ->where('name', $productName)
            ->update(['discount' => $discountPercent]);
        
        if ($updated) {
            $product = Capsule::table('products')->where('name', $productName)->first();
            $originalPrice = $product->price;
            $discountAmount = $originalPrice * ($discountPercent / 100);
            $finalPrice = $originalPrice - $discountAmount;
            
            echo "✅ {$productName}\n";
            echo "   Original: \${$originalPrice}\n";
            echo "   Discount: {$discountPercent}% off\n";
            echo "   Final Price: \${$finalPrice}\n\n";
        }
    }
    
    echo str_repeat("═", 50) . "\n";
    echo "✅ SUCCESS! Discount feature added!\n";
    echo str_repeat("═", 50) . "\n";
    
    // Show all products with discounts
    echo "\n💰 Current Discounted Products:\n";
    $discountedProducts = Capsule::table('products')
        ->where('discount', '>', 0)
        ->select('id', 'name', 'price', 'discount')
        ->get();
    
    foreach ($discountedProducts as $product) {
        $finalPrice = $product->price * (1 - $product->discount / 100);
        echo "   💰 {$product->name} - \${$product->price} → \${$finalPrice} ({$product->discount}% off)\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";
?>
