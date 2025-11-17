<?php

echo "Testing MySQL Connection on Port 3307...\n\n";

try {
    // First connect without database
    $pdo = new PDO(
        'mysql:host=127.0.0.1;port=3307',
        'root',
        '',
        [
            PDO::ATTR_TIMEOUT => 5,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
    echo "✅ MySQL Connected on port 3307\n\n";
    
    // Check if database exists
    echo "Checking for 'capstone2' database...\n";
    $stmt = $pdo->query("SHOW DATABASES LIKE 'capstone2'");
    $result = $stmt->fetch();
    
    if ($result) {
        echo "✅ Database 'capstone2' exists\n\n";
        
        // Connect to the database
        $pdo = new PDO(
            'mysql:host=127.0.0.1;port=3307;dbname=capstone2',
            'root',
            '',
            [PDO::ATTR_TIMEOUT => 5, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        // Check tables
        echo "Checking tables...\n";
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo "Found " . count($tables) . " tables: " . implode(', ', $tables) . "\n\n";
        
        // Check products
        $count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
        echo "✅ Products table: $count products\n";
        
        echo "\n✅✅✅ DATABASE IS READY! ✅✅✅\n";
        
    } else {
        echo "❌ Database 'capstone2' NOT found\n";
        echo "👉 Run: php manual_migrate.php\n";
    }
    
} catch (Exception $e) {
    echo "❌ Connection Failed: " . $e->getMessage() . "\n";
}
