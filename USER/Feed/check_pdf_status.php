<?php
// Include the database connection file
include '../../db.php';

// Set content type header to JSON
header('Content-Type: application/json');

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve the MaintenanceID from GET parameters
    $maintenanceID = isset($_GET['maintenanceID']) ? $_GET['maintenanceID'] : null;

    if ($maintenanceID === null) {
        echo json_encode(['success' => false, 'message' => 'MaintenanceID is required']);
        exit;
    }

    try {
        // Prepare the SQL query to check if a PDF exists and get its name for the given MaintenanceID
        $query = "SELECT PDF FROM dbo.tbl_TransactionalData WHERE MaintenanceID = ?";

        // Use prepared statement to prevent SQL injection
        $params = array($maintenanceID);
        $stmt = sqlsrv_query($conn, $query, $params);

        if ($stmt === false) {
            throw new Exception('Error executing SQL query: ' . print_r(sqlsrv_errors(), true));
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        if ($row) {
            $has_pdf = !empty($row['PDF']);
            echo json_encode([
                'success' => true, 
                'has_pdf' => $has_pdf,
                'pdf_name' => $has_pdf ? $row['PDF'] : null
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No record found for the given MaintenanceID']);
        }

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>