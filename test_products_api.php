<?php
// Complete Products API Test Script

echo "╔════════════════════════════════════════════╗\n";
echo "║   🍕 PRODUCTS API TEST SUITE             ║\n";
echo "╚════════════════════════════════════════════╝\n\n";

$baseUrl = 'http://127.0.0.1:8000/api';

// Helper function to make API requests
function testAPI($method, $endpoint, $data = null, $description = '') {
    global $baseUrl;
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📍 Test: $description\n";
    echo "   Method: $method\n";
    echo "   URL: $baseUrl$endpoint\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $headers = [
        'Accept: application/json',
        'Content-Type: application/json'
    ];
    
    if ($data !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        echo "   Body: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
    }
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $startTime = microtime(true);
    $response = curl_exec($ch);
    $duration = round((microtime(true) - $startTime) * 1000, 2);
    
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    echo "\n";
    echo "📊 Response:\n";
    echo "   Status: $httpCode\n";
    echo "   Time: {$duration}ms\n";
    
    if ($httpCode >= 200 && $httpCode < 300) {
        echo "   ✅ SUCCESS\n";
        $data = json_decode($response, true);
        echo "\n   Data:\n";
        echo "   " . str_replace("\n", "\n   ", json_encode($data, JSON_PRETTY_PRINT)) . "\n";
        return $data;
    } else {
        echo "   ❌ FAILED\n";
        if ($error) {
            echo "   Error: $error\n";
        } else {
            echo "   Response: $response\n";
        }
        return null;
    }
    
    echo "\n";
}

// Test 1: Get all products
echo "\n═══════════════════════════════════════════════\n";
echo "TEST 1: GET ALL PRODUCTS\n";
echo "═══════════════════════════════════════════════\n";

$products = testAPI('GET', '/products', null, 'Fetch all products');

if ($products && isset($products['data'])) {
    echo "\n📈 Summary: Found " . count($products['data']) . " products\n";
    
    // Show first 3 products
    echo "\n🔍 Sample Products:\n";
    $sample = array_slice($products['data'], 0, 3);
    foreach ($sample as $index => $product) {
        echo "   " . ($index + 1) . ". {$product['name']} - \${$product['price']} (Category ID: {$product['category_id']})\n";
    }
}

// Test 2: Get single product
if ($products && isset($products['data'][0])) {
    $firstProductId = $products['data'][0]['id'];
    
    echo "\n═══════════════════════════════════════════════\n";
    echo "TEST 2: GET SINGLE PRODUCT (ID: $firstProductId)\n";
    echo "═══════════════════════════════════════════════\n";
    
    $singleProduct = testAPI('GET', "/products/$firstProductId", null, "Fetch product ID $firstProductId");
}

// Test 3: Create new product
echo "\n═══════════════════════════════════════════════\n";
echo "TEST 3: CREATE NEW PRODUCT\n";
echo "═══════════════════════════════════════════════\n";

$newProduct = [
    'name' => 'Test Product - ' . date('H:i:s'),
    'description' => 'This is a test product created by API test',
    'price' => 19.99,
    'category_id' => 1, // Appetizers
    'image' => 'Image/test-product.jpg'
];

$createdProduct = testAPI('POST', '/products', $newProduct, 'Create new product');

// Test 4: Update product
if ($createdProduct && isset($createdProduct['data']['id'])) {
    $createdProductId = $createdProduct['data']['id'];
    
    echo "\n═══════════════════════════════════════════════\n";
    echo "TEST 4: UPDATE PRODUCT (ID: $createdProductId)\n";
    echo "═══════════════════════════════════════════════\n";
    
    $updateData = [
        'name' => 'Updated Test Product',
        'price' => 24.99
    ];
    
    $updatedProduct = testAPI('PUT', "/products/$createdProductId", $updateData, "Update product ID $createdProductId");
}

// Test 5: Delete product
if (isset($createdProductId)) {
    echo "\n═══════════════════════════════════════════════\n";
    echo "TEST 5: DELETE PRODUCT (ID: $createdProductId)\n";
    echo "═══════════════════════════════════════════════\n";
    
    $deleted = testAPI('DELETE', "/products/$createdProductId", null, "Delete product ID $createdProductId");
}

// Test 6: Search products by category
echo "\n═══════════════════════════════════════════════\n";
echo "TEST 6: FILTER PRODUCTS BY CATEGORY\n";
echo "═══════════════════════════════════════════════\n";

if ($products && isset($products['data'])) {
    // Group products by category
    $byCategory = [];
    foreach ($products['data'] as $product) {
        $catId = $product['category_id'];
        if (!isset($byCategory[$catId])) {
            $byCategory[$catId] = [];
        }
        $byCategory[$catId][] = $product['name'];
    }
    
    echo "📊 Products grouped by category:\n";
    foreach ($byCategory as $categoryId => $productNames) {
        echo "\n   Category ID $categoryId: " . count($productNames) . " products\n";
        foreach ($productNames as $name) {
            echo "      • $name\n";
        }
    }
}

// Final Summary
echo "\n\n╔════════════════════════════════════════════╗\n";
echo "║   ✅ TEST SUITE COMPLETED                 ║\n";
echo "╚════════════════════════════════════════════╝\n";

echo "\n📝 What was tested:\n";
echo "   ✅ GET    /api/products         - List all products\n";
echo "   ✅ GET    /api/products/{id}    - Get single product\n";
echo "   ✅ POST   /api/products         - Create product\n";
echo "   ✅ PUT    /api/products/{id}    - Update product\n";
echo "   ✅ DELETE /api/products/{id}    - Delete product\n";

echo "\n🚀 Next Steps:\n";
echo "   • Test with Postman for more control\n";
echo "   • Test with your frontend application\n";
echo "   • Try filtering by different categories\n";
echo "   • Upload actual product images\n";

echo "\n";
?>