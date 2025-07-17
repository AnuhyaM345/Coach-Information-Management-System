<?php
include '../../db.php';

if (isset($_GET['coachID'])) {
    $coachID = $_GET['coachID'];

    // First query to fetch coach location and shop activity
    $query1 = "SELECT
                [CoachLocation],
                [Shop_Activity]
              FROM [CoachMaster_V_2018].[dbo].[tbl_CoachPosition]
              WHERE RSID = ? AND SdateOut IS NULL";
    $params1 = array($coachID);
    $stmt1 = sqlsrv_query($conn, $query1, $params1);

    // Second query to fetch transactional data
    $query2 = "SELECT
                [CoachID],
                [yard_IN],
                [condition],
                [workshop_IN],
                [NC_offered],
                [NC_fit],
                [despatched_date],
                [POH_shop],
                [repair_type],
                [workorder_no],
                [return_date],
                [Corr_M_Hrs]
              FROM [CoachMaster_V_2018].[dbo].[tbl_TransactionalData]
              WHERE CoachID = ? AND despatched_date IS NULL";
    $params2 = array($coachID);
    $stmt2 = sqlsrv_query($conn, $query2, $params2);

    // Initialize response data array
    $data = [];

    // Process first query results
    if ($stmt1 !== false && sqlsrv_has_rows($stmt1)) {
        $data1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC);
        $data['CoachLocation'] = $data1['CoachLocation'];
        $data['Shop_Activity'] = $data1['Shop_Activity'];
    }

    // Process second query results
    if ($stmt2 !== false && sqlsrv_has_rows($stmt2)) {
        $data2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
        $data = array_merge($data, $data2);
    }

    if (!empty($data)) {
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No data found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No CoachID provided']);
}
?>
