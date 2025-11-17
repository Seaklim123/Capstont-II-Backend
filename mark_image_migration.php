<?php
// Mark the image migration as completed since we already added it manually
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

echo "Marking image migration as completed...\n";

try {
    $migration = '2025_10_29_071519_add_image_to_categories_table';
    
    // Check if migration already exists
    $exists = Capsule::table('migrations')
        ->where('migration', $migration)
        ->exists();
        
    if (!$exists) {
        Capsule::table('migrations')->insert([
            'migration' => $migration,
            'batch' => 2
        ]);
        echo "✅ Marked $migration as completed\n";
    } else {
        echo "ℹ️ $migration already marked as completed\n";
    }

    echo "\n🎉 Image migration marked as completed!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>