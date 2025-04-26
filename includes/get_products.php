<?php
require_once __DIR__ . '/db.php';

$type_to_category_id = [
    't-shirts' => 1,      // T-shirts
    'backpacks' => 2,     // Backpacks
    'books' => 3,         // Books
    'laptops' => 4,       // Laptops
    'stickers' => 5,      // Stickers
    'hardware-tools' => 6,// Hardware Tools
    'software-tools' => 7,// Software Tools
    'mugs' => 8,          // Mugs
    'phone-cases' => 9,   // Phone Cases
    'games' => 10         // Games
];

// Check if the 'type' parameter is set.
$type = $_GET['type'] ?? 't-shirt'; // Default to 't-shirt' if no type is specified
$search_term = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';

// Get the corresponding category_id, default is 1 't-shirt'.
$category_id = $type_to_category_id[$type] ?? 1;

// Pagination logic
$items_per_page = 6; // Number of cards per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page, default is 1
$offset = ($page - 1) * $items_per_page; // Calculate the offset

// Query to fetch products with LIMIT and OFFSET
$query = 'SELECT p.*, c.category_name
          FROM products p
          LEFT JOIN categories c ON p.category_id = c.category_id
          WHERE p.category_id = :category_id AND p.product_name LIKE :search AND p.stock > 0
          ORDER BY p.product_id
          LIMIT :limit OFFSET :offset';

$stmt = $pdo->prepare($query);
$stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
$stmt->bindValue(':search', $search_term, PDO::PARAM_STR);
$stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

if ($stmt->execute()) {
    $products_to_display = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $products_to_display = []; // Default to an empty array if the query fails
}

// Get the total number of products for pagination
$count_query = 'SELECT COUNT(*) 
                FROM products p
                WHERE p.category_id = :category_id AND p.product_name LIKE :search AND p.stock > 0';
$count_stmt = $pdo->prepare($count_query);
$count_stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
$count_stmt->bindValue(':search', $search_term, PDO::PARAM_STR);
$count_stmt->execute();
$total_items = $count_stmt->fetchColumn();

$total_pages = ceil($total_items / $items_per_page); // Total number of pages
?>