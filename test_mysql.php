<?php
echo "Testing MySQL connection...\n";
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3307;dbname=capstone2', 'root', '');
    echo "✅ MySQL Connected on port 3307\n";
} catch (Exception $e) {
    echo "❌ MySQL Error: " . $e->getMessage() . "\n";
    echo "⚠️  Start XAMPP MySQL first!\n";
}
?>
