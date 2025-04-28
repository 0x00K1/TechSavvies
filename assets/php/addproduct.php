<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/secure_session.php';
require_once __DIR__ . '/env_loader.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitAddProductName'])) {
    
    $productName   = trim($_POST['productNameName']);
    $categoryName  = trim($_POST['ProCategoryName']); // Note: We'll discuss this
    $description   = trim($_POST['ProductDescreptionName']);
    $color         = trim($_POST['productColorName']);
    $size          = trim($_POST['productSizeName']);
    $price         = floatval($_POST['productPriceName']);
    $stock         = intval($_POST['productStockName']);

    $errors = [];

    // Optional: Validate required fields
    if (empty($productName) || empty($categoryName) || empty($description) || empty($color) || empty($size) || $price <= 0) {
        $errors[] = "Please fill all required fields correctly.";
    }

    // Handle file upload
    $pictureFileName = null;
    if (isset($_FILES['imageName']) && $_FILES['imageName']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../assets/images/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }
        $tmpName = $_FILES['imageName']['tmp_name'];
        $originalName = basename($_FILES['imageName']['name']);
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif'])) {
            $pictureFileName = uniqid('product_', true) . '.' . $ext;
            $destination = $uploadDir . $pictureFileName;
            if (!move_uploaded_file($tmpName, $destination)) {
                $errors[] = "Failed to move uploaded file.";
            }
        } else {
            $errors[] = "Only PNG, JPG, JPEG, GIF files are allowed.";
        }
    }

    // If no errors, proceed
    if (empty($errors)) {
        try {
            // Find category_id from category name (assuming a 'categories' table exists)
            $stmt = $pdo->prepare("SELECT category_id FROM categories WHERE category_name = :category_name LIMIT 1");
            $stmt->execute(['category_name' => $categoryName]);
            $category = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$category) {
                $errors[] = "Category not found.";
            } else {
                $categoryId = $category['category_id'];

                $createdBy = $_SESSION['user_id'] ?? null; // Assuming you store root/admin user ID in session

                $stmt = $pdo->prepare("
                    INSERT INTO products (category_id, product_name, picture, description, color, size, price, stock, created_by)
                    VALUES (:category_id, :product_name, :picture, :description, :color, :size, :price, :stock, :created_by)
                ");
                $stmt->execute([
                    ':category_id' => $categoryId,
                    ':product_name' => $productName,
                    ':picture' => $pictureFileName,
                    ':description' => $description,
                    ':color' => $color,
                    ':size' => $size,
                    ':price' => $price,
                    ':stock' => $stock,
                    ':created_by' => $createdBy
                ]);

                echo "<script>alert('Product added successfully!'); window.location.href = '/../../root/index.php';</script>";
                exit;
            }
        } catch (PDOException $e) {
            error_log("Database Insert Error: " . $e->getMessage());
            $errors[] = "Failed to add product. Please try again.";
        }
    }

    // Handle errors
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<script>alert('Error: $error');</script>";
        }
    }
}
?>
