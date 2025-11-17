<?php
// Add image field to categories table
require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

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

$schema = $capsule->schema();

echo "Adding image field to categories table...\n";

try {
    // Check if image column already exists
    $columns = Capsule::select("SHOW COLUMNS FROM categories LIKE 'image'");
    
    if (empty($columns)) {
        // Add image column to categories table
        $schema->table('categories', function (Blueprint $table) {
            $table->string('image')->nullable()->after('name');
        });
        echo "✅ Added image field to categories table\n";
    } else {
        echo "ℹ️ Image field already exists in categories table\n";
    }

    // Also check if products table has image field
    $productColumns = Capsule::select("SHOW COLUMNS FROM products LIKE 'image'");
    
    if (empty($productColumns)) {
        // Add image column to products table if it doesn't exist
        $schema->table('products', function (Blueprint $table) {
            $table->string('image')->nullable()->after('category_id');
        });
        echo "✅ Added image field to products table\n";
    } else {
        echo "ℹ️ Image field already exists in products table\n";
    }

    echo "\n🎉 Migration completed successfully!\n";
    echo "Categories and Products tables now have image fields.\n";

} catch (Exception $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
}
?>