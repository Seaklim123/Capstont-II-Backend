<?php

echo "╔══════════════════════════════════════════════╗\n";
echo "║   🧪 QUICK API TEST                         ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

$baseUrl = 'http://127.0.0.1:8000/api';

echo "Testing endpoints:\n\n";

// Test 1: Health check
echo "1️⃣ Health Check: $baseUrl/health\n";
$ch = curl_init($baseUrl . '/health');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "   ✅ Status: $httpCode - Server is running!\n\n";
} else {
    echo "   ❌ Status: $httpCode - Server not responding\n\n";
    echo "   ⚠️  Make sure Laravel server is running:\n";
    echo "      php artisan serve\n\n";
    exit;
}

// Test 2: Categories
echo "2️⃣ Categories: $baseUrl/categories\n";
$ch = curl_init($baseUrl . '/categories');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    $data = json_decode($response, true);
    echo "   ✅ Status: $httpCode - Found " . count($data['data']) . " categories\n\n";
} else {
    echo "   ❌ Status: $httpCode\n\n";
}

// Test 3: Best Sellers
echo "3️⃣ Best Sellers: $baseUrl/products/best-sellers/list\n";
$ch = curl_init($baseUrl . '/products/best-sellers/list');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    $data = json_decode($response, true);
    echo "   ✅ Status: $httpCode - Found " . count($data['data']) . " best sellers\n";
    foreach ($data['data'] as $product) {
        echo "      🌟 {$product['name']} - \${$product['price']}\n";
    }
    echo "\n";
} else {
    echo "   ❌ Status: $httpCode\n";
    echo "   Response: $response\n\n";
}

// Test 4: Discounts
echo "4️⃣ Discounts: $baseUrl/products/discounts/list\n";
$ch = curl_init($baseUrl . '/products/discounts/list');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    $data = json_decode($response, true);
    echo "   ✅ Status: $httpCode - Found " . count($data['data']) . " discounted products\n\n";
} else {
    echo "   ❌ Status: $httpCode\n\n";
}

echo "╔══════════════════════════════════════════════╗\n";
echo "║   ✅ TESTS COMPLETE                         ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

echo "📝 Frontend should use these URLs:\n";
echo "   Categories: http://127.0.0.1:8000/api/categories\n";
echo "   All Products: http://127.0.0.1:8000/api/products\n";
echo "   Best Sellers: http://127.0.0.1:8000/api/products/best-sellers/list\n";
echo "   Discounts: http://127.0.0.1:8000/api/products/discounts/list\n\n";

?>
