<?php
include '../../db.php';

header('Content-Type: application/json'); // Set content type to JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $MID = isset($_POST['MID']) ? $_POST['MID'] : null;
    $B1No = isset($_POST['B1No']) ? $_POST['B1No'] : null;
    $B1A1 = isset($_POST['B1A1']) ? $_POST['B1A1'] : null;
    $B1A1D1 = isset($_POST['B1A1D1']) ? $_POST['B1A1D1'] : null;
    $B1A1D2 = isset($_POST['B1A1D2']) ? $_POST['B1A1D2'] : null;
    $B1A1D1D = is_numeric($_POST['B1A1D1D']) ? $_POST['B1A1D1D'] : null;
    $B1A1D2D = is_numeric($_POST['B1A1D2D']) ? $_POST['B1A1D2D'] : null;
    $B1A1D1M = isset($_POST['B1A1D1M']) ? $_POST['B1A1D1M'] : null;
    $B1A1D2M = isset($_POST['B1A1D2M']) ? $_POST['B1A1D2M'] : null;
    $B1A1RB1 = isset($_POST['B1A1RB1']) ? $_POST['B1A1RB1'] : null;
    $B1A1RB2 = isset($_POST['B1A1RB2']) ? $_POST['B1A1RB2'] : null;
    $B1A1D1RBMake = isset($_POST['B1A1D1RBMake']) ? $_POST['B1A1D1RBMake'] : null;
    $B1A1D2RBMake = isset($_POST['B1A1D2RBMake']) ? $_POST['B1A1D2RBMake'] : null;
    $B1A1D1RBType = isset($_POST['B1A1D1RBType']) ? $_POST['B1A1D1RBType'] : null;
    $B1A1D2RBType = isset($_POST['B1A1D2RBType']) ? $_POST['B1A1D2RBType'] : null;
    $B1A2 = isset($_POST['B1A2']) ? $_POST['B1A2'] : null;
    $B1A2D1 = isset($_POST['B1A2D1']) ? $_POST['B1A2D1'] : null;
    $B1A2D2 = isset($_POST['B1A2D2']) ? $_POST['B1A2D2'] : null;
    $B1A2D1D = is_numeric($_POST['B1A2D1D']) ? $_POST['B1A2D1D'] : null;
    $B1A2D2D = is_numeric($_POST['B1A2D2D']) ? $_POST['B1A2D2D'] : null;
    $B1A2D1M = isset($_POST['B1A2D1M']) ? $_POST['B1A2D1M'] : null;
    $B1A2D2M = isset($_POST['B1A2D2M']) ? $_POST['B1A2D2M'] : null;
    $B1A2RB1 = isset($_POST['B1A2RB1']) ? $_POST['B1A2RB1'] : null;
    $B1A2RB2 = isset($_POST['B1A2RB2']) ? $_POST['B1A2RB2'] : null;
    $B1A2D1RBMake = isset($_POST['B1A2D1RBMake']) ? $_POST['B1A2D1RBMake'] : null;
    $B1A2D2RBMake = isset($_POST['B1A2D2RBMake']) ? $_POST['B1A2D2RBMake'] : null;
    $B1A2D1RBType = isset($_POST['B1A2D1RBType']) ? $_POST['B1A2D1RBType'] : null;
    $B1A2D2RBType = isset($_POST['B1A2D2RBType']) ? $_POST['B1A2D2RBType'] : null;
    $B2No = isset($_POST['B2No']) ? $_POST['B2No'] : null;
    $B2A1 = isset($_POST['B2A1']) ? $_POST['B2A1'] : null;
    $B2A1D1 = isset($_POST['B2A1D1']) ? $_POST['B2A1D1'] : null;
    $B2A1D2 = isset($_POST['B2A1D2']) ? $_POST['B2A1D2'] : null;
    $B2A1D1D = is_numeric($_POST['B2A1D1D']) ? $_POST['B2A1D1D'] : null;
    $B2A1D2D = is_numeric($_POST['B2A1D2D']) ? $_POST['B2A1D2D'] : null;
    $B2A1D1M = isset($_POST['B2A1D1M']) ? $_POST['B2A1D1M'] : null;
    $B2A1D2M = isset($_POST['B2A1D2M']) ? $_POST['B2A1D2M'] : null;
    $B2A1RB1 = isset($_POST['B2A1RB1']) ? $_POST['B2A1RB1'] : null;
    $B2A1RB2 = isset($_POST['B2A1RB2']) ? $_POST['B2A1RB2'] : null;
    $B2A1D1RBMake = isset($_POST['B2A1D1RBMake']) ? $_POST['B2A1D1RBMake'] : null;
    $B2A1D2RBMake = isset($_POST['B2A1D2RBMake']) ? $_POST['B2A1D2RBMake'] : null;
    $B2A1D1RBType = isset($_POST['B2A1D1RBType']) ? $_POST['B2A1D1RBType'] : null;
    $B2A1D2RBType = isset($_POST['B2A1D2RBType']) ? $_POST['B2A1D2RBType'] : null;
    $B2A2 = isset($_POST['B2A2']) ? $_POST['B2A2'] : null;
    $B2A2D1 = isset($_POST['B2A2D1']) ? $_POST['B2A2D1'] : null;
    $B2A2D2 = isset($_POST['B2A2D2']) ? $_POST['B2A2D2'] : null;
    $B2A2D1D = is_numeric($_POST['B2A2D1D']) ? $_POST['B2A2D1D'] : null;
    $B2A2D2D = is_numeric($_POST['B2A2D2D']) ? $_POST['B2A2D2D'] : null;
    $B2A2D1M = isset($_POST['B2A2D1M']) ? $_POST['B2A2D1M'] : null;
    $B2A2D2M = isset($_POST['B2A2D2M']) ? $_POST['B2A2D2M'] : null;
    $B2A2RB1 = isset($_POST['B2A2RB1']) ? $_POST['B2A2RB1'] : null;
    $B2A2RB2 = isset($_POST['B2A2RB2']) ? $_POST['B2A2RB2'] : null;
    $B2A2D1RBMake = isset($_POST['B2A2D1RBMake']) ? $_POST['B2A2D1RBMake'] : null;
    $B2A2D2RBMake = isset($_POST['B2A2D2RBMake']) ? $_POST['B2A2D2RBMake'] : null;
    $B2A2D1RBType = isset($_POST['B2A2D1RBType']) ? $_POST['B2A2D1RBType'] : null;
    $B2A2D2RBType = isset($_POST['B2A2D2RBType']) ? $_POST['B2A2D2RBType'] : null;

    // Prepare and execute the SQL update query
    $query = "UPDATE dbo.tbl_CMS_Coach_under_Gear_Details SET 
    B1No = ?, B1A1 = ?, B1A1D1 = ?, B1A1D2 = ?, B1A1D1D = ?, B1A1D2D = ?, B1A1D1M = ?, B1A1D2M = ?, 
    B1A1RB1 = ?, B1A1RB2 = ?, B1A1D1RBMake = ?, B1A1D2RBMake = ?, B1A1D1RBType = ?, B1A1D2RBType = ?, 
    B1A2 = ?, B1A2D1 = ?, B1A2D2 = ?, B1A2D1D = ?, B1A2D2D = ?, B1A2D1M = ?, B1A2D2M = ?, 
    B1A2RB1 = ?, B1A2RB2 = ?, B1A2D1RBMake = ?, B1A2D2RBMake = ?, B1A2D1RBType = ?, B1A2D2RBType = ?, 
    B2No = ?, B2A1 = ?, B2A1D1 = ?, B2A1D2 = ?, B2A1D1D = ?, B2A1D2D = ?, B2A1D1M = ?, B2A1D2M = ?, 
    B2A1RB1 = ?, B2A1RB2 = ?, B2A1D1RBMake = ?, B2A1D2RBMake = ?, B2A1D1RBType = ?, B2A1D2RBType = ?, 
    B2A2 = ?, B2A2D1 = ?, B2A2D2 = ?, B2A2D1D = ?, B2A2D2D = ?, B2A2D1M = ?, B2A2D2M = ?, 
    B2A2RB1 = ?, B2A2RB2 = ?, B2A2D1RBMake = ?, B2A2D2RBMake = ?, B2A2D1RBType = ?, B2A2D2RBType = ? 
    WHERE MID = ?";
    
    $params = array($B1No, $B1A1, $B1A1D1, $B1A1D2, $B1A1D1D, $B1A1D2D, $B1A1D1M, $B1A1D2M, 
    $B1A1RB1, $B1A1RB2, $B1A1D1RBMake, $B1A1D2RBMake, $B1A1D1RBType, $B1A1D2RBType, 
    $B1A2, $B1A2D1, $B1A2D2, $B1A2D1D, $B1A2D2D, $B1A2D1M, $B1A2D2M, 
    $B1A2RB1, $B1A2RB2, $B1A2D1RBMake, $B1A2D2RBMake, $B1A2D1RBType, $B1A2D2RBType, 
    $B2No, $B2A1, $B2A1D1, $B2A1D2, $B2A1D1D, $B2A1D2D, $B2A1D1M, $B2A1D2M, 
    $B2A1RB1, $B2A1RB2, $B2A1D1RBMake, $B2A1D2RBMake, $B2A1D1RBType, $B2A1D2RBType, 
    $B2A2, $B2A2D1, $B2A2D2, $B2A2D1D, $B2A2D2D, $B2A2D1M, $B2A2D2M, 
    $B2A2RB1, $B2A2RB2, $B2A2D1RBMake, $B2A2D2RBMake, $B2A2D1RBType, $B2A2D2RBType,
    $MID);

    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt !== false) {
        echo json_encode(array('success' => true, 'message' => 'UG details updated successfully.'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error updating UG details: ' . print_r(sqlsrv_errors(), true)));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid request method.'));
}
?>