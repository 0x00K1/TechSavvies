<?php
//to avoid sql injection simply make a white list with column and table names
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
?>