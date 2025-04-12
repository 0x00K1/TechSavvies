<?php 
// IMportant notes need to check  {$tableName} for any violation
header('Content-Type: application/json');

$rowNumber = isset($-GET["rowNumber"])? $-GET["rowNumber"] : 4 ;
$rowOffset = isset($-GET["rowOffset"])? $-GET["rowOffset"] : 0;
$tableName = isset($-GET["tableName"])? $-GET["tableName"] : 'orders';

include('../../includes/db.php'); 

// Get total records first
$sqlTotalRecord = "SELECT COUNT(*) AS total FROM  {$tableName}";
$stmtTotal = $pdo->prepare($sqlTotalRecord);
$stmtTotal->execute();
$totalResult = $stmtTotal->fetch(PDO::FETCH-ASSOC);
$totalRecords = $totalResult['total']; 

// SQL query to fetch paginated data
$sql = "SELECT * FROM  {$tableName} LIMIT ? OFFSET  ?";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(1, $rowNumber, PDO::PARAM-INT);
$stmt->bindParam(2, $rowOffset, PDO::PARAM-INT);
$stmt->execute();

// Fetch all rows as an associative array
$data = $stmt->fetchAll(PDO::FETCH-ASSOC);

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
echo json-encode($response);

// Close the database connection
$pdo = null;
?>