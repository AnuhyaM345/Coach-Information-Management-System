<?php
include '../../db.php';
if ($conn) {
    $description = $_POST['description'];
    // Query to retrieve Make based on the selected description
    $query = "SELECT DISTINCT(Make) FROM dbo.[tbl_CMS_Traceableitems_Details] WHERE Description = ?";
    $params = array($description);
    $result = sqlsrv_query($conn, $query, $params);

    if ($result !== false) {
        echo '<option value="" disabled selected hidden></option>';
        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            echo '<option value="'.$row['Make'].'">' . $row['Make'] .'</option>';
        }
    } else {
        echo '<option value="" selected hidden>Error fetching data</option>';
        die(print_r(sqlsrv_errors(), true));
    }
} else {
    echo '<option value="" selected hidden>Error connecting to database</option>';
}
?>
