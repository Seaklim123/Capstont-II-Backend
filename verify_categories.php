<?php
// Direct database check to verify image data
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

echo "Direct Database Check - Categories with Images\n";
echo "=============================================\n";

try {
    $categories = Capsule::table('categories')->get();
    
    foreach ($categories as $category) {
        echo "ID: {$category->id}\n";
        echo "Name: {$category->name}\n";
        echo "Image: " . ($category->image ?? 'NULL') . "\n";
        echo "Created: {$category->created_at}\n";
        echo "Updated: {$category->updated_at}\n";
        echo "-------------------\n";
    }
    
    echo "\nTotal categories: " . count($categories) . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>