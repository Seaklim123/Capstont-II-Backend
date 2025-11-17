<?php
echo "Quick MySQL Check...\n";

// Fast connection test with short timeout
$context = stream_context_create([
    'socket' => ['timeout' => 2]
]);

// Try to connect to port 3307
$socket = @fsockopen('127.0.0.1', 3307, $errno, $errstr, 2);
if ($socket) {
    echo "✅ Port 3307 is OPEN - MySQL is running\n";
    fclose($socket);
} else {
    echo "❌ Port 3307 is CLOSED - MySQL is NOT running\n";
    echo "   Error: $errstr\n\n";
    
    // Check common MySQL ports
    echo "Checking other ports...\n";
    foreach ([3306, 3308] as $port) {
        $s = @fsockopen('127.0.0.1', $port, $e, $es, 1);
        if ($s) {
            echo "   ✅ Found MySQL on port $port\n";
            fclose($s);
            echo "\n   🔧 FIX: Update .env to use port $port\n";
            break;
        }
    }
}

echo "\n⚠️  START XAMPP MySQL first, then run:\n";
echo "   php artisan serve\n";
?>
