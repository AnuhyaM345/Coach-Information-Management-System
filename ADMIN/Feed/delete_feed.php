<?php
include '../../db.php'; // Assuming this file includes your database connection

header('Content-Type: application/json');

// Function to write debug information to a file


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maintenanceID = isset($_POST['maintenanceID']) ? $_POST['maintenanceID'] : null;
    $coachID = isset($_POST['coachID']) ? $_POST['coachID'] : null;


    if ($maintenanceID) {
        try {
            
            // Fetch CoachID, workshop_IN, and despatched_date
            $fetchQuery = "SELECT [CoachID],[workshop_IN], [despatched_date] 
                           FROM [CoachMaster_V_2018].[dbo].[tbl_TransactionalData] 
                           WHERE MaintenanceID = ?";
            $fetchParams = array($maintenanceID);


            $fetchStmt = sqlsrv_prepare($conn, $fetchQuery, $fetchParams);

            if ($fetchStmt && sqlsrv_execute($fetchStmt)) {
                $row = sqlsrv_fetch_array($fetchStmt, SQLSRV_FETCH_ASSOC);

                if ($row) {
                    
                    if ($row['CoachID'] != $coachID) {
                        echo json_encode(array('success' => false, 'message' => 'The given CoachNo does not have the MaintenanceID: ' . $maintenanceID));
                        exit;
                    }

                    $workshopIn = $row['workshop_IN'];
                    $despatchedDate = $row['despatched_date'];


                    // Increment the despatched_date by one day
                    if ($despatchedDate) {
                        $despatchedDate->modify('+1 day');
                    }

                    // Determine the appropriate delete query based on the fetched data
                    if ($workshopIn && $despatchedDate) {
                        $deleteQuery = "DELETE FROM dbo.tbl_CoachPosition 
                                        WHERE RSID = ? 
                                        AND SDateIn >= ? 
                                        AND SDateOut <= ?";
                        $workshopInStr = $workshopIn->format('Y-m-d H:i:s');
                        $despatchedDateStr = $despatchedDate->format('Y-m-d H:i:s');
                        $deleteParams = array($coachID, $workshopInStr, $despatchedDateStr);
                    } elseif ($workshopIn) {
                        $deleteQuery = "DELETE FROM dbo.tbl_CoachPosition 
                                        WHERE RSID = ? 
                                        AND SDateIn >= ?";
                        $workshopInStr = $workshopIn->format('Y-m-d H:i:s');
                        $deleteParams = array($coachID, $workshopInStr);
                    } else {
                        $deleteQuery = "DELETE FROM dbo.tbl_CoachPosition 
                                        WHERE RSID = ? 
                                        AND SDateIn IS NULL 
                                        AND SDateOut IS NULL";
                        $deleteParams = array($coachID);
                    }


                    // Execute the delete query for tbl_CoachPosition
                    $deleteStmt = sqlsrv_prepare($conn, $deleteQuery, $deleteParams);

                    if ($deleteStmt && sqlsrv_execute($deleteStmt)) {
                        $rowsAffected = sqlsrv_rows_affected($deleteStmt);

                        // Execute the delete query for tbl_TransactionalData
                        $deleteTransQuery = "DELETE FROM dbo.tbl_TransactionalData WHERE MaintenanceID = ? AND CoachID = ?";
                        $deleteTransParams = array($maintenanceID, $coachID);


                        $deleteTransStmt = sqlsrv_prepare($conn, $deleteTransQuery, $deleteTransParams);

                        if ($deleteTransStmt && sqlsrv_execute($deleteTransStmt)) {
                            $transRowsAffected = sqlsrv_rows_affected($deleteTransStmt);

                            echo json_encode(array('success' => true, 'message' => 'Coach details deleted successfully.'));
                        } else {
                            $errors = sqlsrv_errors();
                            $errorMessage = isset($errors[0]['message']) ? $errors[0]['message'] : 'Unknown error occurred while deleting from tbl_TransactionalData';
                            echo json_encode(array('success' => false, 'message' => 'Error deleting coach details from tbl_TransactionalData: ' . $errorMessage));
                        }
                    } else {
                        $errors = sqlsrv_errors();
                        $errorMessage = isset($errors[0]['message']) ? $errors[0]['message'] : 'Unknown error occurred while deleting from tbl_CoachPosition';
                        echo json_encode(array('success' => false, 'message' => 'Error deleting coach details from tbl_CoachPosition: ' . $errorMessage));
                    }
                } else {
                    echo json_encode(array('success' => false, 'message' => 'No records found for the given Maintenance ID.'));
                }
            } else {
                $errors = sqlsrv_errors();
                $errorMessage = isset($errors[0]['message']) ? $errors[0]['message'] : 'Unknown error occurred';
                echo json_encode(array('success' => false, 'message' => 'Error fetching data: ' . $errorMessage));
            }
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'message' => 'Exception: ' . $e->getMessage()));
        }
    } else {
        echo json_encode(array('success' => false, 'message' => 'Maintenance ID not provided.'));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid request method.'));
}

?>