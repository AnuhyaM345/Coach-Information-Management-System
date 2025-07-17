<?php
include '../../db.php'; // Include the database connection
header('Content-Type: application/json');

// Get the JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (empty($data['coachID'])) {
    echo json_encode(['error' => 'No CoachID provided']);
    exit;
}

$coachID = $data['coachID'];

// Function to format date to 'Y-m-d' or return null
function formatDateOrNull($dateString) {
    return empty($dateString) ? null : date('Y-m-d', strtotime($dateString));
}

// Function to format datetime to 'Y-m-d H:i:s' or return null
function formatDateTimeOrNull($dateString) {
    return empty($dateString) ? null : date('Y-m-d H:i:s', strtotime($dateString));
}

// Format dates for tbl_TransactionalData (date only)
$yardIN = formatDateOrNull($data['yardIN']);
$workshopIN = formatDateOrNull($data['workshopIN']);
$ncOffered = formatDateOrNull($data['ncOffered']);
$ncFit = formatDateOrNull($data['ncFit']);
$despatchedDate = formatDateOrNull($data['despatchedDate']);
$returnDate = formatDateOrNull($data['returnDate']);

// Other fields (not dates)
$coach_location = empty($data['coachLocation']) ? null : $data['coachLocation'];
$activity = empty($data['shopActivity']) ? null : $data['shopActivity'];
$corrosion_Hrs = empty($data['corrosionHrs']) ? null : $data['corrosionHrs'];
$pohShop = empty($data['pohShop']) ? null : $data['pohShop'];
$repairType = empty($data['repairType']) ? null : $data['repairType'];
$workorderNumber = empty($data['workorderNumber']) ? null : $data['workorderNumber'];
$condition = empty($data['condition']) ? null : $data['condition'];

$errorMessages = [];

$workshopINPresent = !is_null($workshopIN);
$coachLocationPresent = !is_null($coach_location);
$shopActivityPresent = !is_null($activity);

$anyPresent = $workshopINPresent || $coachLocationPresent || $shopActivityPresent;
$anyNull = !$workshopINPresent || !$coachLocationPresent || !$shopActivityPresent;

if ($anyPresent && $anyNull) {
    if (!$workshopINPresent) {
        $errorMessages[] = 'Workshop IN is null';
    }
    if (!$coachLocationPresent) {
        $errorMessages[] = 'Coach Location is null';
    }
    if (!$shopActivityPresent) {
        $errorMessages[] = 'Shop Activity is null';
    }

    $alertMessage = implode(", ", $errorMessages);
    echo json_encode(['error' => $alertMessage]);
    exit();
}


$despatchedDatePresent = !is_null($despatchedDate);
$returnDatePresent = !is_null($returnDate);

$anyDatePresent = $despatchedDatePresent || $returnDatePresent;
$anyDateNull = !$despatchedDatePresent || !$returnDatePresent;

if ($anyDatePresent && $anyDateNull) {
    if (!$despatchedDatePresent) {
        $errorMessages[] = 'Despatched Date is null';
    }
    if (!$returnDatePresent) {
        $errorMessages[] = 'Return Date is null';
    }

    $alertMessage = implode(", ", $errorMessages);
    echo json_encode(['error' => $alertMessage]);
    exit();
}
$sql1 = "UPDATE dbo.tbl_TransactionalData
        SET yard_IN = ?,
            workshop_IN = ?,
            Corr_M_Hrs = ?,
            NC_offered = ?,
            NC_fit = ?,
            despatched_date = ?,
            POH_shop = ?,
            repair_type = ?,
            workorder_no = ?,
            return_date = ?,
            [condition] = ?
        WHERE CoachID = ? AND despatched_date IS NULL;";

$params1 = array($yardIN, $workshopIN, $corrosion_Hrs, $ncOffered, $ncFit, $despatchedDate, $pohShop, $repairType, $workorderNumber, $returnDate, $condition, $coachID);

$stmt1 = sqlsrv_query($conn, $sql1, $params1);

if ($stmt1 === false) {
    echo json_encode(['error' => 'Database update failed on the first query']);
    die(print_r(sqlsrv_errors(), true));
}

// Check if CoachPosition exists and workshopIN is not NULL
$sqlCheckCoachPosition = "SELECT [RSID] FROM [CoachMaster_V_2018].[dbo].[tbl_CoachPosition] WHERE RSID = ? AND SDateIn IS NULL";
$paramsCheckCoachPosition = array($coachID);
$stmtCheckCoachPosition = sqlsrv_query($conn, $sqlCheckCoachPosition, $paramsCheckCoachPosition);

if ($stmtCheckCoachPosition === false) {
    echo json_encode(['error' => 'Failed to check CoachPosition']);
    die(print_r(sqlsrv_errors(), true));
}

if (sqlsrv_has_rows($stmtCheckCoachPosition) && $workshopIN !== null) {
    if ($workshopIN === null || $coach_location === null || $activity === null) {
        echo json_encode(['error' => 'workshopIN, coachLocation, or activity is missing or null']);
        exit;
    }
    // Both conditions are met, proceed with the main update
    $sqlFetchCoachNo = "SELECT CoachNo FROM [CoachMaster_V_2018].[dbo].[tbl_CoachMaster] WHERE CoachID = ?";
    $paramsFetchCoachNo = array($coachID);
    $stmtFetchCoachNo = sqlsrv_query($conn, $sqlFetchCoachNo, $paramsFetchCoachNo);
    
    if ($stmtFetchCoachNo === false) {
        echo json_encode(['error' => 'Failed to fetch CoachNo']);
        die(print_r(sqlsrv_errors(), true));
    }
    
    $row = sqlsrv_fetch_array($stmtFetchCoachNo, SQLSRV_FETCH_ASSOC);
    if (!$row) {
        echo json_encode(['error' => 'CoachID not found']);
        die();
    }
    
    $coachNo = $row['CoachNo'];
    
    // Prepare CLocation if applicable
    $cLocation = null;
    if ($coach_location !== null && $workshopIN !== null) {
        // Extract shop, line number, and location from coachLocation
        preg_match('/([A-Z]+)(\d+)_([\w]+)/', $coach_location, $matches);
        if (count($matches) < 4) {
            echo json_encode(['error' => 'Invalid coachLocation format']);
            exit;
        }
        $shop = $matches[1]; // Shop code (all alphabets until numbers start)
        $lineNumber = $matches[2]; // Line number (numbers until underscore)
        $newLocation = $matches[3]; // Location (everything after underscore)
    
        // Check if the location is already filled
        $checkQuery = "SELECT [CoachNo] FROM [CoachMaster_V_2018].[dbo].[tbl_CoachPosition] WHERE CoachLocation = ? AND SDateOut IS NULL";
        $checkParams = array($coach_location);
        $checkStmt = sqlsrv_query($conn, $checkQuery, $checkParams);
        if ($checkStmt === false) {
            echo json_encode(['error' => 'Database query failed while checking location']);
            die(print_r(sqlsrv_errors(), true));
        }
        if ($checkRow = sqlsrv_fetch_array($checkStmt, SQLSRV_FETCH_ASSOC)) {
            echo json_encode(['error' => 'Location already filled by CoachNo: ' . $checkRow['CoachNo']]);
            exit;
        }
    
        // Fetch Shop_Name using Shop_Code
        $shopQuery = "SELECT Shop_Name FROM [CoachMaster_V_2018].[dbo].[tbl_CMS_ShopInformation] WHERE Shop_Code = ?";
        $shopParams = array($shop);
        $shopStmt = sqlsrv_query($conn, $shopQuery, $shopParams);
        if ($shopStmt === false) {
            echo json_encode(['error' => 'Failed to fetch shop name']);
            die(print_r(sqlsrv_errors(), true));
        }
        $shopName = sqlsrv_fetch_array($shopStmt, SQLSRV_FETCH_ASSOC)['Shop_Name'];
    
        // Construct CLocation
        $cLocation = "At: {$shopName}_Line {$lineNumber}_Location {$newLocation}";
    }
    
    // Update CoachPosition table
    $sql2 = "UPDATE [CoachMaster_V_2018].[dbo].[tbl_CoachPosition]
            SET [CoachLocation] = ?,
                [Shop_Activity] = ?,
                [SDateIn] = ?,
                [SdateOut] = ?,
                [WorkType] = ?,
                [CLocation] = ?
            WHERE CoachNo = ? AND SdateOut IS NULL;";
    
    $params2 = array($coach_location, $activity, formatDateTimeOrNull($data['workshopIN']), formatDateTimeOrNull($data['despatchedDate']), $repairType, $cLocation, $coachNo);
    
    $stmt2 = sqlsrv_query($conn, $sql2, $params2);
    
    if ($stmt2 === false) {
        echo json_encode(['error' => 'Database update failed on the second query']);
        die(print_r(sqlsrv_errors(), true));
    }
    
    echo json_encode(['success' => 'Data updated successfully']);
} else {
    // Condition not met, execute the alternative update
    $sql2 = "UPDATE [CoachMaster_V_2018].[dbo].[tbl_CoachPosition]
            SET [SdateOut] = ?
            WHERE RSID = ? AND SdateOut IS NULL;";
    
    $params2 = array(formatDateTimeOrNull($data['despatchedDate']), $coachID);
    
    $stmt2 = sqlsrv_query($conn, $sql2, $params2);
    
    if ($stmt2 === false) {
        echo json_encode(['error' => 'Database update failed on the second query']);
        die(print_r(sqlsrv_errors(), true));
    }
    
    echo json_encode(['success' => 'Data updated successfully (SdateOut updated)']);
}

// Free statements and close connection
if (isset($stmtCheckCoachPosition)) {
    sqlsrv_free_stmt($stmtCheckCoachPosition);
}
if (isset($stmtFetchCoachNo)) {
    sqlsrv_free_stmt($stmtFetchCoachNo);
}
sqlsrv_close($conn);
?>