<?php
include '../../db.php';

function getLines($conn, $shopCode) {
    $query = "SELECT Line FROM [CoachMaster_V_2018].[dbo].[tbl_CMS_ShopInformation] WHERE Shop_Code = ?";
    $params = array($shopCode);
    $result = sqlsrv_query($conn, $query, $params);
    $lines = array();
    if ($result !== false) {
        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $lines[] = $row['Line'];
        }
    }
    return $lines;
}

$shopCode = $_GET['shop'];
$lines = getLines($conn, $shopCode);
echo json_encode($lines);
?>