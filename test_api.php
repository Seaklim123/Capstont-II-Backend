<?php
// API Testing Script

$baseUrl = 'http://localhost:8000/api';

function makeRequest($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $headers[] = 'Content-Type: application/json';
    }
    
    if ($headers) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'status' => $httpCode,
        'body' => json_decode($response, true),
        'raw' => $response
    ];
}

echo "🧪 Testing Restaurant API Endpoints\n";
echo "====================================\n\n";

// Test 1: Get all categories
echo "1. Testing GET /api/categories\n";
$response = makeRequest($baseUrl . '/categories');
echo "Status: " . $response['status'] . "\n";
if ($response['status'] == 200) {
    echo "✅ Categories endpoint working!\n";
    echo "Found " . count($response['body']['data'] ?? []) . " categories\n";
} else {
    echo "❌ Categories endpoint failed\n";
    echo "Response: " . $response['raw'] . "\n";
}
echo "\n";

// Test 2: Get all products
echo "2. Testing GET /api/products\n";
$response = makeRequest($baseUrl . '/products');
echo "Status: " . $response['status'] . "\n";
if ($response['status'] == 200) {
    echo "✅ Products endpoint working!\n";
    echo "Found " . count($response['body']['data'] ?? []) . " products\n";
} else {
    echo "❌ Products endpoint failed\n";
    echo "Response: " . $response['raw'] . "\n";
}
echo "\n";

// Test 3: Get all tables
echo "3. Testing GET /api/tables\n";
$response = makeRequest($baseUrl . '/tables');
echo "Status: " . $response['status'] . "\n";
if ($response['status'] == 200) {
    echo "✅ Tables endpoint working!\n";
    echo "Found " . count($response['body']['data'] ?? []) . " tables\n";
} else {
    echo "❌ Tables endpoint failed\n";
    echo "Response: " . $response['raw'] . "\n";
}
echo "\n";

// Test 4: User registration
echo "4. Testing POST /api/auth/register\n";
$userData = [
    'name' => 'API Test User',
    'email' => 'apitest@example.com',
    'password' => 'testpassword',
    'password_confirmation' => 'testpassword'
];
$response = makeRequest($baseUrl . '/auth/register', 'POST', $userData);
echo "Status: " . $response['status'] . "\n";
if ($response['status'] == 201) {
    echo "✅ User registration working!\n";
    $token = $response['body']['access_token'] ?? null;
    if ($token) {
        echo "✅ Token generated: " . substr($token, 0, 20) . "...\n";
    }
} else {
    echo "❌ User registration failed\n";
    echo "Response: " . $response['raw'] . "\n";
}
echo "\n";

// Test 5: User login
echo "5. Testing POST /api/auth/login\n";
$loginData = [
    'email' => 'admin@restaurant.com',
    'password' => 'admin123'
];
$response = makeRequest($baseUrl . '/auth/login', 'POST', $loginData);
echo "Status: " . $response['status'] . "\n";
if ($response['status'] == 200) {
    echo "✅ User login working!\n";
    $token = $response['body']['access_token'] ?? null;
    if ($token) {
        echo "✅ Admin token generated: " . substr($token, 0, 20) . "...\n";
        
        // Test authenticated endpoint
        echo "\n6. Testing authenticated endpoint /api/user\n";
        $authResponse = makeRequest($baseUrl . '/user', 'GET', null, ['Authorization: Bearer ' . $token]);
        echo "Status: " . $authResponse['status'] . "\n";
        if ($authResponse['status'] == 200) {
            echo "✅ Authenticated endpoint working!\n";
            echo "User: " . ($authResponse['body']['name'] ?? 'Unknown') . "\n";
        } else {
            echo "❌ Authenticated endpoint failed\n";
        }
    }
} else {
    echo "❌ User login failed\n";
    echo "Response: " . $response['raw'] . "\n";
}

echo "\n🎉 API Testing Complete!\n";
?>