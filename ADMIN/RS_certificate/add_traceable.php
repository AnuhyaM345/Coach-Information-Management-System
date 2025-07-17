<?php
header('Content-Type: application/json'); // Set JSON header

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../db.php'; // Include database connection setup

// Retrieve form data and check if they are not empty
$MID2 = !empty($_POST['MID2']) ? $_POST['MID2'] : null;
$description = !empty($_POST['description']) ? $_POST['description'] : null;
$uom = !empty($_POST['uom']) ? $_POST['uom'] : null;
$range = !empty($_POST['range']) ? $_POST['range'] : null;
$parameter = !empty($_POST['parameter']) ? $_POST['parameter'] : null;
$make = !empty($_POST['make']) ? $_POST['make'] : null;
$remarks = !empty($_POST['remarks']) ? $_POST['remarks'] : null;

if ($MID2 === null) {
    echo json_encode(['success' => false, 'error' => 'MID2 is required']);
    exit;
}

if ($description === null) {
    echo json_encode(['success' => false, 'error' => 'Description is required']);
    exit;
}

// Insert data into the database
$sql = "INSERT INTO dbo.tbl_CMS_Tracable_Item_List (Mid, Description, UOM, Range, Parameter, Make, Remarks)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$params = array($MID2, $description, $uom, $range, $parameter, $make, $remarks);

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    $response = array('success' => false, 'error' => sqlsrv_errors());
} else {
    $response = array('success' => true, 'MID' => $MID2); // Assuming $MID2 is returned as confirmation
}

echo json_encode($response); // Output JSON response

sqlsrv_close($conn); // Close connection
?>
