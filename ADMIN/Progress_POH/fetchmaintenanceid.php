<?php
include '../../db.php';

// Retrieve coachID from query parameter
$coachID = isset($_GET['coachID']) ? $_GET['coachID'] : null;

// Validate coachID
if (!$coachID) {
    echo json_encode(array('error' => 'Invalid coachID'));
    exit();
}

// Prepare the SQL query
$sql = "SELECT TOP 1 MaintenanceID FROM [CoachMaster_V_2018].[dbo].[tbl_TransactionalData] WHERE [CoachID] = ? AND despatched_date IS NULL";
$params = array($coachID);

// Execute the query
$stmt = sqlsrv_query($conn, $sql, $params);

// Check for query execution errors
if ($stmt === false) {
    echo json_encode(array('error' => 'Query execution failed', 'details' => sqlsrv_errors()));
    exit();
}

// Fetch the MaintenanceID
$maintenanceID = null;
if (sqlsrv_fetch($stmt) !== false) {
    $maintenanceID = sqlsrv_get_field($stmt, 0);
}

// Free the statement and close the connection
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

// Return the MaintenanceID as JSON
header('Content-Type: application/json');
echo json_encode(array('maintenanceID' => $maintenanceID));
?>
