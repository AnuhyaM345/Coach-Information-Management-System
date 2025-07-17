<?php
include '../../db.php'; // Ensure ../../db.php includes correct SQL Server connection setup

// Error handling
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Retrieve form data
$coachID = $_POST['coachID'];
$yardIN = !empty($_POST['yardIN']) ? $_POST['yardIN'] : NULL;
$condition = !empty($_POST['condition']) ? $_POST['condition'] : NULL;
$workshopIN = !empty($_POST['workshopIN']) ? $_POST['workshopIN'] : NULL;
$ncOffered = !empty($_POST['ncOffered']) ? $_POST['ncOffered'] : NULL;
$ncFit = !empty($_POST['ncFit']) ? $_POST['ncFit'] : NULL;
$returnDate = !empty($_POST['returnDate']) ? $_POST['returnDate'] : NULL;
$pohShop = !empty($_POST['pohShop']) ? $_POST['pohShop'] : NULL;
$repairType = !empty($_POST['repairType']) ? $_POST['repairType'] : NULL;
$workorderNumber = !empty($_POST['workorderNumber']) ? $_POST['workorderNumber'] : NULL;
$coachLocation = !empty($_POST['coach_location']) ? $_POST['coach_location'] : NULL;
$shopActivity = !empty($_POST['activity']) ? $_POST['activity'] : NULL;
$corrosion_Hrs = !empty($_POST['corrosion_Hrs']) ? $_POST['corrosion_Hrs'] : NULL;

$query = "SELECT [CoachID]
          FROM [CoachMaster_V_2018].[dbo].[tbl_TransactionalData]
          WHERE [CoachID] = ? AND [despatched_date] IS NULL";
$params = array($coachID);
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    throw new Exception(print_r(sqlsrv_errors(), true));
}

if (sqlsrv_has_rows($stmt)) {
    $alert = "The Coach has not despatched yet.";
    $response = array('success' => false, 'error' => $alert);
    echo json_encode($response);
    sqlsrv_close($conn); // Close connection
    exit;
}

// Function to format date or return NULL
function formatDateOrNull($date) {
    return !empty($date) ? (new DateTime($date))->format('Y-m-d H:i:s') : NULL;
}

// Function to format date to date only or return NULL
function formatDatetodateOrNull($date) {
    return !empty($date) ? (new DateTime($date))->format('Y-m-d') : NULL;
}
$errorMessages = [];

$workshopINPresent = !is_null($workshopIN);
$coachLocationPresent = !is_null($coachLocation);
$shopActivityPresent = !is_null($shopActivity);

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

// Start transaction
sqlsrv_begin_transaction($conn);

try {
    // Insert data into the first table (tbl_TransactionalData)
    $sql1 = "INSERT INTO dbo.tbl_TransactionalData (CoachID, yard_IN, condition, workshop_IN, NC_offered, NC_fit, POH_shop, repair_type, workorder_no, return_date, Corr_M_Hrs)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params1 = array(
        $coachID,
        formatDatetodateOrNull($yardIN),
        $condition,
        formatDatetodateOrNull($workshopIN),
        formatDatetodateOrNull($ncOffered),
        formatDatetodateOrNull($ncFit),
        $pohShop,
        $repairType,
        $workorderNumber,
        formatDatetodateOrNull($returnDate),
        $corrosion_Hrs // Pass corrosion_Hrs directly without formatting
    );

    $stmt1 = sqlsrv_query($conn, $sql1, $params1);

    if ($stmt1 === false) {
        throw new Exception(print_r(sqlsrv_errors(), true));
    }

    $alert = "";

    // Check if coach location and workshop are not NULL
    if ($coachLocation !== NULL && $workshopIN !== NULL) {
        // Fetch CoachNo
        $coachNoQuery = "SELECT [CoachNo] FROM [CoachMaster_V_2018].[dbo].[tbl_CoachMaster] WHERE CoachID = ?";
        $coachNoParams = array($coachID);
        $coachNoStmt = sqlsrv_query($conn, $coachNoQuery, $coachNoParams);
        
        if ($coachNoStmt === false) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }
        $coachNoRow = sqlsrv_fetch_array($coachNoStmt, SQLSRV_FETCH_ASSOC);
        if (!$coachNoRow) {
            throw new Exception("CoachNo not found for CoachID: $coachID");
        }
        $coachNo = $coachNoRow['CoachNo'];

        // Extract shop, line number, and location from coachLocation
        preg_match('/([A-Z]+)(\d+)_([\w]+)/', $coachLocation, $matches);
        if (count($matches) < 4) {
            throw new Exception("Invalid coachLocation format: $coachLocation");
        }
        $shop = $matches[1]; // Shop code (all alphabets until numbers start)
        $lineNumber = $matches[2]; // Line number (numbers until underscore)
        $newLocation = $matches[3]; // Location (everything after underscore)

        // Check if the location is already filled
        $checkQuery = "SELECT [CoachNo] FROM CoachMaster_V_2018.dbo.tbl_CoachPosition WHERE CoachLocation = ? AND SDateOut IS NULL";
        $checkParams = array($coachLocation);
        $checkStmt = sqlsrv_query($conn, $checkQuery, $checkParams);
        
        if ($checkStmt === false) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }
        if ($checkRow = sqlsrv_fetch_array($checkStmt, SQLSRV_FETCH_ASSOC)) {
            throw new Exception("Location already filled by CoachNo: " . $checkRow['CoachNo']);
        }

        // Fetch Shop_Name using Shop_Code
        $shopQuery = "SELECT Shop_Name FROM CoachMaster_V_2018.dbo.tbl_CMS_ShopInformation WHERE Shop_Code = ?";
        $shopParams = array($shop);
        $shopStmt = sqlsrv_query($conn, $shopQuery, $shopParams);
        
        if ($shopStmt === false) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }
        $shopName = sqlsrv_fetch_array($shopStmt, SQLSRV_FETCH_ASSOC)['Shop_Name'];

        // Construct CLocation
        $cLocation = "At: {$shopName}_Line {$lineNumber}_Location {$newLocation}";

        // Insert data into the second table (tbl_CoachPosition)
        $sql2 = "INSERT INTO CoachMaster_V_2018.dbo.tbl_CoachPosition
                (CoachNo, CoachLocation, Shop_Activity, SDateIn, SDateOut, WorkType, CLocation, RSID)
                VALUES (?, ?, ?, ?, NULL, NULL, ?, ?)";

        $params2 = array($coachNo, $coachLocation, $shopActivity, formatDateOrNull($workshopIN), $cLocation, $coachID);

        $stmt2 = sqlsrv_query($conn, $sql2, $params2);

        if ($stmt2 === false) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }
    } else {
        $coachNoQuery = "SELECT [CoachNo] FROM [CoachMaster_V_2018].[dbo].[tbl_CoachMaster] WHERE CoachID = ?";
        $coachNoParams = array($coachID);
        $coachNoStmt = sqlsrv_query($conn, $coachNoQuery, $coachNoParams);
        
        if ($coachNoStmt === false) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }
        $coachNoRow = sqlsrv_fetch_array($coachNoStmt, SQLSRV_FETCH_ASSOC);
        if (!$coachNoRow) {
            throw new Exception("CoachNo not found for CoachID: $coachID");
        }
        $coachNo = $coachNoRow['CoachNo'];

        $insertQuery = "INSERT INTO CoachMaster_V_2018.dbo.tbl_CoachPosition
            (CoachNo, CoachLocation, Shop_Activity, SDateIn, SDateOut, WorkType, CLocation, RSID)
            VALUES (?, NULL, NULL, NULL, NULL, NULL, NULL, ?)";
        $insertParams = array($coachNo, $coachID);
        $stmt3 = sqlsrv_query($conn, $insertQuery, $insertParams);
        
        if ($stmt3 === false) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }
        $alert = "Coach location not assigned or workshop date is missing. Only Transactional data has been saved.";
    }

    // If we've made it this far, commit the transaction
    sqlsrv_commit($conn);
    $response = array('success' => true, 'coach' => $_POST, 'alert' => $alert);
} catch (Exception $e) {
    // An error occurred, rollback the transaction
    sqlsrv_rollback($conn);
    $response = array('success' => false, 'error' => $e->getMessage());
}

echo json_encode($response);

sqlsrv_close($conn); // Close connection
?>