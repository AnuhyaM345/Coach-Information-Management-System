<?php
// Include the database connection file
include '../../db.php';

// Set content type header to JSON
header('Content-Type: application/json');

// Function to log query details

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    // Retrieve the coachID from GET parameters
    $coachID = isset($_GET['coachID']) ? $_GET['coachID'] : null;

    try {
        // Prepare the SQL query to fetch transactional data for the specific coachID
        $query = "SELECT  MaintenanceID, last_POH_DATE, previous_RD, yard_IN, condition , workshop_IN, NC_offered, NC_fit, despatched_date, POH_shop, repair_type, workorder_no, return_date
                  FROM dbo.tbl_TransactionalData 
                  WHERE CoachID = ? ORDER BY MaintenanceID DESC";

        // Use prepared statement to prevent SQL injection
        $params = array($coachID);
        $stmt = sqlsrv_prepare($conn, $query, $params);

        if ($stmt === false) {
            $error = print_r(sqlsrv_errors(), true);
            throw new Exception('Failed to prepare SQL statement: ' . $error);
        }

        // Execute the query
        if (sqlsrv_execute($stmt) === false) {
            $error = print_r(sqlsrv_errors(), true);
            throw new Exception('Error executing SQL query: ' . $error);
        }

        // Initialize an array to store transactional data
        $transactionalData = [];

        // Fetch each row and add it to the transactionalData array
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            // Format the date fields
            $formattedRow = [
                'MaintenanceID' => $row['MaintenanceID'],
                'last_POH_DATE' => !empty($row['last_POH_DATE']) ? date_format($row['last_POH_DATE'], 'd-m-Y') : null,
                'previous_RD' => !empty($row['previous_RD']) ? date_format($row['previous_RD'], 'd-m-Y') : null,
                'yard_IN' => !empty($row['yard_IN']) ? date_format($row['yard_IN'], 'd-m-Y') : null,
                'condition' => $row['condition'],
                'workshop_IN' => !empty($row['workshop_IN']) ? date_format($row['workshop_IN'], 'd-m-Y') : null,
                'NC_offered' => !empty($row['NC_offered']) ? date_format($row['NC_offered'], 'd-m-Y') : null,
                'NC_fit' => !empty($row['NC_fit']) ? date_format($row['NC_fit'], 'd-m-Y') : null,
                'despatched_date' => !empty($row['despatched_date']) ? date_format($row['despatched_date'], 'd-m-Y') : null,
                'POH_shop' => $row['POH_shop'],
                'repair_type' => $row['repair_type'],
                'workorder_no' => $row['workorder_no'],
                'return_date' => !empty($row['return_date']) ? date_format($row['return_date'], 'd-m-Y') : null
            ];

            $transactionalData[] = $formattedRow;
        }

        // Log the query result

        // Check if rows were returned
        if (!empty($transactionalData)) {
            // Return success and the transactional data as JSON
            $response = ['success' => true, 'transactionalData' => $transactionalData];
            echo json_encode($response);
        } else {
            // If no rows were found, return an error message
            $response = ['success' => false, 'message' => 'No transactional data found for the given CoachID'];
            echo json_encode($response);
        }

    } catch (Exception $e) {
        // Handle any exceptions and output a JSON error response
        $response = ['success' => false, 'message' => $e->getMessage()];
        echo json_encode($response);
    }
}
?>