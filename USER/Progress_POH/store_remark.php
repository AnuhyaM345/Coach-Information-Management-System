<?php
// store_remark.php

include '../../db.php'; // Include your database connection file here

global $conn;

if (!$conn) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . print_r(sqlsrv_errors(), true)]));
}

// Get the data from the POST request
$coachNo = $_POST['coachNo'];
$category = $_POST['category'] ?? null;
$code = $_POST['code'] ?? null;
$stage = $_POST['stage'];
$dateType = $_POST['dateType']; // 'IN' or 'OUT'
$reason = $_POST['remark'];

// Map the stage and dateType to the correct column name in the Remarks table
$columnMapping = [
    'P_Insp' => ['Primary Inspection In' => 'Inspection_IN', 'Primary Inspection OUT' => 'Inspection_OUT'],
    'L_L' => ['Lift&Lower IN' => 'Lift_lowering_IN', 'Lift&Lower OUT' => 'Lift_lowering_OUT'],
    'Corr' => ['Corrosion IN' => 'Corrosion_IN', 'Corrosion OUT' => 'Corrosion_OUT'],
    'Paint' => ['Paint IN' => 'Paint_IN', 'Paint OUT' => 'Paint_OUT'],
    'Berth' => ['Triming IN' => 'Triming_IN', 'Triming OUT' => 'Triming_OUT'],
    'W_Tank' => ['Water Tank IN' => 'Water_Tank_IN', 'Water Tank OUT' => 'Water_Tank_OUT'],
    'Carriage' => ['Carriage IN' => 'Carriage_IN', 'Carriage OUT' => 'Carriage_OUT'],
    'ETL_AC' => ['ETL AC IN' => 'ETL_AC_IN', 'ETL AC OUT' => 'ETL_AC_OUT'],
    'Air_Br' => ['Air Brake IN' => 'Air_Brake_IN', 'Air Brake OUT' => 'Air_Brake_OUT'],
    'NTXR' => ['NTXR IN' => 'NTXR_IN', 'NTXR OUT' => 'NTXR_OUT']
];

$columnName = $columnMapping[$stage][$dateType] ?? null;

if (!$columnName) {
    die(json_encode(['success' => false, 'message' => "Invalid stage or date type"]));
}

// Fetch CoachID from tbl_CoachMaster
$sql_coach_master = "SELECT CoachID FROM [dbo].[tbl_CoachMaster] WHERE CoachNo = ?";
$params_coach_master = array($coachNo);
$stmt_coach_master = sqlsrv_query($conn, $sql_coach_master, $params_coach_master);

if ($stmt_coach_master === false) {
    die(json_encode(['success' => false, 'message' => "Query failed: " . print_r(sqlsrv_errors(), true)]));
}

$row_coach_master = sqlsrv_fetch_array($stmt_coach_master, SQLSRV_FETCH_ASSOC);
if (!$row_coach_master) {
    die(json_encode(['success' => false, 'message' => "No data found in CoachMaster for CoachNo: $coachNo"]));
}

$CoachID = $row_coach_master['CoachID'];

// Query to get MaintenanceID from tbl_TransactionalData where despatchedDate is null
$sql_transactional = "SELECT MaintenanceID FROM [dbo].[tbl_TransactionalData] WHERE CoachID = ? AND despatched_date IS NULL";
$params_transactional = array($CoachID);
$stmt_transactional = sqlsrv_query($conn, $sql_transactional, $params_transactional);

if ($stmt_transactional === false) {
    die(json_encode(['success' => false, 'message' => "Query failed: " . print_r(sqlsrv_errors(), true)]));
}

$row_transactional = sqlsrv_fetch_array($stmt_transactional, SQLSRV_FETCH_ASSOC);
if (!$row_transactional) {
    die(json_encode(['success' => false, 'message' => "No data found in tbl_TransactionalData for CoachID: $CoachID with despatchedDate as null"]));
}

$MaintenanceID = $row_transactional['MaintenanceID'];

// Check if a record already exists for this CoachNo
$checkSql = "SELECT * FROM [dbo].[tbl_CMS_POH_Remarks] WHERE M_ID = ?";
$checkParams = array($MaintenanceID);
$checkStmt = sqlsrv_query($conn, $checkSql, $checkParams);

if ($checkStmt === false) {
    die(json_encode(['success' => false, 'message' => "Error checking existing record: " . print_r(sqlsrv_errors(), true)]));
}

if (sqlsrv_has_rows($checkStmt)) {
    // Update existing record
    $updateSql = "UPDATE [dbo].[tbl_CMS_POH_Remarks] SET $columnName = ? WHERE M_ID = ?";
    $updateParams = array($reason, $MaintenanceID);
    $updateStmt = sqlsrv_query($conn, $updateSql, $updateParams);

    if ($updateStmt === false) {
        die(json_encode(['success' => false, 'message' => "Error updating record: " . print_r(sqlsrv_errors(), true)]));
    }
} else {
    // Insert new record
    $insertSql = "INSERT INTO [dbo].[tbl_CMS_POH_Remarks] (CoachNo, category, code, $columnName, M_ID) VALUES (?, ?, ?, ?, ?)";
    $insertParams = array($coachNo, $category, $code, $reason, $MaintenanceID);
    $insertStmt = sqlsrv_query($conn, $insertSql, $insertParams);

    if ($insertStmt === false) {
        die(json_encode(['success' => false, 'message' => "Error inserting record: " . print_r(sqlsrv_errors(), true)]));
    }
}

echo json_encode(['success' => true, 'message' => "Remark stored successfully"]);

sqlsrv_free_stmt($checkStmt);
sqlsrv_free_stmt($stmt_coach_master);
sqlsrv_free_stmt($stmt_transactional);
?>