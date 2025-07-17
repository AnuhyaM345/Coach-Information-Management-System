<?php
// Include your database connection file
include '../../db.php';

// Function to fetch activities for a given shop
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

// Get the shop code from the request
$shopCode = isset($_GET['shop']) ? $_GET['shop'] : '';

// Fetch activities for the given shop code
$activities = getActivities($conn, $shopCode);

// Return the activities as a JSON response
header('Content-Type: application/json');
echo json_encode($activities);
?>
