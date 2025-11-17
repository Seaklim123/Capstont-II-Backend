<?php
echo "╔══════════════════════════════════════════════╗\n";
echo "║   🔍 BACKEND DIAGNOSIS                      ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

// 1. Check MySQL Connection
echo "1️⃣ Testing MySQL Connection (Port 3307)...\n";
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3307', 'root', '', [
        PDO::ATTR_TIMEOUT => 5,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "   ✅ MySQL is running on port 3307\n";
    
    // Check if database exists
    $stmt = $pdo->query("SHOW DATABASES LIKE 'capstone2'");
    if ($stmt->rowCount() > 0) {
        echo "   ✅ Database 'capstone2' exists\n";
    } else {
        echo "   ❌ Database 'capstone2' NOT found\n";
    }
} catch (PDOException $e) {
    echo "   ❌ MySQL Error: " . $e->getMessage() . "\n";
    echo "   🔧 Fix: Start XAMPP MySQL on port 3307\n";
}

echo "\n";

// 2. Check Laravel server
echo "2️⃣ Testing Laravel Server (Port 8000)...\n";
$ch = curl_init('http://127.0.0.1:8000/api/health');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 3);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($httpCode == 200) {
    echo "   ✅ Laravel server is running\n";
} else {
    echo "   ❌ Laravel server not responding\n";
    if ($error) {
        echo "   Error: $error\n";
    }
    echo "   🔧 Fix: Run 'php artisan serve' in a separate terminal\n";
}

echo "\n";

// 3. Summary
echo "╔══════════════════════════════════════════════╗\n";
echo "║   📋 SUMMARY                                ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

echo "To fix 'Loading...' issue:\n\n";
echo "1. ✅ Make sure XAMPP MySQL is running on port 3307\n";
echo "2. ✅ Run: php artisan serve\n";
echo "3. ✅ Keep the server running\n";
echo "4. ✅ Refresh your frontend\n\n";

echo "API Endpoints:\n";
echo "   - Categories: http://127.0.0.1:8000/api/categories\n";
echo "   - Products: http://127.0.0.1:8000/api/products\n";
echo "   - Best Sellers: http://127.0.0.1:8000/api/products/best-sellers/list\n";
echo "   - Discounts: http://127.0.0.1:8000/api/products/discounts/list\n\n";

?>
