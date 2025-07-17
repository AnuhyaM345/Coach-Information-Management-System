<?php
// Include the database connection file
include '../../db.php';

// Set content type header to JSON
header('Content-Type: application/json');

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    // Retrieve the MID from GET parameters
    $MID = isset($_GET['MID']) ? $_GET['MID'] : null;

    try {
        // Prepare the SQL query to fetch transactional data for the specific MID
        $query = "SELECT  Mid, Description, UOM, Range, Parameter, Make, Remarks
                  FROM dbo.tbl_CMS_Tracable_Item_List
                  WHERE Mid = ?";

        // Use prepared statement to prevent SQL injection
        $params = array(&$MID);
        $stmt = sqlsrv_prepare($conn, $query, $params);

        if ($stmt === false) {
            throw new Exception('Failed to prepare SQL statement: ' . print_r(sqlsrv_errors(), true));
        }

        // Execute the query
        if (sqlsrv_execute($stmt) === false) {
            throw new Exception('Error executing SQL query: ' . print_r(sqlsrv_errors(), true));
        }

        // Initialize an array to store transactional data
        $traceableData = [];

        // Fetch each row and add it to the traceableData array
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            // Format the date fields
            $formattedRow = [
                'Mid' => $row['Mid'] ?? '',
                'Description' => $row['Description'] ?? null,
                'UOM' => $row['UOM'] ?? '',
                'Range' => $row['Range'] ?? '',
                'Parameter' => $row['Parameter'] ?? '',
                'Make' => $row['Make'] ?? '',
                'Remarks' => $row['Remarks'] ?? '',
            ];

            $traceableData[] = $formattedRow;
        }

        // Check if rows were returned
        if (!empty($traceableData)) {
            // Return success and the transactional data as JSON
            echo json_encode(['success' => true, 'traceableData' => $traceableData]);
        } else {
            // If no rows were found, return an error message
            echo json_encode(['success' => false, 'message' => 'No traceable data found for the given MID']);
        }

    } catch (Exception $e) {
        // Handle any exceptions and output a JSON error response
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
