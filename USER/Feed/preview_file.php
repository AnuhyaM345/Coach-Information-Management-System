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

            if (file_exists($filePath)) {
                // Serve the PDF file for preview
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="' . $fileName . '"');
                readfile($filePath);
                exit;
            } else {
                echo 'File not found.';
            }
        } else {
            echo 'No PDF found for the given MID.';
        }
    } else {
        echo 'Database query failed: ' . print_r(sqlsrv_errors(), true);
    }
} else {
    echo 'Invalid request method.';
}
?>
