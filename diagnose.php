<?php
// Quick diagnostic - check if everything is working
echo "=== BACKEND DIAGNOSTIC ===\n\n";

// 1. Check if we can load Laravel
try {
    require_once 'vendor/autoload.php';
    echo "✅ Laravel autoloader working\n";
} catch (Exception $e) {
    echo "❌ Laravel autoloader failed: " . $e->getMessage() . "\n";
    exit;
}

// 2. Check database connection
use Illuminate\Database\Capsule\Manager as Capsule;

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

$start = microtime(true);
try {
    $pdo = $capsule->getConnection()->getPdo();
    $time = round((microtime(true) - $start) * 1000, 2);
    echo "✅ Database connection working ({$time}ms)\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "\n🔧 Fix: Make sure XAMPP MySQL is running on port 3307\n";
    exit;
}

// 3. Check if categories table exists and has data
$start = microtime(true);
try {
    $count = Capsule::table('categories')->count();
    $time = round((microtime(true) - $start) * 1000, 2);
    echo "✅ Categories table accessible ({$time}ms) - Found {$count} categories\n";
    
    if ($time > 1000) {
        echo "⚠️ WARNING: Query is slow (>{$time}ms). Check MySQL performance.\n";
    }
} catch (Exception $e) {
    echo "❌ Categories query failed: " . $e->getMessage() . "\n";
}

// 4. Check if images exist
echo "\n=== IMAGE FILES ===\n";
$imageDir = 'public/Image';
if (is_dir($imageDir)) {
    $images = scandir($imageDir);
    $imageFiles = array_filter($images, function($file) {
        return !in_array($file, ['.', '..']);
    });
    echo "✅ Image directory exists: " . count($imageFiles) . " files\n";
    foreach ($imageFiles as $img) {
        echo "  - $img\n";
    }
} else {
    echo "❌ Image directory not found: $imageDir\n";
}

// 5. Test API endpoint
echo "\n=== API TEST ===\n";
echo "Testing: http://127.0.0.1:8000/api/categories\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/api/health');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);

$start = microtime(true);
$response = curl_exec($ch);
$time = round((microtime(true) - $start) * 1000, 2);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ API responding ({$time}ms)\n";
    echo "Response: $response\n";
} else {
    echo "❌ API not responding\n";
    echo "🔧 Fix: Make sure to run: php artisan serve\n";
}

echo "\n=== SUMMARY ===\n";
echo "Backend URL: http://127.0.0.1:8000\n";
echo "API Base URL: http://127.0.0.1:8000/api\n";
echo "Health Check: http://127.0.0.1:8000/api/health\n";
echo "Categories API: http://127.0.0.1:8000/api/categories\n";
echo "\nFrontend should use: http://127.0.0.1:8000/api as base URL\n";
?>