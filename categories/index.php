<?php
// Simulated product data array
$products = [
    // T-shirts category products
    [
        'category'    => 'tshirts',
        'image'       => '../assets/images/Products/Brand/T-shirt/Front-1.png',
        'name'        => 'White T-shirt with Logo',
        'description' => 'Made of pure cotton 100%, YOU SHOULD NOT THINK THIS IS REAL!',
        'rating'      => 3.5,
        'price'       => 100
    ],
    [
        'category'    => 'tshirts',
        'image'       => '../assets/images/Products/Brand/T-shirt/Front.png',
        'name'        => 'Black T-shirt with Logo',
        'description' => 'Made of pure cotton 100%, YOU SHOULD NOT THINK THIS IS REAL!',
        'rating'      => 5,
        'price'       => 100
    ],
    [
        'category'    => 'tshirts',
        'image'       => '../assets/images/Products/Non-Brand/T-shirts/mockup-7f7164bd_720x.png',
        'name'        => 'I need a &lt;br/&gt; T-shirt',
        'description' => 'Made of pure cotton 100%, YOU SHOULD NOT THINK THIS IS REAL!',
        'rating'      => 4.2,
        'price'       => 75
    ],
    [
        'category'    => 'tshirts',
        'image'       => '../assets/images/Products/Non-Brand/T-shirts/il_794xN.5281097748_2ufp.png',
        'name'        => 'I are Programmer T-shirt',
        'description' => 'Made of pure cotton 100%, YOU SHOULD NOT THINK THIS IS REAL!',
        'rating'      => 2.75,
        'price'       => 75
    ],
    [
        'category'    => 'tshirts',
        'image'       => '../assets/images/Products/Non-Brand/T-shirts/unisex-staple-t-shirt-black-front-66eaef158d4ea_720x.png',
        'name'        => 'Choose Your Weapon T-shirt',
        'description' => 'Made of pure cotton 100%, YOU SHOULD NOT THINK THIS IS REAL!',
        'rating'      => 4.0,
        'price'       => 75
    ],
    [
        'category'    => 'tshirts',
        'image'       => '../assets/images/Products/Non-Brand/T-shirts/il_794xN.5785303495_59t7.png',
        'name'        => 'CTRL + PURR T-shirt',
        'description' => 'Made of pure cotton 100%, YOU SHOULD NOT THINK THIS IS REAL!',
        'rating'      => 4.5,
        'price'       => 75
    ],
    [
        'category'    => 'tshirts',
        'image'       => '../assets/images/Products/Non-Brand/T-shirts/il_794xN.4294904508_t966.png',
        'name'        => 'Just Put a ticket in T-shirt Dark Gray',
        'description' => 'Made of pure cotton 100%, YOU SHOULD NOT THINK THIS IS REAL!',
        'rating'      => 4.1,
        'price'       => 75
    ],
    [
        'category'    => 'tshirts',
        'image'       => '../assets/images/Products/Non-Brand/T-shirts/il_794xN.4342291955_pirs.png',
        'name'        => 'Just Put a ticket in T-shirt Light Gray',
        'description' => 'Made of pure cotton 100%, YOU SHOULD NOT THINK THIS IS REAL!',
        'rating'      => 3.9,
        'price'       => 75
    ],
    [
        'category'    => 'tshirts',
        'image'       => '../assets/images/Products/Non-Brand/T-shirts/il_794xN.5394430315_qarj.png',
        'name'        => 'Call me tech wizard T-shirt',
        'description' => 'Made of pure cotton 100%, YOU SHOULD NOT THINK THIS IS REAL!',
        'rating'      => 3.3,
        'price'       => 75
    ],
    // Books category products (example)
    [
        'category'    => 'books',
        'image'       => '../assets/images/Products/Non-Brand/Books/Operating Systems.png',
        'name'        => 'Operating Systems',
        'description' => 'A comprehensive guide to Operating Systems.',
        'rating'      => 4.7,
        'price'       => 25
    ],
    [
        'category'    => 'books',
        'image'       => '../assets/images/Products/Non-Brand/Books/Fluent Python.png',
        'name'        => 'Python Essentials',
        'description' => 'Everything you need to know about Python.',
        'rating'      => 4.5,
        'price'       => 30
    ]
];

// Get the category filter from the query parameter (e.g., ?type=tshirts)
$type = isset($_GET['type']) ? strtolower($_GET['type']) : '';
$filteredProducts = [];

if ($type) {
    foreach ($products as $product) {
        if (strtolower($product['category']) === $type) {
            $filteredProducts[] = $product;
        }
    }
} else {
    $filteredProducts = $products;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>TechSavvies - Categories</title>
  <?php require_once __DIR__ . '/../assets/php/main.php'; ?>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="../assets/css/products.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
  <!-- Header Section -->
  <?php require_once __DIR__ . '/../assets/php/header.php'; ?>

  <!-- Products Grid -->
    <div class="external_grid">
    <?php if (empty($filteredProducts)): ?>
        <div class="no-products">
        <h2>No Products Found !</h2>
        <p>We couldn't find any products in this category.</p>
        </div>
    <?php else: ?>
        <?php foreach ($filteredProducts as $product): ?>
        <div class="card_grid">
            <div class="product">
            <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <h1><?php echo $product['name']; ?></h1>
            <h5><?php echo $product['description']; ?></h5>
            <div class="static-rating" style="--rating: <?php echo $product['rating']; ?>;"></div>
            <h2>$<?php echo $product['price']; ?></h2>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
    </div>

  <!-- Authentication Modal -->
  <?php require_once __DIR__ . '/../assets/php/auth.php'; ?>

  <script src="/assets/js/main.js"></script>
</body>
</html>
