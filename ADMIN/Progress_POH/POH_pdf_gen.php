<?php
session_start();
include('../../db.php'); // Adjust the path accordingly if needed
require('../../FPDF/fpdf.php'); // Correct path to FPDF library

class PDF extends FPDF
{
    private $isFirstPage = true;

    function Header()
    {
        if ($this->isFirstPage) {
            $this->SetFont('Arial', 'B', 20);
            // Left-aligned text
            $this->Cell(0, 10, 'Carriage Workshop, Lallaguda, SC Rly', 0, 0, 'L');
            // Move to the right
            $this->SetX(-$this->GetStringWidth(date('l, F j, Y')) - 10);
            // Right-aligned text
            $this->Cell(0, 10, date('l, F j, Y'), 0, 1, 'R');
            $this->SetFont('Arial', 'B', 16);
            $this->Cell(0, 10, 'POH PROGRESS', 0, 1, 'C');
            $this->Ln(0);
            $this->isFirstPage = false;
        }

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(15, 8, 'Coach', 'LRT', 0, 'C');
        $this->Cell(25, 8, '', 'LRT', 0, 'C');
        $this->Cell(20, 8, '', 'LRT', 0, 'C');
        $this->Cell(12, 8, '', 'LRT', 0, 'C');
        $this->Cell(20, 8, 'Corr', 'LRT', 0, 'C');
        $this->Cell(33, 8, 'P.Insp', 1, 0, 'C');
        $this->Cell(33, 8, 'L/L', 1, 0, 'C');
        $this->Cell(33, 8, 'Corrosion', 1, 0, 'C');
        $this->Cell(33, 8, 'Paint', 1, 0, 'C');
        $this->Cell(33, 8, 'Berth', 1, 0, 'C');
        $this->Cell(33, 8, 'W.Tank', 1, 0, 'C');
        $this->Cell(33, 8, 'Carriage', 1, 0, 'C');
        $this->Cell(33, 8, 'ETL/AC', 1, 0, 'C');
        $this->Cell(33, 8, 'Air Br', 1, 0, 'C');
        $this->Cell(13, 8, '', 'LRT');
        $this->Ln();
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(15, 8, 'NO', 'LRB', 0, 'C');
        $this->Cell(25, 8, 'Code', 'LRB', 0, 'C');
        $this->Cell(20, 8, 'Date IN', 'LRB', 0, 'C');
        $this->Cell(12, 8, 'Days', 'LRB', 0, 'C');
        $this->Cell(20, 8, 'Hrs', 'LRB', 0, 'C');
        $this->Cell(11, 8, 'IN', 1);
        $this->Cell(11, 8, 'PDC', 1);
        $this->Cell(11, 8, 'OUT', 1);
        $this->Cell(11, 8, 'IN', 1);
        $this->Cell(11, 8, 'PDC', 1);
        $this->Cell(11, 8, 'OUT', 1);
        $this->Cell(11, 8, 'IN', 1);
        $this->Cell(11, 8, 'PDC', 1);
        $this->Cell(11, 8, 'OUT', 1);
        $this->Cell(11, 8, 'IN', 1);
        $this->Cell(11, 8, 'PDC', 1);
        $this->Cell(11, 8, 'OUT', 1);
        $this->Cell(11, 8, 'IN', 1);
        $this->Cell(11, 8, 'PDC', 1);
        $this->Cell(11, 8, 'OUT', 1);
        $this->Cell(11, 8, 'IN', 1);
        $this->Cell(11, 8, 'PDC', 1);
        $this->Cell(11, 8, 'OUT', 1);
        $this->Cell(11, 8, 'IN', 1);
        $this->Cell(11, 8, 'PDC', 1);
        $this->Cell(11, 8, 'OUT', 1);
        $this->Cell(11, 8, 'IN', 1);
        $this->Cell(11, 8, 'PDC', 1);
        $this->Cell(11, 8, 'OUT', 1);
        $this->Cell(11, 8, 'IN', 1);
        $this->Cell(11, 8, 'PDC', 1);
        $this->Cell(11, 8, 'OUT', 1);
        $this->Cell(13, 8, 'NCP', 'LRB');
        $this->Ln(15);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF('L', 'mm', 'A3');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 12);
$category = 'ICF ';
$repair_type = 'Condemned';
$pdf->Cell(0, 8, 'Type Of Repair: ' . $category . ' ' . $repair_type, 0, 1, 'L');
$sql_condemned = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE repair_type = 'CONDEMNED' and category = 'ICF NAC'";
$stmt_condemned = sqlsrv_query($conn, $sql_condemned);
$row_condemned = sqlsrv_fetch_array($stmt_condemned, SQLSRV_FETCH_ASSOC);
if ($row_condemned['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'NON AC ' , 0, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    repair_type = 'CONDEMNED' and category = 'ICF NAC'";


$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }


 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}

$sql_condemnedac = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE repair_type = 'CONDEMNED' and category = 'ICF AC'";
$stmt_condemnedac = sqlsrv_query($conn, $sql_condemnedac);
$row_condemnedac = sqlsrv_fetch_array($stmt_condemnedac, SQLSRV_FETCH_ASSOC);
if ($row_condemnedac['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'AC ' , 0, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    repair_type = 'CONDEMNED' and category = 'ICF AC'";

$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }

 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}
$pdf->SetFont('Arial', 'B', 12);
$category = 'ICF ';
$repair_type = 'POH';
$pdf->Cell(0, 8, 'Type Of Repair: ' . $category . ' ' . $repair_type, 0, 1, 'L');
$sql_poh = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE (repair_type = 'POH' OR repair_type = 'POH+CONV' OR repair_type = 'POH+CORR')  and category = 'ICF NAC'";
$stmt_poh = sqlsrv_query($conn, $sql_poh);
$row_poh = sqlsrv_fetch_array($stmt_poh, SQLSRV_FETCH_ASSOC);

if ($row_poh['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'NON AC', 0, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    (repair_type = 'POH' OR repair_type = 'POH+CONV' OR repair_type = 'POH+CORR') 
     and category = 'ICF NAC'";

$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }


 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}

$sql_pohac = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE (repair_type = 'POH' OR repair_type = 'POH+CONV' OR repair_type = 'POH+CORR')  and category = 'ICF AC'";
$stmt_pohac = sqlsrv_query($conn, $sql_pohac);
$row_pohac = sqlsrv_fetch_array($stmt_pohac, SQLSRV_FETCH_ASSOC);

if ($row_pohac['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'AC', 0, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    (repair_type = 'POH' OR repair_type = 'POH+CONV' OR repair_type = 'POH+CORR') 
     and category = 'ICF AC'";
     
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }

 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}

$pdf->SetFont('Arial', 'B', 12);
$category = 'ICF ';
$repair_type = 'SPL. Repair';
$pdf->Cell(0, 8, 'Type Of Repair: ' . $category . ' ' . $repair_type, 0, 1, 'L');
$sql_spl = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE repair_type = 'SPL. REPAIR' and category = 'ICF NAC'";
$stmt_spl = sqlsrv_query($conn, $sql_spl);
$row_spl = sqlsrv_fetch_array($stmt_spl, SQLSRV_FETCH_ASSOC);
if ($row_spl['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'NON AC ' , 0, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    repair_type = 'SPL. REPAIR' and category = 'ICF NAC'";

$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }

 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}

$sql_splac = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE repair_type = 'SPL. REPAIR' and category = 'ICF AC'";
$stmt_splac = sqlsrv_query($conn, $sql_splac);
$row_splac = sqlsrv_fetch_array($stmt_splac, SQLSRV_FETCH_ASSOC);
if ($row_splac['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'AC ' , 0, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    repair_type = 'SPL. REPAIR' and category = 'ICF AC'";


$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }


 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}

$pdf->SetFont('Arial', 'B', 12);
$category = 'LHB ';
$repair_type = 'Condemned';
$pdf->Cell(0, 8, 'Type Of Repair: ' . $category . ' ' . $repair_type, 0, 1, 'L');
$sql_LHBcondemned = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE repair_type = 'CONDEMNED' and category = 'LHB NAC'";
$stmt_LHBcondemned = sqlsrv_query($conn, $sql_LHBcondemned);
$row_LHBcondemned = sqlsrv_fetch_array($stmt_LHBcondemned, SQLSRV_FETCH_ASSOC);
if ($row_LHBcondemned['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'NON AC ' , 0, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    repair_type = 'CONDEMNED' and category = 'LHB NAC'";

$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }


 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}

$sql_LHBcondemnedac = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE repair_type = 'CONDEMNED' and category = 'LHB AC'";
$stmt_LHBcondemnedac = sqlsrv_query($conn, $sql_LHBcondemnedac);
$row_LHBcondemnedac = sqlsrv_fetch_array($stmt_LHBcondemnedac, SQLSRV_FETCH_ASSOC);
if ($row_LHBcondemnedac['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'AC ' , 0, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    repair_type = 'CONDEMNED' and category = 'LHB AC'";

$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }


 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}

$pdf->SetFont('Arial', 'B', 12);
$category = 'LHB ';
$repair_type = 'SS-II';
$pdf->Cell(0, 8, 'Type Of Repair: ' . $category . ' ' . $repair_type, 0, 1, 'L');
$sql_LHBss_II = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE (repair_type = 'SS-II' OR repair_type = 'SS-II+CONV' OR repair_type = 'SS-II+CORR')  and category = 'LHB NAC'";
$stmt_LHBss_II = sqlsrv_query($conn, $sql_LHBss_II);
$row_LHBss_II = sqlsrv_fetch_array($stmt_LHBss_II, SQLSRV_FETCH_ASSOC);

if ($row_LHBss_II['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'NON AC', 1, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    (repair_type = 'SS-II' OR repair_type = 'SS-II+CONV' OR repair_type = 'SS-II+CORR') 
     and category = 'LHB NAC'";

$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }


 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days,'1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}

$sql_LHBss_IIac = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE (repair_type = 'SS-II' OR repair_type = 'SS-II+CONV' OR repair_type = 'SS-II+CORR')  and category = 'LHB AC'";
$stmt_LHBss_IIac = sqlsrv_query($conn, $sql_LHBss_IIac);
$row_LHBss_IIac = sqlsrv_fetch_array($stmt_LHBss_IIac, SQLSRV_FETCH_ASSOC);

if ($row_LHBss_IIac['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'AC', 1, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    (repair_type = 'SS-II' OR repair_type = 'SS-II+CONV' OR repair_type = 'SS-II+CORR') 
     and category = 'LHB AC'";


$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }


 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}

$pdf->SetFont('Arial', 'B', 12);
$category = 'LHB ';
$repair_type = 'SS-III';
$pdf->Cell(0, 8, 'Type Of Repair: ' . $category . ' ' . $repair_type, 0, 1, 'L');
$sql_LHBss_III = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE (repair_type = 'SS-III' OR repair_type = 'SS-III+CONV' OR repair_type = 'SS-III+CORR')  and category = 'LHB NAC'";
$stmt_LHBss_III = sqlsrv_query($conn, $sql_LHBss_III);
$row_LHBss_III = sqlsrv_fetch_array($stmt_LHBss_III, SQLSRV_FETCH_ASSOC);

if ($row_LHBss_III['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'NON AC', 1, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    (repair_type = 'SS-III' OR repair_type = 'SS-III+CONV' OR repair_type = 'SS-III+CORR') 
     and category = 'LHB NAC'";


$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }

 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}

$sql_LHBss_IIIac = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE (repair_type = 'SS-III' OR repair_type = 'SS-III+CONV' OR repair_type = 'SS-III+CORR')  and category = 'LHB AC'";
$stmt_LHBss_IIIac = sqlsrv_query($conn, $sql_LHBss_IIIac);
$row_LHBss_IIIac = sqlsrv_fetch_array($stmt_LHBss_IIIac, SQLSRV_FETCH_ASSOC);

if ($row_LHBss_IIIac['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'AC', 1, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    (repair_type = 'SS-III' OR repair_type = 'SS-III+CONV' OR repair_type = 'SS-III+CORR') 
     and category = 'LHB AC'";


$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }


 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}
$pdf->SetFont('Arial', 'B', 12);
$category = 'SPV ';
$repair_type = 'Condemned';
$pdf->Cell(0, 8, 'Type Of Repair: ' . $category . ' ' . $repair_type, 0, 1, 'L');
$sql_SPVSPARTcondemned = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE repair_type = 'CONDEMNED' and category = 'SPV SPART'";
$stmt_SPVSPARTcondemned = sqlsrv_query($conn, $sql_SPVSPARTcondemned);
$row_SPVSPARTcondemned = sqlsrv_fetch_array($stmt_SPVSPARTcondemned, SQLSRV_FETCH_ASSOC);
if ($row_SPVSPARTcondemned['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'SPART ' , 0, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    repair_type = 'CONDEMNED' and category = 'SPV SPART'";


$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }

 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}

$sql_SPVDEMUcondemned = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE repair_type = 'CONDEMNED' and category = 'SPV DEMU'";
$stmt_SPVDEMUcondemned = sqlsrv_query($conn, $sql_SPVDEMUcondemned);
$row_SPVDEMUcondemned = sqlsrv_fetch_array($stmt_SPVDEMUcondemned, SQLSRV_FETCH_ASSOC);
if ($row_SPVDEMUcondemned['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'DEMU ' , 0, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    repair_type = 'CONDEMNED' and category = 'SPV DEMU'";


$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }


 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}
$sql_SPVEMUMEMUcondemned = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE repair_type = 'CONDEMNED' and category = 'SPV EMU/MEMU'";
$stmt_SPVEMUMEMUcondemned = sqlsrv_query($conn, $sql_SPVEMUMEMUcondemned);
$row_SPVEMUMEMUcondemned = sqlsrv_fetch_array($stmt_SPVEMUMEMUcondemned, SQLSRV_FETCH_ASSOC);
if ($row_SPVEMUMEMUcondemned['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'EMU/MEMU ' , 0, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    repair_type = 'CONDEMNED' and category = 'SPV EMU/MEMU'";

$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }


 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days,'1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}

$sql_SPVTCcondemned = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE repair_type = 'CONDEMNED' and category = 'SPV TC'";
$stmt_SPVTCcondemned = sqlsrv_query($conn, $sql_SPVTCcondemned);
$row_SPVTCcondemned = sqlsrv_fetch_array($stmt_SPVTCcondemned, SQLSRV_FETCH_ASSOC);
if ($row_SPVTCcondemned['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);

$pdf->Cell(0, 8, 'TC ' , 0, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    repair_type = 'CONDEMNED' and category = 'SPV TC'";


$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }


 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}

$pdf->SetFont('Arial', 'B', 12);
$category = 'SPV ';
$repair_type = 'POH';
$pdf->Cell(0, 8, 'Type Of Repair: ' . $category . ' ' . $repair_type, 0, 1, 'L');
$sql_SPVSPARTpoh = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE (repair_type = 'POH' OR repair_type = 'POH+CONV' OR repair_type = 'POH+CORR')  and category = 'SPV SPART'";
$stmt_SPVSPARTpoh = sqlsrv_query($conn, $sql_SPVSPARTpoh);
$row_SPVSPARTpoh = sqlsrv_fetch_array($stmt_SPVSPARTpoh, SQLSRV_FETCH_ASSOC);

if ($row_SPVSPARTpoh['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'SPART', 1, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    (repair_type = 'POH' OR repair_type = 'POH+CONV' OR repair_type = 'POH+CORR') 
     and category = 'SPV SPART'";


$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }


 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}

$sql_SPVDEMUpoh = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE (repair_type = 'POH' OR repair_type = 'POH+CONV' OR repair_type = 'POH+CORR')  and category = 'SPV DEMU'";
$stmt_SPVDEMUpoh = sqlsrv_query($conn, $sql_SPVDEMUpoh);
$row_SPVDEMUpoh = sqlsrv_fetch_array($stmt_SPVDEMUpoh, SQLSRV_FETCH_ASSOC);

if ($row_SPVDEMUpoh['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'DEMU', 1, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    (repair_type = 'POH' OR repair_type = 'POH+CONV' OR repair_type = 'POH+CORR') 
     and category = 'SPV DEMU'";

$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }


 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}

$sql_SPVEMUMEMUpoh = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE (repair_type = 'POH' OR repair_type = 'POH+CONV' OR repair_type = 'POH+CORR')  and category = 'SPV EMU/MEMU'";
$stmt_SPVEMUMEMUpoh = sqlsrv_query($conn, $sql_SPVEMUMEMUpoh);
$row_SPVEMUMEMUpoh = sqlsrv_fetch_array($stmt_SPVEMUMEMUpoh, SQLSRV_FETCH_ASSOC);

if ($row_SPVEMUMEMUpoh['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'EMU/MEMU', 1, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    (repair_type = 'POH' OR repair_type = 'POH+CONV' OR repair_type = 'POH+CORR') 
     and category = 'SPV EMU/MEMU'";


$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }


 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}

$sql_SPVTCpoh = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE (repair_type = 'POH' OR repair_type = 'POH+CONV' OR repair_type = 'POH+CORR')  and category = 'SPV TC'";
$stmt_SPVTCpoh = sqlsrv_query($conn, $sql_SPVTCpoh);
$row_SPVTCpoh = sqlsrv_fetch_array($stmt_SPVTCpoh, SQLSRV_FETCH_ASSOC);

if ($row_SPVTCpoh['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'TC', 1, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    (repair_type = 'POH' OR repair_type = 'POH+CONV' OR repair_type = 'POH+CORR') 
     and category = 'SPV TC'";


$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }

 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}
$pdf->SetFont('Arial', 'B', 12);
$category = 'SPV';
$repair_type = 'SPL. Repair';
$pdf->Cell(0, 8, 'Type Of Repair: ' . $category . ' ' . $repair_type, 0, 1, 'L');
$sql_SPVSPARTspl = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE repair_type = 'SPL. REPAIR' and category = 'SPV SPART'";
$stmt_SPVSPARTspl = sqlsrv_query($conn, $sql_SPVSPARTspl);
$row_SPVSPARTspl = sqlsrv_fetch_array($stmt_SPVSPARTspl, SQLSRV_FETCH_ASSOC);
if ($row_SPVSPARTspl['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'SPV SPART ' , 0, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    repair_type = 'SPL. REPAIR' and category = 'SPV SPART'";

$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }

 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}

$sql_SPVDEMUspl = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE repair_type = 'SPL. REPAIR' and category = 'SPV DEMU'";
$stmt_SPVDEMUspl = sqlsrv_query($conn, $sql_SPVDEMUspl);
$row_SPVDEMUspl = sqlsrv_fetch_array($stmt_SPVDEMUspl, SQLSRV_FETCH_ASSOC);
if ($row_SPVDEMUspl['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, ' DEMU ' , 0, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    repair_type = 'SPL. REPAIR' and category = 'SPV DEMU'";

$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }


 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}

$sql_SPVEMUMEMUspl = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE repair_type = 'SPL. REPAIR' and category = 'SPV EMU/MEMU'";
$stmt_SPVEMUMEMUspl = sqlsrv_query($conn, $sql_SPVEMUMEMUspl);
$row_SPVEMUMEMUspl = sqlsrv_fetch_array($stmt_SPVEMUMEMUspl, SQLSRV_FETCH_ASSOC);
if ($row_SPVEMUMEMUspl['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'EMU/MEMU ' , 0, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    repair_type = 'SPL. REPAIR' and category = 'SPV EMU/MEMU'";

$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }


 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}

$sql_SPVTCspl = "SELECT COUNT(*) as count FROM V_CMS_POH_PDF WHERE repair_type = 'SPL. REPAIR' and category = 'SPV TC'";
$stmt_SPVTCspl = sqlsrv_query($conn, $sql_SPVTCspl);
$row_SPVTCspl = sqlsrv_fetch_array($stmt_SPVTCspl, SQLSRV_FETCH_ASSOC);
if ($row_SPVTCspl['count'] > 0) {
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'TC ' , 0, 'L');
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT 
    CoachNo,
    Code,
    Workshop_IN,
    Corr_Hrs,
    P_Insp_IN,
    P_Insp_PDC,
    P_Insp_OUT,
    L_L_IN,
    L_L_PDC,
    L_L_OUT,
    Corr_IN,
    Corr_PDC,
    Corr_OUT,
    Paint_IN,
    Paint_PDC,
    Paint_OUT,
    Berth_IN,
    Berth_PDC,
    Berth_OUT,
    W_Tank_IN,
    W_Tank_PDC,
    W_Tank_OUT,
    Carriage_IN,
    Carriage_PDC,
    Carriage_OUT,
    ETL_AC_IN,
    ETL_AC_PDC,
    ETL_AC_OUT,
    Air_Br_IN,
    Air_Br_PDC,
    Air_Br_OUT,
    NTXR_OUT
FROM 
    V_CMS_POH_PDF
WHERE
    repair_type = 'SPL. REPAIR' and category = 'SPV TC'";


$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$today = new DateTime();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $workshop_in_date = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN'] : new DateTime($row['Workshop_IN']);
    
    // Calculate total days in workshop
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($workshop_in_date, $interval, $today);
    $total_days = 0;

    foreach ($period as $date) {
        $date_str = $date->format('Y-m-d');
        $sql_wd = "SELECT WD_count FROM tbl_CMS_Master_WD_CD_Dates WHERE Cal_Date = ?";
        $params = array($date_str);
        $stmt_wd = sqlsrv_query($conn, $sql_wd, $params);
        if ($stmt_wd === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row_wd = sqlsrv_fetch_array($stmt_wd, SQLSRV_FETCH_ASSOC);
        if ($row_wd) {
            $total_days += $row_wd['WD_count'];
        }
    }

 // Determine Corr_Hrs range based on specific values and print directly
if ($row['Corr_Hrs'] == 'less than 500') {
    $corr_hrs_range = '< 500';
} elseif ($row['Corr_Hrs'] == 'between 501 to 1000') {
    $corr_hrs_range = '500-1000';
} elseif ($row['Corr_Hrs'] == 'more than 1000') {
    $corr_hrs_range = '> 1000';
} else {
    $corr_hrs_range = 'Unknown range';
}


    // Format DateTime objects
    $workshop_in = $row['Workshop_IN'] instanceof DateTime ? $row['Workshop_IN']->format('d-m-y') : $row['Workshop_IN'];
    $p_insp_in = $row['P_Insp_IN'] instanceof DateTime ? $row['P_Insp_IN']->format('d-m') : $row['P_Insp_IN'];
    $p_insp_pdc = $row['P_Insp_PDC'] instanceof DateTime ? $row['P_Insp_PDC']->format('d-m') : $row['P_Insp_PDC'];
    $p_insp_out = $row['P_Insp_OUT'] instanceof DateTime ? $row['P_Insp_OUT']->format('d-m') : $row['P_Insp_OUT'];
    $l_l_in = $row['L_L_IN'] instanceof DateTime ? $row['L_L_IN']->format('d-m') : $row['L_L_IN'];
    $l_l_pdc = $row['L_L_PDC'] instanceof DateTime ? $row['L_L_PDC']->format('d-m') : $row['L_L_PDC'];
    $l_l_out = $row['L_L_OUT'] instanceof DateTime ? $row['L_L_OUT']->format('d-m') : $row['L_L_OUT'];
    $corr_in = $row['Corr_IN'] instanceof DateTime ? $row['Corr_IN']->format('d-m') : $row['Corr_IN'];
    $corr_pdc = $row['Corr_PDC'] instanceof DateTime ? $row['Corr_PDC']->format('d-m') : $row['Corr_PDC'];
    $corr_out = $row['Corr_OUT'] instanceof DateTime ? $row['Corr_OUT']->format('d-m') : $row['Corr_OUT'];
    $paint_in = $row['Paint_IN'] instanceof DateTime ? $row['Paint_IN']->format('d-m') : $row['Paint_IN'];
    $paint_pdc = $row['Paint_PDC'] instanceof DateTime ? $row['Paint_PDC']->format('d-m') : $row['Paint_PDC'];
    $paint_out = $row['Paint_OUT'] instanceof DateTime ? $row['Paint_OUT']->format('d-m') : $row['Paint_OUT'];
    $berth_in = $row['Berth_IN'] instanceof DateTime ? $row['Berth_IN']->format('d-m') : $row['Berth_IN'];
    $berth_pdc = $row['Berth_PDC'] instanceof DateTime ? $row['Berth_PDC']->format('d-m') : $row['Berth_PDC'];
    $berth_out = $row['Berth_OUT'] instanceof DateTime ? $row['Berth_OUT']->format('d-m') : $row['Berth_OUT'];
    $w_tank_in = $row['W_Tank_IN'] instanceof DateTime ? $row['W_Tank_IN']->format('d-m') : $row['W_Tank_IN'];
    $w_tank_pdc = $row['W_Tank_PDC'] instanceof DateTime ? $row['W_Tank_PDC']->format('d-m') : $row['W_Tank_PDC'];
    $w_tank_out = $row['W_Tank_OUT'] instanceof DateTime ? $row['W_Tank_OUT']->format('d-m') : $row['W_Tank_OUT'];
    $carriage_in = $row['Carriage_IN'] instanceof DateTime ? $row['Carriage_IN']->format('d-m') : $row['Carriage_IN'];
    $carriage_pdc = $row['Carriage_PDC'] instanceof DateTime ? $row['Carriage_PDC']->format('d-m') : $row['Carriage_PDC'];
    $carriage_out = $row['Carriage_OUT'] instanceof DateTime ? $row['Carriage_OUT']->format('d-m') : $row['Carriage_OUT'];
    $etl_ac_in = $row['ETL_AC_IN'] instanceof DateTime ? $row['ETL_AC_IN']->format('d-m') : $row['ETL_AC_IN'];
    $etl_ac_pdc = $row['ETL_AC_PDC'] instanceof DateTime ? $row['ETL_AC_PDC']->format('d-m') : $row['ETL_AC_PDC'];
    $etl_ac_out = $row['ETL_AC_OUT'] instanceof DateTime ? $row['ETL_AC_OUT']->format('d-m') : $row['ETL_AC_OUT'];
    $air_br_in = $row['Air_Br_IN'] instanceof DateTime ? $row['Air_Br_IN']->format('d-m') : $row['Air_Br_IN'];
    $air_br_pdc = $row['Air_Br_PDC'] instanceof DateTime ? $row['Air_Br_PDC']->format('d-m') : $row['Air_Br_PDC'];
    $air_br_out = $row['Air_Br_OUT'] instanceof DateTime ? $row['Air_Br_OUT']->format('d-m') : $row['Air_Br_OUT'];
    $ntxr_out = $row['NTXR_OUT'] instanceof DateTime ? $row['NTXR_OUT']->format('d-m-y') : $row['NTXR_OUT'];

    $pdf->Cell(15, 8, $row['CoachNo'], '1');
    $pdf->Cell(25, 8, $row['Code'], '1');
    $pdf->Cell(20, 8, $workshop_in, '1');
    $pdf->Cell(12, 8, $total_days, '1');
    $pdf->Cell(20, 8, $corr_hrs_range, '1'); // Make sure $corr_hrs_range is set correctly
    $pdf->Cell(11, 8, $p_insp_in, '1');
    $pdf->Cell(11, 8, $p_insp_pdc, '1');
    $pdf->Cell(11, 8, $p_insp_out, '1');
    $pdf->Cell(11, 8, $l_l_in, '1');
    $pdf->Cell(11, 8, $l_l_pdc, '1');
    $pdf->Cell(11, 8, $l_l_out, '1');
    $pdf->Cell(11, 8, $corr_in, '1');
    $pdf->Cell(11, 8, $corr_pdc, '1');
    $pdf->Cell(11, 8, $corr_out, '1');
    $pdf->Cell(11, 8, $paint_in, '1');
    $pdf->Cell(11, 8, $paint_pdc, '1');
    $pdf->Cell(11, 8, $paint_out, '1');
    $pdf->Cell(11, 8, $berth_in, '1');
    $pdf->Cell(11, 8, $berth_pdc, '1');
    $pdf->Cell(11, 8, $berth_out, '1');
    $pdf->Cell(11, 8, $w_tank_in, '1');
    $pdf->Cell(11, 8, $w_tank_pdc, '1');
    $pdf->Cell(11, 8, $w_tank_out, '1');
    $pdf->Cell(11, 8, $carriage_in, '1');
    $pdf->Cell(11, 8, $carriage_pdc, '1');
    $pdf->Cell(11, 8, $carriage_out, '1');
    $pdf->Cell(11, 8, $etl_ac_in, '1');
    $pdf->Cell(11, 8, $etl_ac_pdc, '1');
    $pdf->Cell(11, 8, $etl_ac_out, '1');
    $pdf->Cell(11, 8, $air_br_in, '1');
    $pdf->Cell(11, 8, $air_br_pdc, '1');
    $pdf->Cell(11, 8, $air_br_out, '1');
    $pdf->Cell(13, 8, $ntxr_out, '1');
    $pdf->Ln();
}
}

$pdf->Output();
?>
