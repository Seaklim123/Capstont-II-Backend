<?php
// Mark existing tables as migrated to avoid conflicts
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

echo "Marking existing migrations as completed...\n";

try {
    // List of migrations to mark as completed
    $migrations = [
        '0001_01_01_000000_create_users_table',
        '0001_01_01_000001_create_cache_table', 
        '0001_01_01_000002_create_jobs_table',
        '2025_10_11_143641_create_categories_table',
        '2025_10_11_143838_create_table_numbers_table',
        '2025_10_11_144022_create_products_table',
        '2025_10_11_144424_create_carts_table',
        '2025_10_11_144522_create_order_lists_table',
        '2025_10_11_144618_create_order_inoformations_table',
        '2025_10_13_090034_create_personal_access_tokens_table'
    ];
    
    $batch = 1;
    
    foreach ($migrations as $migration) {
        // Check if migration already exists
        $exists = Capsule::table('migrations')
            ->where('migration', $migration)
            ->exists();
            
        if (!$exists) {
            Capsule::table('migrations')->insert([
                'migration' => $migration,
                'batch' => $batch
            ]);
            echo "✅ Marked $migration as completed\n";
        } else {
            echo "ℹ️ $migration already marked as completed\n";
        }
    }

    echo "\n🎉 All existing migrations marked as completed!\n";
    echo "You can now run 'php artisan migrate' safely for new migrations.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>