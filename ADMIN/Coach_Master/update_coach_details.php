<?php
include '../../db.php';

header('Content-Type: application/json'); // Set content type to JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve POST data
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
    // Prepare and execute the SQL update query
    $query = "UPDATE dbo.tbl_CoachMaster
              SET OldCoachNo = ?, Code = ?, Type = ?, Railway = ?, VehicleType = ?, Category = ?, AC_Flag = ?, BrakeSystem = ?, BuiltDate = ?, InductionDate = ?, Built = ?, Periodicity = ?, TareWeight = ?, OwningDivision = ?, BaseDepot = ?, Workshop = ?, CodalLife = ?, PowerGenerationType = ?, CouplingType = ?, SeatingCapacity = ?, SleepingCapacity = ?
              WHERE CoachNo = ?";
    $params = array($oldCoachNo, $code, $type, $railway, $vehicleType, $category, $AC_Flag, $brakesystem, $formattedDatebuilt, $formattedDateinduction, $built, $periodicity, $Tareweight, $owningDivision, $baseDepot, $workshop, $codalLife, $powerGenerationType, $couplingType, $Seating, $Sleeping, $coachNo);

    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt !== false) {
        echo json_encode(array('success' => true, 'message' => 'Coach details updated successfully.'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error updating coach details: ' . print_r(sqlsrv_errors(), true)));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid request method.'));
}
?>