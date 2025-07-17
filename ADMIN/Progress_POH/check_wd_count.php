<?php
if (isset($_GET['date'])) {
    $date = $_GET['date'];

    include '../../db.php'; // Include your database connection file here

    global $conn;

    if (!$conn) {
        die(json_encode(array("success" => false, "message" => "Connection failed: " . print_r(sqlsrv_errors(), true))));
    }

    $sql = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
    $params = array($date);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(json_encode(array("success" => false, "message" => "Query failed: " . print_r(sqlsrv_errors(), true))));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($row) {
        $data = array(
            "success" => true,
            "WD_count" => (float)$row['WD_count']
        );
        echo json_encode($data);
    } else {
        echo json_encode(array("success" => false, "message" => "No data found in tbl_CMS_Master_WD_CD_Dates for date: " . htmlspecialchars($date)));
    }

    sqlsrv_free_stmt($stmt);
}
?>