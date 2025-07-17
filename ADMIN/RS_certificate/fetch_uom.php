<?php
include '../../db.php';

if ($conn) {
    if (isset($_POST['description'])) {
        $description = $_POST['description'];

        // Query to retrieve UOM based on the selected description
        $query = "SELECT DISTINCT(UOM) FROM dbo.[tbl_CMS_Traceableitems_Details] WHERE Description = ?";
        $params = array($description);
        $result = sqlsrv_query($conn, $query, $params);

        if ($result !== false) {
            echo '<option value="" disabled selected hidden></option>';
            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                echo '<option value="'.$row['UOM'].'">' . $row['UOM'] .'</option>';
            }
        } else {
            echo '<option value="" selected hidden>Error fetching data</option>';
            die(print_r(sqlsrv_errors(), true));
        }
    } else {
        echo '<option value="" selected hidden>Invalid request</option>';
    }
} else {
    echo '<option value="" selected hidden>Error connecting to database</option>';
}
?>
