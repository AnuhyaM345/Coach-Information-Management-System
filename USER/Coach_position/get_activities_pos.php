<?php
include '../../db.php';

function getActivities($conn, $shopCode) {
    $query = "SELECT [activity] FROM [CoachMaster_V_2018].[dbo].[tbl_CMS_ActivityList] WHERE Shop = ?";
    $params = array($shopCode);
    $result = sqlsrv_query($conn, $query, $params);
    $activities = array();
    if ($result !== false) {
        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $activities[] = $row['activity'];
        }
    }
    return $activities;
}

if (isset($_GET['shop'])) {
    $shopCode = $_GET['shop'];
    $activities = getActivities($conn, $shopCode);
    echo json_encode($activities);
}
?>