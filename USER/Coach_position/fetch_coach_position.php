<?php
include '../../db.php'; // Include your database connection file

error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
error_log("POST data: " . print_r($_POST, true));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['coachNumber'])) {
    $coachNumber = $_POST['coachNumber'];
    
    // Log the received coach number
    error_log("Received coach number: " . $coachNumber);

    $query = "SELECT [Shop_Activity], [SDateIn], [SdateOut], [WorkType], [CLocation]
              FROM [CoachMaster_V_2018].[dbo].[tbl_CoachPosition]
              WHERE [CoachNo]= ?
              ORDER BY [SDateIn] DESC";

    $params = array($coachNumber);
    $stmt = sqlsrv_prepare($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_execute($stmt) === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $coachPositionData = [];

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        // Format dates for datetime-local input
        $row['SDateIn'] = $row['SDateIn'] ? $row['SDateIn']->format('Y-m-d   H:i') : null;
        $row['SdateOut'] = $row['SdateOut'] ? $row['SdateOut']->format('Y-m-d   H:i') : null;

        // Calculate shifting time
        $shiftingTime = '';
        if ($row['SDateIn'] && $row['SdateOut']) {
            $dateIn = new DateTime($row['SDateIn']);
            $dateOut = new DateTime($row['SdateOut']);
            $interval = $dateIn->diff($dateOut);
            $shiftingTime = $interval->format('%a day' . ($interval->d > 1 ? 's' : '') . ' %h hr' . ($interval->h > 1 ? 's' : '') . ' %i min' . ($interval->i > 1 ? 's' : ''));
        }

        $row['ShiftingTime'] = $shiftingTime;

        $coachPositionData[] = $row;
    }

    echo json_encode(['success' => true, 'coachPositionData' => $coachPositionData]);
} else {
    error_log("Invalid request or missing coach number");
    echo json_encode(['success' => false, 'message' => 'Invalid request or missing coach number']);
}
?>
