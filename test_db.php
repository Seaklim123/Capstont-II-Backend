<?php
// Simple database connection test
require_once 'vendor/autoload.php';

echo "Testing database connection...\n";

try {
    // Test MySQL connection directly
    $pdo = new PDO(
        'mysql:host=127.0.0.1;port=3307;dbname=capstone2', 
        'root', 
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "✅ Database connection successful!\n";
    
    // Test if we can run a simple query
    $stmt = $pdo->query('SELECT 1 as test');
    $result = $stmt->fetch();
    echo "✅ Query test successful: " . $result['test'] . "\n";
    
    // Check if database exists
    $stmt = $pdo->query('SELECT DATABASE() as current_db');
    $result = $stmt->fetch();
    echo "✅ Current database: " . $result['current_db'] . "\n";
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "Error details:\n";
    echo "- Host: 127.0.0.1\n";
    echo "- Port: 3307\n";
    echo "- Database: capstone2\n";
    echo "- Username: root\n";
    echo "- Password: (empty)\n";
}
?>