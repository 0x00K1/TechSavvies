<?php
/**
 *  Called from:
 *      – categories/index.php          (catalogue view)
 *      – categories/products/index.php (single-product view)
 */

 require_once __DIR__ . '/secure_session.php';
 require_once __DIR__ . '/db.php';

/*-----------------------------------------------------------
| 1. Normalise incoming GET parameters
|-----------------------------------------------------------*/
$type           = $_GET['type']        ?? null;
$search_raw     = $_GET['search']      ?? '';
$product_id_raw = $_GET['product_id']  ?? null;

$search_term    = '%' . $search_raw . '%';
$product_id     = $product_id_raw !== null ? (int)$product_id_raw : null;

/* Map pretty URLs to category_id */
$type_to_category_id = [
    't-shirts'       => 1,
    'backpacks'      => 2,
    'books'          => 3,
    'laptops'        => 4,
    'stickers'       => 5,
    'hardware-tools' => 6,
    'software-tools' => 7,
    'mugs'           => 8,
    'phone-cases'    => 9,
    'games'          => 10,
];

/*-----------------------------------------------------------
| 2. Single–product request?
|-----------------------------------------------------------*/
if ($product_id !== null) {
    /* Grab the one product we were asked for */
    $stmt = $pdo->prepare(
        'SELECT p.*, c.category_name
           FROM products p
           LEFT JOIN categories c USING (category_id)
          WHERE p.product_id = :pid'
    );
    $stmt->bindValue(':pid', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        /* Map DB column names -> keys the UI expects */
        $product = [
            'product_id'  => $row['product_id'],
            'name'        => $row['product_name'],
            'image'       => $row['picture'],
            'description' => $row['description'],
            'price'       => $row['price'],
            'stock'       => $row['stock'],
            'category'    => $row['category_name'],
            /* retain any extra fields: */
            'color'       => $row['color'],
            'size'        => $row['size'],
            'category_id' => $row['category_id'],
        ];
    
        /* Keep both interfaces happy */
        $products              = [$product['product_id'] => $product];
        $products_to_display   = [$product];   // not really used on this page
        $total_pages           = 1;
        $page                  = 1;
    } else {
        $products            = [];
        $products_to_display = [];
    }
    return;
}

/*-----------------------------------------------------------
| 3. Catalogue / search view with optional pagination
|-----------------------------------------------------------*/
$category_id = $type ? ($type_to_category_id[$type] ?? 1) : 1;

/* ---------- pagination ---------- */
$items_per_page = 8;
$page           = isset($_GET['page']) && ctype_digit($_GET['page'])
                ? (int)$_GET['page'] : 1;
$offset         = ($page - 1) * $items_per_page;

/* ---------- main product query ---------- */
$sql = 'SELECT p.*, c.category_name
          FROM products p
          LEFT JOIN categories c USING (category_id)
         WHERE p.category_id = :cid
           AND p.product_name LIKE :search
           AND p.stock > 0
      ORDER BY p.product_id
         LIMIT :lim OFFSET :off';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':cid',    $category_id,   PDO::PARAM_INT);
$stmt->bindValue(':search', $search_term,   PDO::PARAM_STR);
$stmt->bindValue(':lim',    $items_per_page,PDO::PARAM_INT);
$stmt->bindValue(':off',    $offset,        PDO::PARAM_INT);
$stmt->execute();

$products_to_display = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ---------- total count for pagination ---------- */
$count_stmt = $pdo->prepare(
    'SELECT COUNT(*)
       FROM products
      WHERE category_id = :cid
        AND product_name LIKE :search
        AND stock > 0'
);
$count_stmt->bindValue(':cid',    $category_id, PDO::PARAM_INT);
$count_stmt->bindValue(':search', $search_term, PDO::PARAM_STR);
$count_stmt->execute();
$total_items  = $count_stmt->fetchColumn();
$total_pages  = (int)ceil($total_items / $items_per_page);

/* ---------- full map keyed by ID ---------- */
$products = [];
foreach ($products_to_display as $row) {
    $products[$row['product_id']] = $row;
}
?>
