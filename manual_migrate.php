<?php
// Direct migration runner to bypass Laravel bootstrap cache issues
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

echo "Starting manual migrations...\n";

try {
    // Drop tables if they exist (for refresh)
    $tables = [
        'order_inoformations',
        'order_lists', 
        'carts',
        'products',
        'table_numbers',
        'categories',
        'personal_access_tokens',
        'jobs',
        'cache',
        'users'
    ];
    
    foreach ($tables as $table) {
        if ($schema->hasTable($table)) {
            $schema->drop($table);
            echo "✅ Dropped table: $table\n";
        }
    }

    // Create users table
    $schema->create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->rememberToken();
        $table->timestamps();
    });
    echo "✅ Created users table\n";

    // Create cache table
    $schema->create('cache', function (Blueprint $table) {
        $table->string('key')->primary();
        $table->mediumText('value');
        $table->integer('expiration');
    });
    echo "✅ Created cache table\n";

    // Create jobs table
    $schema->create('jobs', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('queue')->index();
        $table->longText('payload');
        $table->unsignedTinyInteger('attempts');
        $table->unsignedInteger('reserved_at')->nullable();
        $table->unsignedInteger('available_at');
        $table->unsignedInteger('created_at');
    });
    echo "✅ Created jobs table\n";

    // Create categories table
    $schema->create('categories', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });
    echo "✅ Created categories table\n";

    // Create table_numbers table
    $schema->create('table_numbers', function (Blueprint $table) {
        $table->id();
        $table->integer('table_number')->unique();
        $table->boolean('is_available')->default(true);
        $table->timestamps();
    });
    echo "✅ Created table_numbers table\n";

    // Create products table
    $schema->create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->decimal('price', 8, 2);
        $table->unsignedBigInteger('category_id');
        $table->string('image')->nullable();
        $table->timestamps();
        
        $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
    });
    echo "✅ Created products table\n";

    // Create carts table
    $schema->create('carts', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('product_id');
        $table->unsignedBigInteger('table_number_id');
        $table->integer('quantity');
        $table->timestamps();
        
        $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        $table->foreign('table_number_id')->references('id')->on('table_numbers')->onDelete('cascade');
    });
    echo "✅ Created carts table\n";

    // Create order_lists table
    $schema->create('order_lists', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('product_id');
        $table->integer('quantity');
        $table->decimal('price', 8, 2);
        $table->timestamps();
        
        $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
    });
    echo "✅ Created order_lists table\n";

    // Create order_inoformations table (note: keeping original name from migration)
    $schema->create('order_inoformations', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('order_list_id');
        $table->unsignedBigInteger('table_number_id');
        $table->decimal('total_amount', 10, 2);
        $table->enum('status', ['pending', 'preparing', 'ready', 'served'])->default('pending');
        $table->timestamps();
        
        $table->foreign('order_list_id')->references('id')->on('order_lists')->onDelete('cascade');
        $table->foreign('table_number_id')->references('id')->on('table_numbers')->onDelete('cascade');
    });
    echo "✅ Created order_inoformations table\n";

    // Create personal_access_tokens table
    $schema->create('personal_access_tokens', function (Blueprint $table) {
        $table->id();
        $table->morphs('tokenable');
        $table->string('name');
        $table->string('token', 64)->unique();
        $table->text('abilities')->nullable();
        $table->timestamp('last_used_at')->nullable();
        $table->timestamp('expires_at')->nullable();
        $table->timestamps();
    });
    echo "✅ Created personal_access_tokens table\n";

    echo "\n🎉 All migrations completed successfully!\n";
    echo "Your database is now ready to use.\n";

} catch (Exception $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
}
?>