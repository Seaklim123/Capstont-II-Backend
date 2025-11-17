<?php
// Simple API test
$url = 'http://127.0.0.1:8000/api/categories';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Testing API endpoint: $url\n";
echo "HTTP Status: $httpCode\n";
echo "Response:\n";
echo $response;
echo "\n";
?>