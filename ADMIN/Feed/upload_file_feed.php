<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection function
include '../../db.php';

// Include configuration file
require_once 'config.php';

header('Content-Type: application/json');

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve MID from POST request
    $mid = isset($_POST['mid']) ? $_POST['mid'] : null;

    if ($mid === null) {
        $response['error'] = 'MID is required.';
        echo json_encode($response);
        exit;
    }

    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileDest = $config['upload_dir'] . $fileName;

        // Validate file type
        $fileType = mime_content_type($fileTmpPath);
        if ($fileType != 'application/pdf') {
            $response['error'] = 'Only PDF files are allowed.';
            echo json_encode($response);
            exit;
        }

        if (file_exists($fileDest)) {
            $response['error'] = 'A file with the same name already exists.';
            echo json_encode($response);
            exit;
        }

        // Ensure the upload directory exists
        if (!is_dir($config['upload_dir'])) {
            if (!mkdir($config['upload_dir'], 0777, true)) {
                $response['error'] = 'Failed to create upload directory.';
                echo json_encode($response);
                exit;
            }
        }

        if (move_uploaded_file($fileTmpPath, $fileDest)) {
            if ($conn) {
                // Prepare SQL statement for updating data
                $tsql = "UPDATE dbo.tbl_TransactionalData SET PDF = ? WHERE MaintenanceID = ?";
                $params = [$fileName, $mid];
                $stmt = sqlsrv_query($conn, $tsql, $params);

                if ($stmt === false) {
                    $response['error'] = 'Database query failed: ' . print_r(sqlsrv_errors(), true);
                } else {
                    // Use sqlsrv_free_stmt() only if $stmt is a resource
                    if (is_resource($stmt)) {
                        sqlsrv_free_stmt($stmt);
                    }
                    $response['success'] = true;
                }

                // Close connection
                sqlsrv_close($conn);
            } else {
                $response['error'] = 'Database connection failed: ' . print_r(sqlsrv_errors(), true);
            }
        } else {
            $response['error'] = 'Error moving uploaded file.';
        }
    } else {
        $response['error'] = 'File upload error or no file uploaded.';
    }
} else {
    $response['error'] = 'Invalid request method.';
}

echo json_encode($response);
?>