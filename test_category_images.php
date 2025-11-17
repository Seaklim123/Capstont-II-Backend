<?php

$baseUrl = 'http://127.0.0.1:8000/api';

echo "╔══════════════════════════════════════════════╗\n";
echo "║   📸 TESTING CATEGORY IMAGES API            ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

// Test: Get all categories
echo "📂 TEST: GET ALL CATEGORIES WITH IMAGES\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "URL: $baseUrl/categories\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$baseUrl/categories");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($httpCode == 200) {
    $data = json_decode($response, true);
    echo "✅ Status: $httpCode\n";
    echo "📊 Total Categories: " . count($data['data']) . "\n\n";
    
    echo str_repeat("═", 50) . "\n";
    echo "CATEGORIES WITH IMAGES:\n";
    echo str_repeat("═", 50) . "\n\n";
    
    foreach ($data['data'] as $category) {
        $hasImage = !empty($category['image']) ? '✅' : '❌';
        
        echo "$hasImage {$category['name']} (ID: {$category['id']})\n";
        
        if (!empty($category['image'])) {
            echo "   📸 Image URL: {$category['image']}\n";
            
            // Check if image URL is accessible
            $imageCheck = @get_headers($category['image']);
            if ($imageCheck && strpos($imageCheck[0], '200')) {
                echo "   ✅ Image is accessible!\n";
            } else {
                echo "   ⚠️  Image URL returns error (file might not exist)\n";
            }
        } else {
            echo "   ❌ NO IMAGE!\n";
        }
        
        echo "\n";
    }
    
    echo str_repeat("═", 50) . "\n";
    
    // Summary
    $withImages = 0;
    $withoutImages = 0;
    foreach ($data['data'] as $cat) {
        if (!empty($cat['image'])) {
            $withImages++;
        } else {
            $withoutImages++;
        }
    }
    
    echo "\n📊 SUMMARY:\n";
    echo "   Total Categories: " . count($data['data']) . "\n";
    echo "   ✅ With Images: $withImages\n";
    echo "   ❌ Without Images: $withoutImages\n";
    
    if ($withoutImages == 0) {
        echo "\n🎉 SUCCESS! All categories have images!\n";
    } else {
        echo "\n⚠️  WARNING: Some categories missing images!\n";
    }
    
} else {
    echo "❌ FAILED: Status $httpCode\n";
    if ($error) {
        echo "Error: $error\n";
    } else {
        echo "Response: $response\n";
    }
}

echo "\n╔══════════════════════════════════════════════╗\n";
echo "║   ✅ TESTING COMPLETE                       ║\n";
echo "╚══════════════════════════════════════════════╝\n";

echo "\n📝 USAGE IN FRONTEND:\n";
echo "   1. Fetch: GET http://127.0.0.1:8000/api/categories\n";
echo "   2. Each category has 'image' field with full URL\n";
echo "   3. Use: <img src={category.image} alt={category.name} />\n\n";

?>
