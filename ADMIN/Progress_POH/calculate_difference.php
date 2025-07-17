<?php
// calculate_difference.php

require_once '../../db.php';

function checkWDCountZero($conn, $date) {
    $query = "SELECT WD_count FROM [dbo].[tbl_CMS_Master_WD_CD_Dates] WHERE Cal_Date = ?";
    $params = array($date);
    $stmt = sqlsrv_query($conn, $query, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    return $row['WD_count'] == 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inDate = new DateTime($_POST['inDate']);
    $outDate = new DateTime($_POST['outDate']);
    
    $diffHours = 0;
    $currentDate = clone $inDate;

    while ($currentDate < $outDate) {
        $isWDCountZero = checkWDCountZero($conn, $currentDate->format('Y-m-d'));
        if (!$isWDCountZero) {
            $nextDay = clone $currentDate;
            $nextDay->modify('+1 day');
            $nextDay->setTime(0, 0, 0);
            $endTime = min($outDate, $nextDay);
            $diffHours += ($endTime->getTimestamp() - $currentDate->getTimestamp()) / 3600;
        }
        $currentDate->modify('+1 day');
        $currentDate->setTime(0, 0, 0);
    }

    $totalMinutes = round($diffHours * 60);
    
    // Apply rounding rules
    if ($totalMinutes % 60 == 30) {
        $totalMinutes += 30; // Round up to next hour if exactly 30 minutes
    } elseif ($totalMinutes % 60 > 30) {
        $totalMinutes += 60 - ($totalMinutes % 60); // Round up to next hour if over 30 minutes
    } else {
        $totalMinutes -= $totalMinutes % 60; // Round down to previous hour if under 30 minutes
    }

    $diffDays = floor($totalMinutes / (24 * 60));
    $remainingHours = ($totalMinutes % (24 * 60)) / 60;

    // Format the result as "0d Xh" if less than 24 hours
    if ($diffDays == 0 && $remainingHours > 0) {
        $result = '0d ' . $remainingHours . 'h';
    } else {
        $result = '';
        if ($diffDays > 0) $result .= $diffDays . 'd ';
        $result .= $remainingHours . 'h';
    }

    // Output only the formatted result
    echo trim($result);
}

sqlsrv_close($conn);
?>