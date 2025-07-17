<?php
// Database connection
include '../../db.php'; // Ensure db.php includes your database connection logic

// Log input data (for debugging)
error_log('Input data: ' . print_r($_POST, true));  // Assuming you're using POST method

header('Content-Type: application/json');

// Initialize response array
$response = array('success' => false);

// Retrieve POST data
$data = json_decode(file_get_contents('php://input'), true);

// Check if MID2 and description are provided
$MID2 = $data['MID2'] ?? null;
$description = $data['description'] ?? null;

if ($MID2 !== null && $description !== null) {
    // Use prepared statement to delete based on MID and Description
    $sql = "DELETE FROM dbo.tbl_CMS_Tracable_Item_List WHERE Mid = ? AND Description = ?";
    $params = array($MID2, $description);
    
    $stmt = sqlsrv_prepare($conn, $sql, $params);

    if ($stmt) {
        if (sqlsrv_execute($stmt)) {
            $response['success'] = true;
        } else {
            $response['error'] = 'Error executing SQL query: ' . print_r(sqlsrv_errors(), true);
        }

        sqlsrv_free_stmt($stmt);
    } else {
        $response['error'] = 'Error preparing SQL statement: ' . print_r(sqlsrv_errors(), true);
    }
} else {
    $response['error'] = 'Invalid MID or description';
}

// Close database connection
sqlsrv_close($conn);

// Return JSON response
echo json_encode($response);
?>
