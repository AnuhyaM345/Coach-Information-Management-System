<?php
include '../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $coachID = isset($_POST['coachID']) ? $_POST['coachID'] : null;
    $coachNo = isset($_POST['coachNo']) ? $_POST['coachNo'] : null;
    $oldCoachNo = isset($_POST['oldCoachNo']) ? $_POST['oldCoachNo'] : null;
    $code = isset($_POST['code']) ? $_POST['code'] : null;
    $type = isset($_POST['type']) ? $_POST['type'] : null;
    $railway = isset($_POST['railway']) ? $_POST['railway'] : null;
    $vehicleType = isset($_POST['vehicleType']) ? $_POST['vehicleType'] : null;
    $category = isset($_POST['category']) ? $_POST['category'] : null;
    $AC_Flag = isset($_POST['AC_Flag']) ? $_POST['AC_Flag'] : null;
    $brakesystem = isset($_POST['brakesystem']) ? $_POST['brakesystem'] : null;

    $builtDate = isset($_POST['builtDate']) ? $_POST['builtDate'] : null;
    $inductionDate = isset($_POST['inductionDate']) ? $_POST['inductionDate'] : null;

    $formattedDatebuilt = null;
    $formattedDateinduction = null;

    if (!empty($builtDate)) {
        $datebuilt = new DateTime($builtDate);
        $formattedDatebuilt = $datebuilt->format('Y-m-d H:i:s');
    }

    if (!empty($inductionDate)) {
        $dateinduction = new DateTime($inductionDate);
        $formattedDateinduction = $dateinduction->format('Y-m-d H:i:s');
    }

    $built = isset($_POST['built']) ? $_POST['built'] : null;
    $periodicity = isset($_POST['periodicity']) ? $_POST['periodicity'] : null;
    $Tareweight = is_numeric($_POST['Tareweight']) ? $_POST['Tareweight'] : null;
    $owningDivision = isset($_POST['owningDivision']) ? $_POST['owningDivision'] : null;
    $baseDepot = isset($_POST['baseDepot']) ? $_POST['baseDepot'] : null;
    $workshop = isset($_POST['workshop']) ? $_POST['workshop'] : null;
    $codalLife = isset($_POST['codalLife']) ? $_POST['codalLife'] : null;
    $powerGenerationType = isset($_POST['powerGenerationType']) ? $_POST['powerGenerationType'] : null;
    $couplingType = isset($_POST['couplingType']) ? $_POST['couplingType'] : null;
    $Seating = is_numeric($_POST['Seating']) ? $_POST['Seating'] : null;
    $Sleeping = is_numeric($_POST['Sleeping']) ? $_POST['Sleeping'] : null;

    // Check if coach details already exist
    $query_check = "SELECT COUNT(*) AS count FROM dbo.tbl_CoachMaster WHERE CoachID = ? AND CoachNo = ?";
    $params_check = array($coachID, $coachNo);
    $stmt_check = sqlsrv_query($conn, $query_check, $params_check);

    if ($stmt_check !== false) {
        $row = sqlsrv_fetch_array($stmt_check, SQLSRV_FETCH_ASSOC);
        $count = $row['count'];
        
        if ($count > 0) {
            // Coach details already exist
            echo json_encode(array('success' => false, 'message' => 'Coach details already exist.'));
        } else {
            // Insert new coach details
            $query_insert = "INSERT INTO dbo.tbl_CoachMaster (CoachID, CoachNo, OldCoachNo, Code, Type, Railway, VehicleType, Category, AC_Flag, BrakeSystem, BuiltDate, InductionDate, Built, Periodicity, TareWeight, OwningDivision, BaseDepot, Workshop, CodalLife, PowerGenerationType, CouplingType, SeatingCapacity, SleepingCapacity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $params_insert = array($coachID, $coachNo, $oldCoachNo, $code, $type, $railway, $vehicleType, $category, $AC_Flag, $brakesystem, $formattedDatebuilt, $formattedDateinduction, $built, $periodicity, $Tareweight, $owningDivision, $baseDepot, $workshop, $codalLife, $powerGenerationType, $couplingType, $Seating, $Sleeping);
            $stmt_insert = sqlsrv_query($conn, $query_insert, $params_insert);

            if ($stmt_insert !== false) {
                echo json_encode(array('success' => true, 'message' => 'New coach details added successfully.'));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Error adding new coach details: ' . print_r(sqlsrv_errors(), true)));
            }
        }
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error checking existing coach details: ' . print_r(sqlsrv_errors(), true)));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid request method.'));
}
?>