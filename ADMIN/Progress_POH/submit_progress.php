<?php
include '../../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $CoachNo = $_POST['coachNo'] ?? NULL;
    $category = $_POST['category'] ?? NULL;
    $code = $_POST['code'] ?? NULL;
    // $workshop_in = $_POST['workshop_in'] ?? NULL;
    $transactional_corr_m_hrs = $_POST['transactional_corr_m_hrs'] ?? NULL;

    // Additional fields
    function formatDate($date, $confirmed) {
        if ($date && $confirmed === 'true') {
            $dateObj = new DateTime($date);
            return $dateObj->format('Y-m-d H:i:s');
        }
        return NULL;
    }

    $workshop_in = isset($_POST['$transactional_workshop_in']) ? formatDate($_POST['$transactional_workshop_in']) : NULL;
    $P_Insp_IN = isset($_POST['P_Insp_IN']) ? formatDate($_POST['P_Insp_IN'], $_POST['P_Insp_IN_confirmed']) : NULL;
    $P_Insp_PDC = $_POST['P_Insp_PDC'] ?? NULL;
    $P_Insp_OUT = isset($_POST['P_Insp_OUT']) ? formatDate($_POST['P_Insp_OUT'], $_POST['P_Insp_OUT_confirmed']) : NULL;
    $L_L_IN = isset($_POST['L_L_IN']) ? formatDate($_POST['L_L_IN'], $_POST['L_L_IN_confirmed']) : NULL;
    $L_L_PDC = $_POST['L_L_PDC'] ?? NULL;
    $L_L_OUT = isset($_POST['L_L_OUT']) ? formatDate($_POST['L_L_OUT'], $_POST['L_L_OUT_confirmed']) : NULL;
    $Corrosion_IN = isset($_POST['Corr_IN']) ? formatDate($_POST['Corr_IN'], $_POST['Corr_IN_confirmed']) : NULL;
    $Corrosion_PDC = $_POST['Corr_PDC'] ?? NULL;
    $Corrosion_OUT = isset($_POST['Corr_OUT']) ? formatDate($_POST['Corr_OUT'], $_POST['Corr_OUT_confirmed']) : NULL;
    $Paint_IN = isset($_POST['Paint_IN']) ? formatDate($_POST['Paint_IN'], $_POST['Paint_IN_confirmed']) : NULL;
    $Paint_PDC = $_POST['Paint_PDC'] ?? NULL;
    $Paint_OUT = isset($_POST['Paint_OUT']) ? formatDate($_POST['Paint_OUT'], $_POST['Paint_OUT_confirmed']) : NULL;
    $Berth_IN = isset($_POST['Berth_IN']) ? formatDate($_POST['Berth_IN'], $_POST['Berth_IN_confirmed']) : NULL;
    $Berth_PDC = $_POST['Berth_PDC'] ?? NULL;
    $Berth_OUT = isset($_POST['Berth_OUT']) ? formatDate($_POST['Berth_OUT'], $_POST['Berth_OUT_confirmed']) : NULL;
    $W_Tank_IN = isset($_POST['W_Tank_IN']) ? formatDate($_POST['W_Tank_IN'], $_POST['W_Tank_IN_confirmed']) : NULL;
    $W_Tank_PDC = $_POST['W_Tank_PDC'] ?? NULL;
    $W_Tank_OUT = isset($_POST['W_Tank_OUT']) ? formatDate($_POST['W_Tank_OUT'], $_POST['W_Tank_OUT_confirmed']) : NULL;
    $Carriage_IN = isset($_POST['Carriage_IN']) ? formatDate($_POST['Carriage_IN'], $_POST['Carriage_IN_confirmed']) : NULL;
    $Carriage_PDC = $_POST['Carriage_PDC'] ?? NULL;
    $Carriage_OUT = isset($_POST['Carriage_OUT']) ? formatDate($_POST['Carriage_OUT'], $_POST['Carriage_OUT_confirmed']) : NULL;
    $ETL_IN = isset($_POST['ETL_AC_IN']) ? formatDate($_POST['ETL_AC_IN'], $_POST['ETL_AC_IN_confirmed']) : NULL;
    $ETL_PDC = $_POST['ETL_AC_PDC'] ?? NULL;
    $ETL_OUT = isset($_POST['ETL_AC_OUT']) ? formatDate($_POST['ETL_AC_OUT'], $_POST['ETL_AC_OUT_confirmed']) : NULL;
    $Air_Br_IN = isset($_POST['Air_Br_IN']) ? formatDate($_POST['Air_Br_IN'], $_POST['Air_Br_IN_confirmed']) : NULL;
    $Air_Br_PDC = $_POST['Air_Br_PDC'] ?? NULL;
    $Air_Br_OUT = isset($_POST['Air_Br_OUT']) ? formatDate($_POST['Air_Br_OUT'], $_POST['Air_Br_OUT_confirmed']) : NULL;
    $NTXR_IN = isset($_POST['NTXR_IN']) ? formatDate($_POST['NTXR_IN'], $_POST['NTXR_IN_confirmed']) : NULL;
    $NTXR_OUT = isset($_POST['NTXR_OUT']) ? formatDate($_POST['NTXR_OUT'], $_POST['NTXR_OUT_confirmed']) : NULL;

    if (!$conn) {
        die(json_encode(array("success" => false, "message" => "Connection failed: " . print_r(sqlsrv_errors(), true))));
    }

    // Query to get CoachID from tbl_CoachMaster
    $sql_coach_master = "SELECT CoachID FROM [dbo].[tbl_CoachMaster] WHERE CoachNo = ?";
    $params_coach_master = array($CoachNo);
    $stmt_coach_master = sqlsrv_query($conn, $sql_coach_master, $params_coach_master);

    if ($stmt_coach_master === false) {
        die(json_encode(array("success" => false, "message" => "Query failed: " . print_r(sqlsrv_errors(), true))));
    }

    $row_coach_master = sqlsrv_fetch_array($stmt_coach_master, SQLSRV_FETCH_ASSOC);
    if (!$row_coach_master) {
        die(json_encode(array("success" => false, "message" => "No data found in CoachMaster for CoachNo: $CoachNo")));
    }

    $CoachID = $row_coach_master['CoachID'];

    // Query to get workshop_IN and Corr_M_Hrs from tbl_TransactionalData
    $query_transactional = "SELECT [workshop_IN], [Corr_M_Hrs] FROM [CoachMaster_V_2018].[dbo].[tbl_TransactionalData] WHERE CoachID = ? AND [despatched_date] IS NULL";
    $params_transactional = array($CoachID);
    $stmt_transactional = sqlsrv_query($conn, $query_transactional, $params_transactional);

    if ($stmt_transactional === false) {
        die(json_encode(array("success" => false, "message" => "Query failed: " . print_r(sqlsrv_errors(), true))));
    }

    $row_transactional = sqlsrv_fetch_array($stmt_transactional, SQLSRV_FETCH_ASSOC);

    // Fetch the workshop_IN and Corr_M_Hrs values
    $transactional_workshop_in = $row_transactional['workshop_IN'] ? $row_transactional['workshop_IN']->format('Y-m-d') : NULL;
    $transactional_corr_m_hrs = $row_transactional['Corr_M_Hrs'] ?? NULL;

    // Query to get MaintenanceID from tbl_TransactionalData
    $sql_transactional = "SELECT MaintenanceID FROM [dbo].[tbl_TransactionalData] WHERE CoachID = ? AND despatched_date IS NULL";
    $params_transactional = array($CoachID);
    $stmt_transactional = sqlsrv_query($conn, $sql_transactional, $params_transactional);

    if ($stmt_transactional === false) {
        die(json_encode(array("success" => false, "message" => "Query failed: " . print_r(sqlsrv_errors(), true))));
    }

    $row_transactional = sqlsrv_fetch_array($stmt_transactional, SQLSRV_FETCH_ASSOC);
    if (!$row_transactional) {
        die(json_encode(array("success" => false, "message" => "No data found in tbl_TransactionalData for CoachID: $CoachID with despatchedDate as null")));
    }

    $MaintenanceID = $row_transactional['MaintenanceID'];

    // Check if the row already exists in tbl_CMS_POH
    $sql_check = "SELECT COUNT(*) AS count FROM tbl_CMS_POH WHERE M_ID = ?";
    $params_check = array($MaintenanceID);
    $stmt_check = sqlsrv_query($conn, $sql_check, $params_check);

    if ($stmt_check === false) {
        die(json_encode(array("success" => false, "message" => "Check query failed: " . print_r(sqlsrv_errors(), true))));
    }

    $row_check = sqlsrv_fetch_array($stmt_check, SQLSRV_FETCH_ASSOC);
    $exists = $row_check['count'] > 0;

    if ($exists) {
        // Update the existing row
        $sql_update = "UPDATE tbl_CMS_POH SET
            Category = ?,
            Code = ?,
            workshop_IN = ?,
            Corr_Hrs = ?,
            P_Insp_IN = ?,
            P_Insp_OUT = ?,
            L_L_IN = ?,
            L_L_OUT = ?,
            Corr_IN = ?,
            Corr_OUT = ?,
            Paint_IN = ?,
            Paint_OUT = ?,
            Berth_IN = ?,
            Berth_OUT = ?,
            W_Tank_IN = ?,
            W_Tank_OUT = ?,
            Carriage_IN = ?,
            Carriage_OUT = ?,
            ETL_AC_IN = ?,
            ETL_AC_OUT = ?,
            Air_Br_IN = ?,
            Air_Br_OUT = ?,
            NTXR_IN = ?,
            NTXR_OUT = ?
            WHERE M_ID = ?";

        $params_update = array(
            $category === '' ? NULL : $category,
            $code === '' ? NULL : $code,
            $transactional_workshop_in === '' ? NULL : $transactional_workshop_in,
            $transactional_corr_m_hrs === '' ? NULL : $transactional_corr_m_hrs,
            $P_Insp_IN === '' ? NULL : $P_Insp_IN,
            $P_Insp_OUT === '' ? NULL : $P_Insp_OUT,
            $L_L_IN === '' ? NULL : $L_L_IN,
            $L_L_OUT === '' ? NULL : $L_L_OUT,
            $Corrosion_IN === '' ? NULL : $Corrosion_IN,
            $Corrosion_OUT === '' ? NULL : $Corrosion_OUT,
            $Paint_IN === '' ? NULL : $Paint_IN,
            $Paint_OUT === '' ? NULL : $Paint_OUT,
            $Berth_IN === '' ? NULL : $Berth_IN,
            $Berth_OUT === '' ? NULL : $Berth_OUT,
            $W_Tank_IN === '' ? NULL : $W_Tank_IN,
            $W_Tank_OUT === '' ? NULL : $W_Tank_OUT,
            $Carriage_IN === '' ? NULL : $Carriage_IN,
            $Carriage_OUT === '' ? NULL : $Carriage_OUT,
            $ETL_IN === '' ? NULL : $ETL_IN,
            $ETL_OUT === '' ? NULL : $ETL_OUT,
            $Air_Br_IN === '' ? NULL : $Air_Br_IN,
            $Air_Br_OUT === '' ? NULL : $Air_Br_OUT,
            $NTXR_IN === '' ? NULL : $NTXR_IN,
            $NTXR_OUT === '' ? NULL : $NTXR_OUT,
            $MaintenanceID === '' ? NULL : $MaintenanceID
        );

        $stmt_update = sqlsrv_query($conn, $sql_update, $params_update);

        if ($stmt_update === false) {
            die(json_encode(array("success" => false, "message" => "Update failed: " . print_r(sqlsrv_errors(), true))));
        } else {
            echo json_encode(array("success" => true, "message" => "Data updated in POH table successfully."));
        }

        sqlsrv_free_stmt($stmt_update);
    } else {
        // Insert a new row
        $sql_insert = "INSERT INTO tbl_CMS_POH (M_ID, CoachNo, Category, Code, workshop_IN, Corr_Hrs, P_Insp_IN, P_Insp_PDC, P_Insp_OUT, L_L_IN, L_L_PDC, L_L_OUT, 
        Corr_IN, Corr_PDC, Corr_OUT, Paint_IN, Paint_PDC, Paint_OUT, Berth_IN, Berth_PDC, Berth_OUT, W_Tank_IN, W_Tank_PDC, W_Tank_OUT, Carriage_IN, Carriage_PDC, 
        Carriage_OUT, ETL_AC_IN, ETL_AC_PDC, ETL_AC_OUT, Air_Br_IN, Air_Br_PDC, Air_Br_OUT, NTXR_IN, NTXR_OUT)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $params_insert = array(
            $MaintenanceID === '' ? NULL : $MaintenanceID,
            $CoachNo === '' ? NULL : $CoachNo,
            $category === '' ? NULL : $category,
            $code === '' ? NULL : $code,
            $transactional_workshop_in === '' ? NULL : $transactional_workshop_in,
            $transactional_corr_m_hrs === '' ? NULL : $transactional_corr_m_hrs,
            $P_Insp_IN === '' ? NULL : $P_Insp_IN,
            $P_Insp_PDC === '' ? NULL : $P_Insp_PDC,
            $P_Insp_OUT === '' ? NULL : $P_Insp_OUT,
            $L_L_IN === '' ? NULL : $L_L_IN,
            $L_L_PDC === '' ? NULL : $L_L_PDC,
            $L_L_OUT === '' ? NULL : $L_L_OUT,
            $Corrosion_IN === '' ? NULL : $Corrosion_IN,
            $Corrosion_PDC === '' ? NULL : $Corrosion_PDC,
            $Corrosion_OUT === '' ? NULL : $Corrosion_OUT,
            $Paint_IN === '' ? NULL : $Paint_IN,
            $Paint_PDC === '' ? NULL : $Paint_PDC,
            $Paint_OUT === '' ? NULL : $Paint_OUT,
            $Berth_IN === '' ? NULL : $Berth_IN,
            $Berth_PDC === '' ? NULL : $Berth_PDC,
            $Berth_OUT === '' ? NULL : $Berth_OUT,
            $W_Tank_IN === '' ? NULL : $W_Tank_IN,
            $W_Tank_PDC === '' ? NULL : $W_Tank_PDC,
            $W_Tank_OUT === '' ? NULL : $W_Tank_OUT,
            $Carriage_IN === '' ? NULL : $Carriage_IN,
            $Carriage_PDC === '' ? NULL : $Carriage_PDC,
            $Carriage_OUT === '' ? NULL : $Carriage_OUT,
            $ETL_IN === '' ? NULL : $ETL_IN,
            $ETL_PDC === '' ? NULL : $ETL_PDC,
            $ETL_OUT === '' ? NULL : $ETL_OUT,
            $Air_Br_IN === '' ? NULL : $Air_Br_IN,
            $Air_Br_PDC === '' ? NULL : $Air_Br_PDC,
            $Air_Br_OUT === '' ? NULL : $Air_Br_OUT,
            $NTXR_IN === '' ? NULL : $NTXR_IN,
            $NTXR_OUT === '' ? NULL : $NTXR_OUT
        );

        $stmt_insert = sqlsrv_query($conn, $sql_insert, $params_insert);

        if ($stmt_insert === false) {
            die(json_encode(array("success" => false, "message" => "Insert failed: " . print_r(sqlsrv_errors(), true))));
        } else {
            echo json_encode(array("success" => true, "message" => "Data inserted into POH table successfully."));
        }

        sqlsrv_free_stmt($stmt_insert);
    }

    sqlsrv_free_stmt($stmt_check);
    sqlsrv_free_stmt($stmt_coach_master);
    sqlsrv_free_stmt($stmt_transactional);
}
?>
