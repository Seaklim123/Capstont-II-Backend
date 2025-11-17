<?php

$baseUrl = 'http://127.0.0.1:8000/api';

echo "╔══════════════════════════════════════════════╗\n";
echo "║   🌟 TESTING BEST SELLERS API               ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

// Test 1: Get all products
echo "📋 TEST 1: GET ALL PRODUCTS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$baseUrl/products");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    $data = json_decode($response, true);
    echo "✅ Status: $httpCode\n";
    echo "📊 Total Products: " . count($data['data']) . "\n\n";
    
    // Show first 3 with best seller status
    foreach (array_slice($data['data'], 0, 5) as $product) {
        $badge = $product['is_best_seller'] ? '🌟 BEST SELLER' : '  ';
        echo "   $badge {$product['name']} - \${$product['price']}\n";
    }
} else {
    echo "❌ Failed: Status $httpCode\n";
}

echo "\n";

// Test 2: Get only best sellers
echo "🌟 TEST 2: GET BEST SELLERS ONLY\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$baseUrl/products/best-sellers/list");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    $data = json_decode($response, true);
    echo "✅ Status: $httpCode\n";
    echo "🌟 Best Sellers: " . count($data['data']) . "\n\n";
    
    foreach ($data['data'] as $product) {
        echo "   🌟 {$product['name']} - \${$product['price']}\n";
        echo "      {$product['description']}\n";
        echo "      Image: {$product['image']}\n\n";
    }
} else {
    echo "❌ Failed: Status $httpCode\n";
    echo "Response: $response\n";
}

echo "\n╔══════════════════════════════════════════════╗\n";
echo "║   ✅ TESTING COMPLETE                       ║\n";
echo "╚══════════════════════════════════════════════╝\n";

echo "\n📝 NEW API ENDPOINT:\n";
echo "   GET /api/products/best-sellers/list\n";
echo "   Returns only products marked as best sellers\n\n";

?>
