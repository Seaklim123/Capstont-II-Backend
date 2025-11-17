<?php
// Quick test to check if backend is accessible
echo "Testing Backend Connection\n";
echo "==========================\n\n";

$endpoints = [
    'http://127.0.0.1:8000/api/categories',
    'http://localhost:8000/api/categories',
];

foreach ($endpoints as $url) {
    echo "Testing: $url\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($httpCode == 200) {
        echo "  ✅ Status: $httpCode - WORKING!\n";
        $data = json_decode($response, true);
        if (isset($data['data'])) {
            echo "  ✅ Found " . count($data['data']) . " categories\n";
        }
    } else {
        echo "  ❌ Status: $httpCode - FAILED\n";
        if ($error) {
            echo "  Error: $error\n";
        }
    }
    echo "\n";
}

// Check CORS headers
echo "Checking CORS Configuration:\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/api/categories');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Origin: http://localhost:3000',
    'Accept: application/json'
]);

$response = curl_exec($ch);
curl_close($ch);

if (strpos($response, 'Access-Control-Allow-Origin') !== false) {
    echo "✅ CORS headers are present\n";
} else {
    echo "❌ CORS headers are missing - this might cause frontend connection issues!\n";
}

echo "\n📝 Frontend should connect to: http://127.0.0.1:8000 or http://localhost:8000\n";
?>