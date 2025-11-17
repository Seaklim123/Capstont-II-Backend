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
echo "║   🍝 UPDATE PASTA IMAGE                     ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

try {
    // Find Pasta product
    $pasta = Capsule::table('products')->where('name', 'Pasta')->first();
    
    if ($pasta) {
        echo "📋 Found Pasta product:\n";
        echo "   ID: {$pasta->id}\n";
        echo "   Current Image: {$pasta->image}\n\n";
        
        // Update image to pasta.jpg
        Capsule::table('products')
            ->where('id', $pasta->id)
            ->update([
                'image' => 'Image/pasta.jpg',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        
        echo "✅ SUCCESS! Updated Pasta image:\n";
        echo "   Old: {$pasta->image}\n";
        echo "   New: Image/pasta.jpg\n";
        
        echo "\n⚠️  REMINDER: Make sure you have this file:\n";
        echo "   C:\\Users\\DELL\\OneDrive\\Desktop\\Capstont-II-Backend\\public\\Image\\pasta.jpg\n";
        
    } else {
        echo "❌ Pasta product not found in database!\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";
?>
