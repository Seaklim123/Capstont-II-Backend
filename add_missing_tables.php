<?php
// Add missing sessions table
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

echo "Adding missing sessions table...\n";

try {
    // Create sessions table
    if (!$schema->hasTable('sessions')) {
        $schema->create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
        echo "✅ Created sessions table\n";
    } else {
        echo "ℹ️ Sessions table already exists\n";
    }

    // Also add cache_locks table for Laravel 11+
    if (!$schema->hasTable('cache_locks')) {
        $schema->create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });
        echo "✅ Created cache_locks table\n";
    }

    // Add failed_jobs table
    if (!$schema->hasTable('failed_jobs')) {
        $schema->create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
        echo "✅ Created failed_jobs table\n";
    }

    echo "\n🎉 All missing tables added successfully!\n";
    echo "Your Laravel application should now work without session errors.\n";

} catch (Exception $e) {
    echo "❌ Error adding tables: " . $e->getMessage() . "\n";
}
?>