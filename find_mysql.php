<?php

echo "🔍 Scanning for MySQL on common ports...\n\n";

$ports = [3306, 3307, 3308];

foreach ($ports as $port) {
    echo "Checking port $port... ";
    
    $connection = @fsockopen('127.0.0.1', $port, $errno, $errstr, 2);
    
    if ($connection) {
        fclose($connection);
        echo "✅ OPEN\n";
        
        // Try to connect to database
        try {
            $pdo = new PDO(
                "mysql:host=127.0.0.1;port=$port;dbname=capstone2",
                'root',
                '',
                [PDO::ATTR_TIMEOUT => 2]
            );
            echo "   ✅ Database 'capstone2' accessible on port $port\n";
            echo "   👉 UPDATE YOUR .env FILE: DB_PORT=$port\n\n";
            exit(0);
        } catch (Exception $e) {
            echo "   ⚠️  Port open but database not accessible: " . $e->getMessage() . "\n\n";
        }
    } else {
        echo "❌ CLOSED\n";
    }
}

echo "\n❌ MySQL not found on any port (3306, 3307, 3308)\n";
echo "👉 START XAMPP MySQL SERVICE FIRST\n";
