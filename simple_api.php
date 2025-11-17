<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Simple API server to test database operations
require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Setup database connection
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'port'      => 3307,
    'database'  => 'capstone2',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

$request_uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

try {
    // Parse the route
    $path = parse_url($request_uri, PHP_URL_PATH);
    $segments = explode('/', trim($path, '/'));
    
    if ($segments[0] === 'api') {
        array_shift($segments); // Remove 'api'
        
        $resource = $segments[0] ?? '';
        
        switch ($resource) {
            case 'categories':
                if ($method === 'GET') {
                    $categories = Capsule::table('categories')->get();
                    echo json_encode([
                        'success' => true,
                        'data' => $categories,
                        'message' => 'Categories retrieved successfully'
                    ]);
                }
                break;
                
            case 'products':
                if ($method === 'GET') {
                    $products = Capsule::table('products')
                        ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                        ->select('products.*', 'categories.name as category_name')
                        ->get();
                    echo json_encode([
                        'success' => true,
                        'data' => $products,
                        'message' => 'Products retrieved successfully'
                    ]);
                }
                break;
                
            case 'tables':
                if ($method === 'GET') {
                    $tables = Capsule::table('table_numbers')->get();
                    echo json_encode([
                        'success' => true,
                        'data' => $tables,
                        'message' => 'Tables retrieved successfully'
                    ]);
                }
                break;
                
            case 'test':
                echo json_encode([
                    'success' => true,
                    'message' => 'API is working!',
                    'timestamp' => date('Y-m-d H:i:s'),
                    'database_connection' => 'OK'
                ]);
                break;
                
            default:
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Endpoint not found',
                    'available_endpoints' => [
                        'GET /api/categories',
                        'GET /api/products', 
                        'GET /api/tables',
                        'GET /api/test'
                    ]
                ]);
        }
    } else {
        echo json_encode([
            'success' => true,
            'message' => 'Restaurant API v1.0',
            'endpoints' => [
                'GET /api/categories' => 'Get all categories',
                'GET /api/products' => 'Get all products',
                'GET /api/tables' => 'Get all tables',
                'GET /api/test' => 'Test endpoint'
            ]
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
?>