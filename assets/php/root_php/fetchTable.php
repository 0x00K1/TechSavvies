<?php
/* use mapping and $_GET[table] 
$tableMap = [
    'products' => ['tableName' => 'products', 'columns' => ['product_id', 'product_name', 'price']],
    'customers' => ['tableName' => 'customers', 'columns' => ['customer_id', 'username', 'email']],
];

$tableKey = $_GET['table'] ?? '';
if (!isset($tableMap[$tableKey])) {
    die(json_encode(['error' => 'Invalid table identifier']));
}

$tableName = $tableMap[$tableKey]['tableName'];
$columns = $tableMap[$tableKey]['columns'];
*/
header('Content-Type: application/json');

$rowNumber = isset($_GET["rowNumber"])? $_GET["rowNumber"] : 4 ;
$rowOffset = isset($_GET["rowOffset"])? $_GET["rowOffset"] : 0;
$tableName = isset($_GET["tableName"])? $_GET["tableName"] : 'orders';
$sortBy = $_GET['sortBy'] ?? null;
$sortDirection = ($_GET['sortDirection'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';

include('../../../includes/db.php');

// Get total records first
$sqlTotalRecord = "SELECT COUNT(*) AS total FROM  {$tableName}";
$stmtTotal = $pdo->prepare($sqlTotalRecord);
$stmtTotal->execute();
$totalResult = $stmtTotal->fetch(PDO::FETCH_ASSOC);
$totalRecords = $totalResult['total'];

// SQL query to fetch paginated data and sorting
$sql = "SELECT * FROM  {$tableName}";

// Add sorting if sortBy is provided
if ($sortBy) {
    // Sanitize the $sortBy value to prevent SQL injection
    // This is a basic example; you might want a more robust method
    $safeSortBy = preg_replace('/[^a-zA-Z0-9_]/', '', $sortBy);
    $sql .= " ORDER BY " . $safeSortBy . " " . $sortDirection;
}

$sql .= " LIMIT ? OFFSET ?";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(1, $rowNumber, PDO::PARAM_INT);
$stmt->bindParam(2, $rowOffset, PDO::PARAM_INT);
$stmt->execute();

// Fetch all rows as an associative array
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create a single response object with both the data and total count
$response = [
    'records' => $data,
    'totalRecords' => $totalRecords
];

// If no data is found, add a message
if (empty($data)) {
    $response['message'] = "No data found.";
}

// Output the complete JSON response
echo json_encode($response);

// Close the database connection
$pdo = null;

/*new AI code ##########################################################################################
                 ##########################################################################################
                 ##########################################################################################

// Table mapping for allowed tables and columns for updates (and security)
$tableMap = [
    'products' => ['tableName' => 'products', 'columns' => ['product_id', 'product_name', 'price']],
    'customers' => ['tableName' => 'customers', 'columns' => ['customer_id', 'username', 'email']],
    'orders'    => ['tableName' => 'orders', 'columns' => ['order_id', 'order_date', 'amount']]
];

$action = $_GET['action'] ?? 'fetch';

// Determine table based on mapping if provided
$tableKey = $_GET['table'] ?? '';
if ($tableKey && isset($tableMap[$tableKey])) {
    $tableName = $tableMap[$tableKey]['tableName'];
    $allowedColumns = $tableMap[$tableKey]['columns'];
} else {
    // If no valid mapping exists, obtain tableName from GET and leave allowedColumns empty
    $tableName = $_GET["tableName"] ?? 'orders';
    $allowedColumns = [];
}

include('../../../includes/db.php');

if ($action === 'update') {
    // For updates, require an identifier (using the first column from allowed columns as primary key)
    if (empty($allowedColumns)) {
        echo json_encode(["error" => "Update not allowed for this table."]);
        exit;
    }
    if (!isset($_GET['id'])) {
        echo json_encode(["error" => "Missing record id."]);
        exit;
    }
    $id = $_GET['id'];

    // Get fields to update from POST data, but only allow fields listed in the allowed columns (except the id field)
    $fieldsToUpdate = [];
    foreach ($_POST as $key => $value) {
        if (in_array($key, $allowedColumns) && $key !== $allowedColumns[0]) {
            $fieldsToUpdate[$key] = $value;
        }
    }

    if (empty($fieldsToUpdate)) {
        echo json_encode(["error" => "No valid fields provided for update."]);
        exit;
    }

    // Build the UPDATE query using prepared statements
    $setParts = [];
    foreach ($fieldsToUpdate as $col => $val) {
        $setParts[] = "$col = :$col";
    }
    $setString = implode(", ", $setParts);
    $sql = "UPDATE {$tableName} SET $setString WHERE {$allowedColumns[0]} = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    foreach ($fieldsToUpdate as $col => $value) {
        $stmt->bindValue(":$col", $value);
    }

    $stmt->execute();
    echo json_encode(["success" => "Record updated."]);
    exit;
}

// Default action: fetch data
header('Content-Type: application/json');

// Get pagination and sorting parameters
$rowNumber = isset($_GET["rowNumber"]) ? $_GET["rowNumber"] : 4;
$rowOffset = isset($_GET["rowOffset"]) ? $_GET["rowOffset"] : 0;
$sortBy = $_GET['sortBy'] ?? null;
$sortDirection = ($_GET['sortDirection'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';

// Get total records first
$sqlTotalRecord = "SELECT COUNT(*) AS total FROM {$tableName}";
$stmtTotal = $pdo->prepare($sqlTotalRecord);
$stmtTotal->execute();
$totalResult = $stmtTotal->fetch(PDO::FETCH_ASSOC);
$totalRecords = $totalResult['total'];

// Build query for fetching data
$sql = "SELECT * FROM {$tableName}";

// Sanitize and add sorting if sortBy is provided
if ($sortBy) {
    $safeSortBy = preg_replace('/[^a-zA-Z0-9_]/', '', $sortBy);
    $sql .= " ORDER BY " . $safeSortBy . " " . $sortDirection;
}

$sql .= " LIMIT ? OFFSET ?";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(1, $rowNumber, PDO::PARAM_INT);
$stmt->bindParam(2, $rowOffset, PDO::PARAM_INT);
$stmt->execute();

// Fetch all rows as an associative array
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create a single response object with both the data and total count
$response = [
    'records' => $data,
    'totalRecords' => $totalRecords
];

// If no data is found, add a message
if (empty($data)) {
    $response['message'] = "No data found.";
}

// Output the complete JSON response
echo json_encode($response);

// Close the database connection
$pdo = null;
*/
?>

