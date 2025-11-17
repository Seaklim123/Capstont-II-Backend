<?php

echo "=== Testing Backend & Database Connection ===\n\n";

// Test 1: MySQL Connection
echo "1️⃣ Testing MySQL (Port 3307)...\n";
try {
    $pdo = new PDO(
        'mysql:host=127.0.0.1;port=3307;dbname=capstone2',
        'root',
        '',
        [PDO::ATTR_TIMEOUT => 3]
    );
    echo "   ✅ MySQL Connected\n";
    
    // Quick query test
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "   ✅ Database Query Works - Found " . $result['count'] . " products\n\n";
} catch (Exception $e) {
    echo "   ❌ MySQL Failed: " . $e->getMessage() . "\n\n";
}

// Test 2: Laravel Server
echo "2️⃣ Testing Laravel Server (Port 8000)...\n";
$ch = curl_init('http://127.0.0.1:8000/api/health');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 3);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    echo "   ✅ Laravel Server Running\n";
    echo "   Response: " . $response . "\n\n";
} else {
    echo "   ❌ Laravel Server Not Running (Start with: php artisan serve)\n\n";
}

// Test 3: API Endpoints
if ($httpCode === 200) {
    echo "3️⃣ Testing API Endpoints...\n";
    
    $endpoints = [
        'Categories' => 'http://127.0.0.1:8000/api/categories',
        'Products' => 'http://127.0.0.1:8000/api/products',
        'Best Sellers' => 'http://127.0.0.1:8000/api/products/best-sellers/list',
        'Discounts' => 'http://127.0.0.1:8000/api/products/discounts/list'
    ];
    
    foreach ($endpoints as $name => $url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $data = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($code === 200) {
            $json = json_decode($data, true);
            $count = isset($json['data']) ? count($json['data']) : 0;
            echo "   ✅ $name - $count items\n";
        } else {
            echo "   ❌ $name - Failed\n";
        }
    }
}

echo "\n=== Test Complete ===\n";
