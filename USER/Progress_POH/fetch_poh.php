<?php
header('Content-Type: application/json');

include '../../db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get M_ID from the query string
$M_ID = isset($_GET['MID']) ? $_GET['MID'] : '';

if (empty($M_ID)) {
    echo json_encode(["success" => false, "message" => "M_ID is required"]);
    exit;
}

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . print_r(sqlsrv_errors(), true)]);
    exit;
}

// Select all columns from the table for the given M_ID
$sql = "SELECT * FROM dbo.tbl_CMS_POH WHERE M_ID = ?";
$params = [$M_ID];
$options = ["Scrollable" => SQLSRV_CURSOR_KEYSET];

$stmt = sqlsrv_query($conn, $sql, $params, $options);

if ($stmt === false) {
    echo json_encode(["success" => false, "message" => "Query failed: " . print_r(sqlsrv_errors(), true)]);
    exit;
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if ($row === false) {
    echo json_encode(["success" => false, "message" => "No records found"]);
} else {
    // Iterate over the row to format any DateTime fields
    foreach ($row as $key => $value) {
        if ($value instanceof DateTime) {
            $row[$key] = $value->format('Y-m-d\TH:i:s');
        } else if ($value === null) {
            $row[$key] = ''; // Handle null values appropriately
        }
    }

    echo json_encode(["success" => true, "POHDetails" => $row]);
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
