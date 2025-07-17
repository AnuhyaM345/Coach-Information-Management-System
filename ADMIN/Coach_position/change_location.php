<?php
// Include your database connection file
include '../../db.php';

// Function to handle JSON response
function jsonResponse($success, $message = '') {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

try {
    // Get the JSON data from input
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!$data) {
        jsonResponse(false, 'Invalid JSON data received.');
    }

    // Extract data from JSON
    $dateOut = isset($data['dateOut']) ? $data['dateOut'] : '';
    $shop = isset($data['shop']) ? $data['shop'] : '';
    $lineNumber = isset($data['lineNumber']) ? $data['lineNumber'] : '';
    $newLocation = isset($data['newLocation']) ? $data['newLocation'] : '';
    $activity = isset($data['activity']) ? $data['activity'] : '';
    $dateIn = isset($data['dateIn']) ? $data['dateIn'] : '';
    $coachNumber = isset($data['coachNumber']) ? $data['coachNumber'] : '';

    if (empty($shop) || empty($lineNumber) || empty($newLocation)) {
        jsonResponse(false, 'Shop, line number and new location fields are required.');
    }

    // Validate datetime fields
    if (empty($dateOut) || empty($dateIn)) {
        jsonResponse(false, 'Date fields cannot be empty.');
    }

    // Parse datetime strings into DateTime objects
    $dateTimeOut = new DateTime($dateOut);
    $dateTimeIn = new DateTime($dateIn);

    // Format datetime objects
    $formattedDateOut = $dateTimeOut->format('Y-m-d H:i:s');
    $formattedDateIn = $dateTimeIn->format('Y-m-d H:i:s');

    // Fetch Shop_Name based on shop code
    $shopQuery = "SELECT Shop_Name FROM CoachMaster_V_2018.dbo.tbl_CMS_ShopInformation WHERE Shop_Code = ?";
    $shopParams = array($shop);
    $shopStmt = sqlsrv_query($conn, $shopQuery, $shopParams);

    if ($shopStmt === false) {
        // SQL query error handling
        $errors = sqlsrv_errors();
        $errorMessage = isset($errors[0]['message']) ? $errors[0]['message'] : 'Unknown error occurred.';
        jsonResponse(false, 'Failed to fetch Shop_Name: ' . $errorMessage);
    }

    // Fetch the result
    $shopName = '';
    if (sqlsrv_fetch($shopStmt) === true) {
        $shopName = sqlsrv_get_field($shopStmt, 0);
    } else {
        jsonResponse(false, "Failed to fetch Shop_Name for Shop_Code: $shop");
    }

    // Construct the Cloaction string
    $cloaction = "At: $shopName" . "_Line $lineNumber" . "_Location $newLocation";

    // Construct the CoachLocation string
    $coachLocation = "$shop" . "$lineNumber" . "_$newLocation";

    // Check if the coach already exists at the location
    $checkQuery = "SELECT [CoachNo] FROM CoachMaster_V_2018.dbo.tbl_CoachPosition WHERE CoachLocation = ? AND SDateOut IS NULL";
    $checkParams = array($coachLocation);
    $checkStmt = sqlsrv_query($conn, $checkQuery, $checkParams);

    if ($checkStmt === false) {
        // SQL query error handling
        $errors = sqlsrv_errors();
        $errorMessage = isset($errors[0]['message']) ? $errors[0]['message'] : 'Unknown error occurred.';
        jsonResponse(false, 'Failed to check existing coach: ' . $errorMessage);
    }

    if (sqlsrv_fetch($checkStmt) === true) {
        $coachNo = sqlsrv_get_field($checkStmt, 0);
        
        // Coach exists at the location
        jsonResponse(false, "The location $coachLocation is already occupied by coach number $coachNo.");
    }

    // Fetch RSID (CoachID) based on coach number
    $rsidQuery = "SELECT CoachID FROM CoachMaster_V_2018.dbo.tbl_CoachMaster WHERE CoachNo = ?";
    $rsidParams = array($coachNumber);
    $rsidStmt = sqlsrv_query($conn, $rsidQuery, $rsidParams);

    if ($rsidStmt === false) {
        // SQL query error handling
        $errors = sqlsrv_errors();
        $errorMessage = isset($errors[0]['message']) ? $errors[0]['message'] : 'Unknown error occurred.';
        jsonResponse(false, 'Failed to fetch RSID: ' . $errorMessage);
    }

    // Fetch the result
    $rsid = '';
    if (sqlsrv_fetch($rsidStmt) === true) {
        $rsid = sqlsrv_get_field($rsidStmt, 0);
    } else {
        jsonResponse(false, "Failed to fetch RSID for CoachNo: $coachNumber");
    }

    // Start a transaction
    sqlsrv_begin_transaction($conn);

    // Update the existing row where SDateOut is NULL for the same CoachNo
    $updateQuery = "UPDATE CoachMaster_V_2018.dbo.tbl_CoachPosition
                    SET SDateOut = ?
                    WHERE CoachNo = ? AND SDateOut IS NULL";

    // Parameters for the update query
    $updateParams = array($formattedDateOut, $coachNumber);

    // Prepare the update statement
    $updateStmt = sqlsrv_prepare($conn, $updateQuery, $updateParams);

    // Execute the update statement
    if ($updateStmt === false || sqlsrv_execute($updateStmt) === false) {
        sqlsrv_rollback($conn);
        $errors = sqlsrv_errors();
        $errorMessage = isset($errors[0]['message']) ? $errors[0]['message'] : 'Unknown error occurred.';
        jsonResponse(false, 'Failed to update existing row: ' . $errorMessage);
    }

    // Prepare the SQL query to insert a new row
    $insertQuery = "INSERT INTO CoachMaster_V_2018.dbo.tbl_CoachPosition
                    (CoachNo, CoachLocation, Shop_Activity, SDateIn, SDateOut, WorkType, CLocation, RSID)
                    VALUES (?, ?, ?, ?, NULL, ?, ?, ?)";

    // Parameters to be bound to the insert query
    $insertParams = array($coachNumber, $coachLocation, $activity, $formattedDateIn, $activity, $cloaction, $rsid);

    // Prepare the insert statement
    $insertStmt = sqlsrv_prepare($conn, $insertQuery, $insertParams);

    // Execute the insert statement
    if ($insertStmt === false || sqlsrv_execute($insertStmt) === false) {
        sqlsrv_rollback($conn);
        $errors = sqlsrv_errors();
        $errorMessage = isset($errors[0]['message']) ? $errors[0]['message'] : 'Unknown error occurred.';
        jsonResponse(false, 'Failed to insert new row: ' . $errorMessage);
    }

    // Commit the transaction
    sqlsrv_commit($conn);

    // If successful
    jsonResponse(true, 'Record updated successfully.');

} catch (Exception $e) {
    // If an error occurs
    jsonResponse(false, 'Error: ' . $e->getMessage());
}
?>
