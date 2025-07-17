<?php
include '../../db.php';

// Check if coachNumber is provided via POST
if(isset($_POST['coachNumber'])) {
    $coachNumber = $_POST['coachNumber'];

    // Prepare and execute the SQL query
    $query = "SELECT * FROM dbo.tbl_CoachMaster WHERE CoachNo = ?";
    $params = array($coachNumber);
    $stmt = sqlsrv_query($conn, $query, $params);

    // Check if the query was executed successfully
    if($stmt !== false) {
        // Fetch the coach details
        $coachDetails = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        // Check if coach details were found
        if($coachDetails) {
            // Format Built Date
            if (!empty($coachDetails['BuiltDate'])) {
                $coachDetails['BuiltDate'] = date_format($coachDetails['BuiltDate'], 'Y-m-d');
            } else {
                // Set default date if BuiltDate is null
                $coachDetails['BuiltDate'] = '9999-09-09'; // Default date set to '9999-09-09'
            }
        
            // Format Induction Date
            if (!empty($coachDetails['InductionDate'])) {
                $coachDetails['InductionDate'] = date_format($coachDetails['InductionDate'], 'Y-m-d');
            } else {
                // Set default date if InductionDate is null
                $coachDetails['InductionDate'] = '9999-09-09'; // Default date set to '9999-09-09'
            }
        
            // Add any additional properties that are required by the form fields
            $coachDetails['Drawing_No'] = ''; // Set an empty value or provide a default value
            $coachDetails['Type'] = $coachDetails['VehicleType']; // Assuming 'Type' corresponds to 'VehicleType'
            $coachDetails['Built'] = $coachDetails['Built'];
            $coachDetails['Railway'] = $coachDetails['Railway'];
            $coachDetails['OwningDivision'] = $coachDetails['OwningDivision'];
            $coachDetails['BaseDepot'] = $coachDetails['BaseDepot'];
            $coachDetails['CouplingType'] = $coachDetails['CouplingType'];
            $coachDetails['BrakeSystem'] = $coachDetails['BrakeSystem'];
            $coachDetails['US_RM'] = $coachDetails['US_RM'];
            $coachDetails['Periodicity'] = $coachDetails['Periodicity'];
        
            // Return coach details as JSON response
            echo json_encode(array('success' => true, 'coachDetails' => $coachDetails));
        } else {
            // If coach details were not found, return error message as JSON
            echo json_encode(array('success' => false, 'message' => 'Coach details not found.'));
        }
    }
}
?>