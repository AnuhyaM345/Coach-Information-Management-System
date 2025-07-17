<?php
include '../../db.php';

if ($conn) {
    $coachID = $_POST['coachID'];

    $query = "SELECT [workshop_IN], [Corr_M_Hrs] FROM [CoachMaster_V_2018].[dbo].[tbl_TransactionalData] WHERE CoachID = ? AND [despatched_date] IS NULL";

    $params = array($coachID);
    $result = sqlsrv_query($conn, $query, $params);

    if ($result !== false) {
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

        if ($row) {
            // Convert workshop_IN to a date string if it is not null
            if ($row['workshop_IN'] !== null) {
                $workshopInDateTime = $row['workshop_IN'];
                $row['workshop_IN'] = $workshopInDateTime->format('Y-m-d');
            }

            echo json_encode($row);
        } else {
            echo json_encode(['error' => 'No data found']);
        }
    } else {
        echo json_encode(['error' => 'Error fetching data']);
    }
} else {
    echo json_encode(['error' => 'Error connecting to database']);
}
?>
