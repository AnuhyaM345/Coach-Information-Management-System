<?php
session_start();

require('../../FPDF/fpdf.php'); // Correct path to FPDF library
require('../../db.php'); // Include your existing database connection file

$coachNo = isset($_GET['coachNo']) ? $_GET['coachNo'] : '';

if (empty($coachNo)) {
    echo "No Coach No provided.";
    exit;
}

// Fetch CoachID
$sqlCoachID = "SELECT CoachID FROM tbl_CoachMaster WHERE CoachNo = ?";
$paramsCoachID = array($coachNo);
$stmtCoachID = sqlsrv_query($conn, $sqlCoachID, $paramsCoachID);

if ($stmtCoachID === false) {
    die("Error in SQL query for CoachID: " . print_r(sqlsrv_errors(), true));
}

$coachIDData = sqlsrv_fetch_array($stmtCoachID, SQLSRV_FETCH_ASSOC);
$coachID = $coachIDData['CoachID'];

// Fetch other coach details
$sql = "SELECT CoachNo, Code, Railway, Type, Category, AC_Flag, BaseDepot, OwningDivision,
               InductionDate, CouplingType, BrakeSystem, ToiletSystem, US_RM, PowerGenerationType,
               CodalLife, Periodicity, Built, BuiltDate, VehicleType 
        FROM tbl_CoachMaster 
        WHERE CoachNo = ?";
$params = array($coachNo);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die("Error in SQL query: " . print_r(sqlsrv_errors(), true));
}

$data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// Fetch the latest MaintenanceID
$sqlLatestMID = "SELECT TOP 1 MaintenanceID FROM tbl_TransactionalData WHERE CoachID = ? ORDER BY MaintenanceID DESC";
$paramsLatestMID = array($coachID);
$stmtLatestMID = sqlsrv_query($conn, $sqlLatestMID, $paramsLatestMID);

if ($stmtLatestMID === false) {
    die("Error in SQL query for latest MaintenanceID: " . print_r(sqlsrv_errors(), true));
}

$latestMIDData = sqlsrv_fetch_array($stmtLatestMID, SQLSRV_FETCH_ASSOC);
$latestMID = $latestMIDData['MaintenanceID']??'';

// Fetch B1No and B2No based on latest MaintenanceID
$sqlBogieNos = "SELECT B1No,B2No,B1A1,B1A2,B2A1,B2A2,
                B1A1D1,B1A1D2,B1A2D1,B1A2D2,B2A1D1,B2A1D2,B2A2D1,B2A2D2,
                B1A1D1D,B1A1D2D,B1A2D1D,B1A2D2D,B2A1D1D,B2A1D2D,B2A2D1D,B2A2D2D,
                B1A1RB1,B1A1RB2,B1A2RB1,B1A2RB2,B2A1RB1,B2A1RB2,B2A2RB1,B2A2RB2,
                B1A1D1RBMake,B1A1D2RBMake,B1A2D1RBMake,B1A2D2RBMake,B2A1D1RBMake,B2A1D2RBMake,B2A2D1RBMake,B2A2D2RBMake,
                B1A1D1RBType,B1A1D2RBType,B1A2D1RBType,B1A2D2RBType,B2A1D1RBType,B2A1D2RBType,B2A2D1RBType,B2A2D2RBType
                 FROM tbl_CMS_Coach_under_Gear_Details WHERE MID = ?";
$paramsBogieNos = array($latestMID);
$stmtBogieNos = sqlsrv_query($conn, $sqlBogieNos, $paramsBogieNos);

if ($stmtBogieNos === false) {
    die("Error in SQL query for B1No and B2No: " . print_r(sqlsrv_errors(), true));
}

$bogieNosData = sqlsrv_fetch_array($stmtBogieNos, SQLSRV_FETCH_ASSOC);
$bogieNo1 = $bogieNosData['B1No']??'';
$bogieNo2 = $bogieNosData['B2No']??'';
$axleNo11 = $bogieNosData['B1A1']??'';
$axleNo12 = $bogieNosData['B1A2']??'';
$axleNo21 = $bogieNosData['B2A1']??'';
$axleNo22 = $bogieNosData['B2A2']??'';
$diaNo11 = $bogieNosData['B1A1D1']??'';
$diaNo12 = $bogieNosData['B1A1D2']??'';
$diaNo13 = $bogieNosData['B1A2D1']??'';
$diaNo14 = $bogieNosData['B1A2D2']??'';
$diaNo21 = $bogieNosData['B2A1D1']??'';
$diaNo22 = $bogieNosData['B2A1D2']??'';
$diaNo23 = $bogieNosData['B2A2D1']??'';
$diaNo24 = $bogieNosData['B2A2D2']??'';
$discNo11 = $bogieNosData['B1A1D1D']??'';
$discNo12 = $bogieNosData['B1A1D2D']??'';
$discNo13 = $bogieNosData['B1A2D1D']??'';
$discNo14 = $bogieNosData['B1A2D2D']??'';
$discNo21 = $bogieNosData['B2A1D1D']??'';
$discNo22 = $bogieNosData['B2A1D2D']??'';
$discNo23 = $bogieNosData['B2A2D1D']??'';
$discNo24 = $bogieNosData['B2A2D2D']??'';
$rbNo11 = $bogieNosData['B1A1RB1']??'';
$rbNo12 = $bogieNosData['B1A1RB2']??'';
$rbNo13 = $bogieNosData['B1A2RB1']??'';
$rbNo14 = $bogieNosData['B1A2RB2']??'';
$rbNo21 = $bogieNosData['B2A1RB1']??'';
$rbNo22 = $bogieNosData['B2A1RB2']??'';
$rbNo23 = $bogieNosData['B2A2RB1']??'';
$rbNo24 = $bogieNosData['B2A2RB2']??'';
$rbmNo11 = $bogieNosData['B1A1D1RBMake']??'';
$rbmNo12 = $bogieNosData['B1A1D2RBMake']??'';
$rbmNo13 = $bogieNosData['B1A2D1RBMake']??'';
$rbmNo14 = $bogieNosData['B1A2D2RBMake']??'';
$rbmNo21 = $bogieNosData['B2A1D1RBMake']??'';
$rbmNo22 = $bogieNosData['B2A1D2RBMake']??'';
$rbmNo23 = $bogieNosData['B2A2D1RBMake']??'';
$rbmNo24 = $bogieNosData['B2A2D2RBMake']??'';
$rbtNo11 = $bogieNosData['B1A1D1RBType']??'';
$rbtNo12 = $bogieNosData['B1A1D2RBType']??'';
$rbtNo13 = $bogieNosData['B1A2D1RBType']??'';
$rbtNo14 = $bogieNosData['B1A2D2RBType']??'';
$rbtNo21 = $bogieNosData['B2A1D1RBType']??'';
$rbtNo22 = $bogieNosData['B2A1D2RBType']??'';
$rbtNo23 = $bogieNosData['B2A2D1RBType']??'';
$rbtNo24 = $bogieNosData['B2A2D2RBType']??'';
// Create PDF

$pdf = new FPDF();
$pdf->AddPage();
$pdf->Image('../../SCR_Logo.png', 10, 10, 30); 
$pdf->SetFont('Arial', 'B', 12);

$pdf->Cell(0, 8, 'Ministry of Railways', 0, 1, 'C');
$pdf->Cell(0, 8, 'Periodical Overhauling Certificate', 0, 1, 'C');
$pdf->Cell(0, 8, 'Carriage Workshop,Lallaguda', 0, 1, 'C');
$pdf->Cell(0, 8, 'South Central Railway', 0, 1, 'C');

    
// Query to fetch CoachNo, Code, Railway from tbl_CoachMaster
$sqlCoachDetails = "SELECT CoachNo, Code, Railway FROM tbl_CoachMaster WHERE CoachNo = ?";
$paramsCoachDetails = array($coachNo);
$stmtCoachDetails = sqlsrv_query($conn, $sqlCoachDetails, $paramsCoachDetails);

if ($stmtCoachDetails === false) {
    die("Error in SQL query for Coach details: " . print_r(sqlsrv_errors(), true));
}

$coachDetails = sqlsrv_fetch_array($stmtCoachDetails, SQLSRV_FETCH_ASSOC);

// Check if data is fetched
if ($coachDetails) {
    $coachNo = $coachDetails['CoachNo'];
    $code = $coachDetails['Code'];
    $railway = $coachDetails['Railway'];

    // Displaying the fetched data in a single line in the PDF
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 8, htmlspecialchars($railway . '- ' . $coachNo . '- ' . $code), 0, 1, 'C');
} else {
    // Handle the case when no data is fetched
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 6, 'No Coach details found.', 0, 1, 'L');
}
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 6, 'RS ID:', 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, htmlspecialchars($coachID), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'Rolling Stock Master Data:', 0, 1, 'L');
$columns = 4; // Number of columns per row

$pdf->Ln(); // Add spacing

function formatValue($value) {
    if ($value instanceof DateTime) {
        return $value->format('Y-m-d'); // Format DateTime to string
    }
    return htmlspecialchars((string)$value); // Convert other types to string and escape
}

$rowCount = 0;
foreach ($data as $key => $value) {
    $formattedValue = formatValue($value);

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(31, 6, ucfirst(str_replace('_', ' ', $key)), 0);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(16, 6, $formattedValue, 0);

    $rowCount++;
    if ($rowCount % $columns == 0) {
        $pdf->Ln(); // Start a new row after 4 columns
    }
}
$pdf->Ln(10); // Adjust the number to increase or decrease the space

// Add POH heading
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'POH History:', 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 10, 'MID', 1);
$pdf->Cell(22, 10, 'Last POH Date', 1);
$pdf->Cell(20, 10, 'Previous RD', 1);
$pdf->Cell(20, 10, 'Yard IN', 1);
$pdf->Cell(20, 10, 'IN Date', 1);
$pdf->Cell(20, 10, 'OUT Date', 1);
$pdf->Cell(20, 10, 'POH Shop', 1);
$pdf->Cell(20, 10, 'Repair Type', 1);
$pdf->Cell(20, 10, 'CD', 1);
$pdf->Cell(20, 10, 'WD', 1);
$pdf->Ln();

$sqlPOH = "SELECT MaintenanceID, last_POH_DATE, previous_RD, yard_IN, workshop_IN, despatched_date, POH_shop,
                repair_type, CalendarDays, WorkingDays
            FROM tbl_TransactionalData
            WHERE CoachID = ?
            ORDER BY MaintenanceID DESC";
$paramsPOH = array($coachID);
$stmtPOH = sqlsrv_query($conn, $sqlPOH, $paramsPOH);

if ($stmtPOH === false) {
    die("Error in SQL query for POH details: " . print_r(sqlsrv_errors(), true));
}

// Output POH details in table rows
while ($pohData = sqlsrv_fetch_array($stmtPOH, SQLSRV_FETCH_ASSOC)) {
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(15, 6, formatValue($pohData['MaintenanceID']), 1);
    $pdf->Cell(22, 6, formatValue($pohData['last_POH_DATE']), 1);
    $pdf->Cell(20, 6, formatValue($pohData['previous_RD']), 1);
    $pdf->Cell(20, 6, formatValue($pohData['yard_IN']), 1);
    $pdf->Cell(20, 6, formatValue($pohData['workshop_IN']), 1);
    $pdf->Cell(20, 6, formatValue($pohData['despatched_date']), 1);
    $pdf->Cell(20, 6, formatValue($pohData['POH_shop']), 1);
    $pdf->Cell(20, 6, formatValue($pohData['repair_type']), 1);
    $pdf->Cell(20, 6, formatValue($pohData['CalendarDays']), 1);
    $pdf->Cell(20, 6, formatValue($pohData['WorkingDays']), 1);
    $pdf->Ln();
}

// Add vertical table
$pdf->Ln(10); // Add some space before the new table
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Wheel & Axle Details:', 0, 1, 'L');
// Vertical Table Headers
$verticalHeaders = array(
    'Bogie No',   'Axle No', 'Items/Locations',
    'Wheel Dia', 'Wheel Disc No', 'RB Nos', 'RB Make', 'RB Type'
);

$cellSizes = array(
    'Bogie No' => 80,
    
    'Axle No' => 40,
    'Items/Locations' => 20, // Default width
    'Wheel Dia' => 20,
    'Wheel Disc No' => 20,
    'RB Nos' => 20,
    'RB Make' => 20,
    'RB Type' => 20
);

$cellHeights = array(
    'Bogie No' => 8,
    'Axle No' => 8,
    'Items/Locations' => 8, // Default width
    'Wheel Dia' => 8,
    'Wheel Disc No' => 8,
    'RB Nos' => 8,
    'RB Make' => 8,
    'RB Type' => 8
);

// Add vertical table to the PDF
$pdf->SetFont('Arial', 'B', 10);

foreach ($verticalHeaders as $header) {
    $pdf->SetFont('Arial', 'B', 10); // Set font for the header
    $pdf->Cell(30, isset($cellHeights[$header]) ? $cellHeights[$header] : 10, $header, 1); // Header cell with increased height

    if (isset($cellSizes[$header])) {
        $pdf->SetFont('Arial', '', 8); // Set font for data cells

        if ($header == 'Bogie No') {
            // Display two cells for the two parts of Bogie No
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($bogieNo1), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($bogieNo2), 1);
        } else if ($header == 'Axle No') {
            // Display four cells for the four parts of Axle No
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($axleNo11), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($axleNo12), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($axleNo21), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($axleNo22), 1);
            } else if ($header == 'Items/Locations') {
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell($cellSizes[$header], $cellHeights[$header], '', 0);
                $pdf->Cell($cellSizes[$header], $cellHeights[$header], 'PP/PEASD End', 0);
                $pdf->Cell($cellSizes[$header], $cellHeights[$header], '', 0);
                $pdf->Cell($cellSizes[$header], $cellHeights[$header], '', 0);
                $pdf->Cell($cellSizes[$header], $cellHeights[$header], '', 'TLB');
                $pdf->Cell($cellSizes[$header], $cellHeights[$header], 'NPP/NPEASD End', 0);
                $pdf->Cell($cellSizes[$header], $cellHeights[$header], '', 0);
                $pdf->Cell($cellSizes[$header], $cellHeights[$header], '', 'TBR');
            } else if ($header == 'Wheel Dia') {
            // Display eight cells for the eight parts of Wheel Dia
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($diaNo11), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($diaNo12), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($diaNo13), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($diaNo14), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($diaNo21), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($diaNo22), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($diaNo23), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($diaNo24), 1);
        } else if ($header == 'Wheel Disc No') {
            // Display eight cells for the eight parts of Wheel Disc No
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($discNo11), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($discNo12), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($discNo13), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($discNo14), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($discNo21), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($discNo22), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($discNo23), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($discNo24), 1);
        } else if ($header == 'RB Nos') {
            // Display eight cells for the eight parts of RB Nos
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbNo11), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbNo12), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbNo13), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbNo14), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbNo21), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbNo22), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbNo23), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbNo24), 1);
        } else if ($header == 'RB Make') {
            // Display eight cells for the eight parts of RB Make
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbmNo11), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbmNo12), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbmNo13), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbmNo14), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbmNo21), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbmNo22), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbmNo23), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbmNo24), 1);
        } else if ($header == 'RB Type') {
            // Display eight cells for the eight parts of RB Type
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbtNo11), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbtNo12), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbtNo13), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbtNo14), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbtNo21), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbtNo22), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbtNo23), 1);
            $pdf->Cell($cellSizes[$header], 8, htmlspecialchars($rbtNo24), 1);
        }
    } else {
        $pdf->Cell(160, 8, '', 1); // Empty cell for data (160 to account for both cells of Bogie No and Frame No)
    }

    $pdf->Ln(); // Move to the next row
}
$pdf->Ln(100); // Adjust the number to increase or decrease the space

// Add POH heading
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Items Traceability:', 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 10, 'MID', 1);
$pdf->Cell(35, 10, 'Description', 1);
$pdf->Cell(25, 10, 'UOM', 1);
$pdf->Cell(25, 10, 'Range', 1);
$pdf->Cell(25, 10, 'parameter', 1);
$pdf->Cell(25, 10, 'Make', 1);
$pdf->Cell(25, 10, 'Remark', 1);
$pdf->Ln();
$sqlCMS = "SELECT Mid, Description, UOM, Range, Parameter, Make, Remarks
            FROM tbl_CMS_Tracable_Item_List
            WHERE Mid = ?";
$paramsCMS = array($latestMID); // Assuming $mid is the variable containing the MID value
$stmtCMS = sqlsrv_query($conn, $sqlCMS, $paramsCMS);

if ($stmtCMS === false) {
    die("Error in SQL query for CMS details: " . print_r(sqlsrv_errors(), true));
}

// Fetch and process the results as needed
while ($CMSData = sqlsrv_fetch_array($stmtCMS, SQLSRV_FETCH_ASSOC)) {
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(15, 6, $CMSData['Mid'], 1);
    $pdf->Cell(35, 6, $CMSData['Description'], 1);
    $pdf->Cell(25, 6, $CMSData['UOM'], 1);
    $pdf->Cell(25, 6, $CMSData['Range'], 1);
    $pdf->Cell(25, 6, $CMSData['Parameter'], 1);
    $pdf->Cell(25, 6, $CMSData['Make'], 1);
    $pdf->Cell(25, 6, $CMSData['Remarks'], 1);
    $pdf->Ln();
}



// Clean up
sqlsrv_free_stmt($stmt);
sqlsrv_free_stmt($stmtCoachID);
sqlsrv_free_stmt($stmtPOH);
sqlsrv_free_stmt($stmtLatestMID);
sqlsrv_free_stmt($stmtBogieNos);
sqlsrv_close($conn);

$pdf->Output();
?>
