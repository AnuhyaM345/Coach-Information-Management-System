<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection file and configuration file
include '../../db.php';
require_once 'config.php';

header('Content-Type: application/json');

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve MID from GET request
    $mid = isset($_GET['mid']) ? $_GET['mid'] : null;

    if ($mid === null) {
        $response['error'] = 'MID is required.';
        echo json_encode($response);
        exit;
    }

    // Prepare SQL query to fetch the PDF file name
    $query = "SELECT PDF FROM dbo.tbl_TransactionalData WHERE MaintenanceID = ?";
    $params = array($mid);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt !== false) {
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row && !empty($row['PDF'])) {
            $fileName = $row['PDF'];
            $filePath = $config['upload_dir'] . $fileName;

            // Delete the file from the filesystem
            if (file_exists($filePath) && unlink($filePath)) {
                // Prepare SQL statement for updating the record
                $tsql = "UPDATE dbo.tbl_TransactionalData SET PDF = NULL WHERE MaintenanceID = ?";
                $stmtUpdate = sqlsrv_query($conn, $tsql, $params);

                if ($stmtUpdate !== false) {
                    $response['success'] = true;
                } else {
                    $response['error'] = 'Database update failed: ' . print_r(sqlsrv_errors(), true);
                }
            } else {
                $response['error'] = 'File deletion failed or file does not exist.';
            }
        } else {
            $response['error'] = 'No PDF found for the given MID.';
        }
    } else {
        $response['error'] = 'Database query failed: ' . print_r(sqlsrv_errors(), true);
    }
} else {
    $response['error'] = 'Invalid request method.';
}

// Return JSON response
echo json_encode($response);
?>