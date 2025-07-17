<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $category = $_GET['category'];
    $corrosionHrs = $_GET['corrosionHrs'];

    include '../../db.php'; // Include your database connection file here

    global $conn;

    if (!$conn) {
        echo json_encode(array("success" => false, "message" => "Connection failed: " . print_r(sqlsrv_errors(), true)));
        exit;
    }

    // Fetch P_Insp_IN and L_L_IN from progress_1 table based on category and corrosionHrs
    $sql = "SELECT P_Insp_IN, L_L_IN, Corr_IN, Paint_IN, Berth_IN, W_Tank_IN, Carriage_IN, ETL_AC_IN, Air_Br_IN
    FROM tbl_CMS_Progress_1
    WHERE category = ? AND Corr_Hrs = ?";
$params = array($category, $corrosionHrs);

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        echo json_encode(array("success" => false, "message" => "Query failed: " . print_r(sqlsrv_errors(), true)));
        exit;
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($row) {
        $P_Insp_IN = intval($row['P_Insp_IN']);
        $L_L_IN = intval($row['L_L_IN']);
        $Corr_IN = intval($row['Corr_IN']);
        $Paint_IN = intval($row['Paint_IN']);
        $Berth_IN = intval($row['Berth_IN']);
        $W_Tank_IN = intval($row['W_Tank_IN']);
        $Carriage_IN = intval($row['Carriage_IN']);
        $ETL_AC_IN = intval($row['ETL_AC_IN']);
        $Air_Br_IN = intval($row['Air_Br_IN']);

        echo json_encode(array("success" => true, "P_Insp_IN" => $P_Insp_IN, "L_L_IN" => $L_L_IN,"Corr_IN"=>$Corr_IN,"Paint_IN"=> $Paint_IN,"Berth_IN"=>$Berth_IN,"W_Tank_IN"=> $W_Tank_IN,"Carriage_IN"=>$Carriage_IN,"ETL_AC_IN"=>$ETL_AC_IN,"Air_Br_IN"=> $Air_Br_IN));
    } else {
        echo json_encode(array("success" => false, "message" => "No matching record found in tbl_CMS_Progress_1 table."));
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
}
?>