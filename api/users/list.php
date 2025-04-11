<?php 
$rowNumber = $_GET["rowNumber"];
$rowOffset = $_GET["rowOffset"];
$totalRecord;
include ('..\..\includes\db.php');
// SQL query to fetch data (example: fetch all from 'users' table)
$sql = "SELECT * FROM customers
Limit :rowNumber
OFFSET :rowOffset";   //database got update and users is customers

$stmt = $pdo->prepare($sql);
$stmt ->bindParam(':rowNumber',$rowNumber ,PDO::PARAM_INT);
$stmt ->bindParam(':rowOffset',$rowOffset, PDO::PARAM_INT);
$stmt->execute();

$sqlTotalRecord= "SELECT COUNT(*) FROM Customers;";
$stmtTotal = $pdo->prepare($sqlTotalRecord);
$stmtTotal -> execute();
$totalResult = $stmtTotal->fetch(PDO::FETCH_ASSOC);
$totalRecords = $totalResult['total'];
echo "<input type='hidden' id='totalRecordsHidden' value='$totalRecords'>";

// Fetch all rows as an associative array
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If no data is found, you might want to return a specific message
if (empty($data)) {
    $data['message'] = "No data found.";
}

// Set content type to application/json
header('Content-Type: application/json');

// Output the JSON data
echo json_encode($data);
// Close the database connection by setting the PDO object to null
$pdo = null;

?>