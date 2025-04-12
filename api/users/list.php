<?php 
// Set content type first before any output
header('Content-Type: application/json');

$rowNumber = isset($_GET["rowNumber"])? $_GET["rowNumber"] : 4 ;
$rowOffset = isset($_GET["rowOffset"])? $_GET["rowOffset"] : 0;
$tableName = $_GET["tableName"];

include('../../includes/db.php'); 

// Get total records first
$sqlTotalRecord = "SELECT COUNT(*) AS total FROM ?";
$stmtTotal = $pdo->prepare($sqlTotalRecord);
$stmtTotal-> bindParam(1,$tableName, PDO::PARAM_INT );
$stmtTotal->execute();
$totalResult = $stmtTotal->fetch(PDO::FETCH_ASSOC);
$totalRecords = $totalResult['total']; 

// SQL query to fetch paginated data
$sql = "SELECT * FROM ? LIMIT ? OFFSET ?";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(1,$tableName, PDO::PARAM_INT );
$stmt->bindParam(2, $rowNumber, PDO::PARAM_INT);
$stmt->bindParam(3, $rowOffset, PDO::PARAM_INT);
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