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
echo "║   🌐 SET PLACEHOLDER IMAGES FOR PRODUCTS    ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

// Using picsum.photos for different placeholder images
$productImages = [
    1 => 'https://picsum.photos/seed/springrolls/400/300',
    2 => 'https://picsum.photos/seed/chickenwings/400/300',
    3 => 'https://picsum.photos/seed/mozzarella/400/300',
    4 => 'https://picsum.photos/seed/salmon/400/300',
    5 => 'https://picsum.photos/seed/steak/400/300',
    6 => 'https://picsum.photos/seed/alfredo/400/300',
    7 => 'https://picsum.photos/seed/curry/400/300',
    8 => 'https://picsum.photos/seed/cake/400/300',
    9 => 'https://picsum.photos/seed/tiramisu/400/300',
    10 => 'https://picsum.photos/seed/cheesecake/400/300',
    11 => 'https://picsum.photos/seed/juice/400/300',
    12 => 'https://picsum.photos/seed/coffee/400/300',
    13 => 'https://picsum.photos/seed/tea/400/300',
    14 => 'https://picsum.photos/seed/caesar/400/300',
    15 => 'https://picsum.photos/seed/greek/400/300',
];

try {
    $updated = 0;
    
    foreach ($productImages as $id => $imageUrl) {
        $product = Capsule::table('products')->where('id', $id)->first();
        
        if ($product) {
            Capsule::table('products')
                ->where('id', $id)
                ->update(['image' => $imageUrl]);
            
            echo "✅ Updated: {$product->name}\n   → $imageUrl\n\n";
            $updated++;
        }
    }
    
    echo str_repeat("═", 50) . "\n";
    echo "✅ SUCCESS! Updated $updated products with unique placeholders\n";
    echo "🌐 Each product now has a different image\n";
    echo "⚠️  Note: These are temporary placeholder images\n";
    echo "📝 Replace with real food images later!\n";
    echo str_repeat("═", 50) . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";
?>
