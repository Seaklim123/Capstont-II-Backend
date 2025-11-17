<?php
// Test categories API to check if image field is returned
$url = 'http://127.0.0.1:8000/api/categories';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Testing Categories API with Image Field\n";
echo "======================================\n";
echo "URL: $url\n";
echo "HTTP Status: $httpCode\n\n";

if ($httpCode == 200) {
    $data = json_decode($response, true);
    
    if (isset($data['data']) && is_array($data['data'])) {
        echo "✅ API Response successful!\n";
        echo "Found " . count($data['data']) . " categories:\n\n";
        
        foreach ($data['data'] as $index => $category) {
            echo "Category " . ($index + 1) . ":\n";
            echo "  - ID: " . ($category['id'] ?? 'N/A') . "\n";
            echo "  - Name: " . ($category['name'] ?? 'N/A') . "\n";
            echo "  - Image: " . ($category['image'] ?? 'NULL') . "\n";
            echo "  - Created: " . ($category['created_at'] ?? 'N/A') . "\n";
            echo "\n";
        }
        
        // Check if image field exists
        $hasImage = isset($data['data'][0]['image']);
        if ($hasImage) {
            echo "✅ Image field is present in the response!\n";
        } else {
            echo "❌ Image field is missing from the response\n";
            echo "Available fields: " . implode(', ', array_keys($data['data'][0] ?? [])) . "\n";
        }
        
    } else {
        echo "❌ Unexpected response format\n";
        echo "Response: $response\n";
    }
} else {
    echo "❌ API request failed\n";
    echo "Response: $response\n";
}
?>