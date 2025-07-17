<?php
include '../../db.php';

function getCount($conn, $shopCode, $line) {
    $query = "SELECT Count FROM [CoachMaster_V_2018].[dbo].[tbl_CMS_ShopInformation] WHERE Shop_Code = ? AND Line = ?";
    $params = array($shopCode, $line);
    $result = sqlsrv_query($conn, $query, $params);
    if ($result !== false) {
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        return $row['Count'];
    }
    return 0;
}

$shopCode = $_GET['shop'];
$line = $_GET['line'];
$count = getCount($conn, $shopCode, $line);
echo $count;
?>