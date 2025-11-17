<?php

$baseUrl = 'http://127.0.0.1:8000/api';

echo "╔══════════════════════════════════════════════╗\n";
echo "║   💰 TESTING DISCOUNT API                   ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

// Test: Get products with discounts
echo "💰 GET DISCOUNTED PRODUCTS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$baseUrl/products/discounts/list");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    $data = json_decode($response, true);
    echo "✅ Status: $httpCode\n";
    echo "💰 Discounted Products: " . count($data['data']) . "\n\n";
    
    foreach ($data['data'] as $product) {
        $saved = $product['price'] - $product['final_price'];
        echo "   💰 {$product['name']}\n";
        echo "      Original Price: \${$product['price']}\n";
        echo "      Discount: {$product['discount']}% OFF\n";
        echo "      Final Price: \${$product['final_price']}\n";
        echo "      You Save: \${$saved}\n";
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
echo "   GET /api/products/discounts/list\n";
echo "   Returns only products with discount > 0\n\n";

echo "📊 CURRENT DISCOUNTED PRODUCTS:\n";
echo "   💰 Chicken Wings - 15% OFF\n";
echo "   💰 Mozzarella Sticks - 20% OFF\n";
echo "   💰 Tiramisu - 10% OFF\n";
echo "   💰 Coffee - 25% OFF\n";
echo "   💰 Caesar Salad - 15% OFF\n\n";

?>
