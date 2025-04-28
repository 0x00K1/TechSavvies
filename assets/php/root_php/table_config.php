<?php
require_once __DIR__ . '/../../../includes/secure_session.php';
header('Content-Type: application/json');

if (!isset($_SESSION['root_id'])) { 
    http_response_code(401);
    exit(json_encode(['error' => 'Unauthorized']));
}

$tableConfig = [
    'roots' => [
        'displayColumns'    => ['root_id','email','username','created_at'],
        'editableColumns'   => [],            
        'deletable'         => true,
        'primaryKey'        => 'root_id',
        'searchableColumns' => ['email','username'],
    ],
    'categories' => [
        'displayColumns'    => ['category_id', 'category_name'],
        'editableColumns'   => [],
        'deletable'         => false,
        'primaryKey'        => 'category_id',
        'searchableColumns' => ['category_id', 'category_name'],
    ],
    'products' => [
        'displayColumns'    => ['product_id', 'category', 'product_name', 'picture', 'description', 'color', 'price', 'size', 'stock'],
        'editableColumns'   => ['category_id', 'product_name','picture','description','color','price','size','stock'],
        'deletable'         => true,
        'primaryKey'        => 'product_id',
        'searchableColumns' => ['product_name','description','color'],
    ],
    'customers' => [
        'displayColumns'    => ['customer_id','email','username','created_at'],
        'editableColumns'   => ['email','username'],
        'deletable'         => true,
        'primaryKey'        => 'customer_id',
        'searchableColumns' => ['email','username'],
    ],
    'orders' => [
        'displayColumns'    => ['order_id','customer_id','status','total_amount','order_date'],
        'editableColumns'   => ['status'],
        'deletable'         => true,
        'primaryKey'        => 'order_id',
        'searchableColumns' => ['order_id','customer_id','status'],
    ],
    'payments' => [
        'displayColumns'    => ['payment_id','order_id','customer_id','payment_method','payment_status','transaction_id','amount','created_at'],
        'editableColumns'   => ['payment_status'],
        'deletable'         => false,
        'primaryKey'        => 'payment_id',
        'searchableColumns' => ['payment_id','order_id','transaction_id','payment_status'],
    ],
    'reviews' => [
        'displayColumns'    => ['review_id','customer_id','product_id','rating','review_text','created_at'],
        'editableColumns'   => [],
        'deletable'         => true,
        'primaryKey'        => 'review_id',
        'searchableColumns' => ['product_id','customer_id','rating','review_text'],
    ],
];
