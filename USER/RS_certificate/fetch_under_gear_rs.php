<?php
include '../../db.php'; // Ensure db.php has your database connection

header('Content-Type: application/json');

// Fetch the CoachID from the request (e.g., via GET or POST)
$coachID = isset($_GET['coachID']) ? $_GET['coachID'] : null;

if ($coachID !== null) {
    // Fetch MID from another table
    $fetchMIDQuery = "SELECT TOP 1 MaintenanceID FROM [CoachMaster_V_2018].[dbo].[tbl_TransactionalData] WHERE [CoachID] = ? ORDER BY MaintenanceID DESC";
    $fetchMIDParams = array($coachID);
    $fetchMIDStmt = sqlsrv_query($conn, $fetchMIDQuery, $fetchMIDParams);

    if ($fetchMIDStmt !== false && sqlsrv_fetch($fetchMIDStmt)) {
        $MID = sqlsrv_get_field($fetchMIDStmt, 0);
    } else {
        $MID = null;
    }

    // Initialize the response array
    $response = array();

    // Define the keys you expect
    $expectedKeys = array(
        'B1No', 'B1A1RB1', 'B1A1D1', 'B1A1D2', 'B1A1RB2', 'B1A1D1RBType', 'B1A1D1D', 'B1A1',
        'B1A1D2D', 'B1A1D2RBType', 'B1A1D1RBMake', 'B1A1D1M', 'B1A1D2M', 'B1A1D2RBMake',
        // Add more fields as needed for B1 Axle 2
        'B1A2RB1', 'B1A2D1', 'B1A2D2', 'B1A2RB2', 'B1A2D1RBType', 'B1A2D1D', 'B1A2', 'B1A2D2D',
        'B1A2D2RBType', 'B1A2D1RBMake', 'B1A2D1M', 'B1A2D2M', 'B1A2D2RBMake',
        //bogie2
        'B2No', 'B2A1RB1', 'B2A1D1', 'B2A1D2', 'B2A1RB2', 'B2A1D1RBType', 'B2A1D1D', 'B2A1',
        'B2A1D2D', 'B2A1D2RBType', 'B2A1D1RBMake', 'B2A1D1M', 'B2A1D2M', 'B2A1D2RBMake',
        // Add more fields as needed for B2 Axle 2
        'B2A2RB1', 'B2A2D1', 'B2A2D2', 'B2A2RB2', 'B2A2D1RBType', 'B2A2D1D', 'B2A2', 'B2A2D2D',
        'B2A2D2RBType', 'B2A2D1RBMake', 'B2A2D1M', 'B2A2D2M', 'B2A2D2RBMake'
    );

    if ($MID !== null) {
        // Prepare and execute your SQL query to fetch data based on MID
        $query = "SELECT * FROM dbo.tbl_CMS_Coach_under_Gear_Details WHERE MID = ?";
        $params = array($MID);
        $stmt = sqlsrv_query($conn, $query, $params);

        if ($stmt !== false) {
            $data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

            if ($data !== false) {
                // Loop through expected keys and populate response
                foreach ($expectedKeys as $key) {
                    // Check if the key exists in $data
                    $response[$key] = isset($data[$key]) ? $data[$key] : null;
                }

                // Return data as JSON
                echo json_encode($response);
            } else {
                // Set all keys to null if no data found for the given MID
                foreach ($expectedKeys as $key) {
                    $response[$key] = null;
                }
                echo json_encode($response);
            }
        } else {
            // Handle query execution error
            http_response_code(500);
            echo json_encode(array('error' => 'Error executing query: ' . print_r(sqlsrv_errors(), true)));
        }
    } else {
        // Set all keys to null if no MID found
        foreach ($expectedKeys as $key) {
            $response[$key] = null;
        }
        echo json_encode($response);
    }
} else {
    // Handle missing or invalid CoachID
    http_response_code(400);
    echo json_encode(array('error' => 'Missing CoachID parameter'));
}

// Close the database connection
sqlsrv_close($conn);
?>
