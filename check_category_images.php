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
echo "║   📸 CHECK CATEGORY IMAGES                  ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

try {
    $categories = Capsule::table('categories')
        ->select('id', 'name', 'image')
        ->orderBy('id')
        ->get();
    
    echo "📊 CURRENT CATEGORIES:\n\n";
    
    foreach ($categories as $cat) {
        $status = $cat->image ? '✅' : '❌';
        $imageInfo = $cat->image ? $cat->image : 'NO IMAGE';
        
        echo "$status ID: {$cat->id} - {$cat->name}\n";
        echo "   Image: $imageInfo\n\n";
    }
    
    echo str_repeat("═", 50) . "\n";
    echo "📝 Categories in your frontend:\n";
    echo "   1. All\n";
    echo "   2. Appetizers\n";
    echo "   3. Main Course\n";
    echo "   4. Desserts\n";
    echo "   5. Beverages\n";
    echo "   6. Salads\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";
?>
