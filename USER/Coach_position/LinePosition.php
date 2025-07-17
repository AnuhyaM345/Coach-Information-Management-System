<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../check_session.php';
include '../../db.php';

$cusername = isset($_SESSION['username']) ? $_SESSION['username'] : '';
?>
<?php

function customErrorHandler($errno, $errstr, $errfile, $errline)
{
    if ($errno == E_WARNING && strpos($errstr, 'Undefined array key') !== false) {
        echo "<script>alert('Warning: $errstr in $errfile on line $errline');</script>";
    }
    return true; // Prevent the PHP default error handler from running
}

set_error_handler("customErrorHandler");

include '../../db.php';

// SQL query to fetch data from the database
$sql = "SELECT CoachNo, CoachLocation FROM vw_CMS_LGDSHolding";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Initialize an array to store coach numbers for each location
$coachPositions = [];

// Loop through each row of the result set
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $coachNo = $row["CoachNo"];
    $coachLocation = $row["CoachLocation"];


    // Check if coachLocation is empty
    if (empty($coachLocation)) {
        error_log("Empty coachLocation for CoachNo: $coachNo");
        continue;
    }

    // Enhanced error handling for debugging
    preg_match('/([A-Z]+)(\d+)_([\w]+)/', $coachLocation, $matches);
    if (count($matches) < 4) {
        // Log the invalid format and continue
        error_log("Invalid coachLocation format: $coachLocation");
        continue; // Skip to the next iteration
    }
    $shop = $matches[1]; // Shop code (all alphabets until numbers start)
    $line = $matches[2]; // Line number (numbers until underscore)
    $location = $matches[3]; // Location (everything after underscore)

    // Add the coach number to the corresponding shop, line, and location
    if (empty($line)) {
        // If no separate line information, directly assign the location
        $coachPositions[$shop][$location] = $coachNo;
    } else {
        $coachPositions[$shop]["Line" . $line][$location] = $coachNo;
    }
}

//var_dump($coachPositions);

// Close the database connection
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

// Define the structure of shops and their lines for display
$shopsStructure = [
    "Wall Line" => ["Wall Line 1 " => 22],
    "Inspection Line" => [
        "INSP Line 1" => 3,
        "INSP Line 2" => 4,
        "INSP Line 3" => 3,
    ],
    "Lift and Lower Shop" => [
        "LL Line 1" => 7,
        "LL Line 2" => 7,
    ],
    "Old Paint Shop" => [
        "PNT Line 1" => 8,
        "PNT Line 2" => 8,
    ],
    "Weigh Bridge Line" => [
        "WB Line 1" => 3,
    ],
    "New Paint Shop" => [
        "PNT Line 3" => 2,
        "PNT Line 4" => 2,
        "PNT Line 5" => 2,
        "PNT Line 6" => 2,
        "PNT Line 7" => 2,
        "PNT Line 8" => 2,
    ],
    "Carriage Shop" => [
        "CAR Line 1" => 7,
        "CAR Line 2" => 7,
        "CAR Line 3" => 7,
        "CAR Line 4" => 7,
        "CAR Line 5" => 7,
        "CAR Line 6" => 7,
        "CAR Line 7" => 7,
    ],
    "Feed Line" => [
        "F Line 1" => 6,
        "F Line 2" => 6,
    ],

    "PVC Shop" => ["PVC Line 1" => 6],
    "Corrosion Shop" => [
        "COR Line 1" => 3,
        "COR Line 2" => 3,
        "COR Line 3" => 3,
        "COR Line 4" => 3,
        "COR Line 5" => 3,
        "COR Line 6" => 3,
        "COR Line 7" => 3,
        "COR Line 8" => 3,
        "COR Line 9" => 3,
        "COR Line 10" => 3,
        "COR Line 11" => 3,
        "COR Line 12" => 3,
    ],
    "Power Car Shed" => [
        "PC Line 1" => 6,
        "PC Line 2" => 6,
    ],
    "DEMU Shed" => [
        "D4" => 6,
        "D3" => 6,
        "D2" => 6,
        "D1" => 6,
    ],
];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Position</title>
    <link rel="icon" href="../../SCR_Logo.png" type="image/jpg">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="Coach_Position.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .coach-container {
            padding: 20px;
            background-color: #F0E3CE;
            max-width: 1200px;
            margin: 20px auto;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            height: 70px;
        }

        .coach-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: center;
            background-color: blue;
            color: white;
            padding: 20px 20px;
            border-radius: 20px 20px 0 0;
        }

        .coach-header h1 {
            margin: 0;
            text-align: center;
            flex: 1;
        }

        .coach-header button {
            background-color: #ff0000;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .coach-tablecard {

            width: 850px;

            border: 1px solid #ccc;
            border-radius: 8px;
            margin-top: 15px;
            background-color: #f9f9f9;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.4);
            margin-left: 3px;
            margin-right: 5px;
        }

        .coach-table-container {
            width: 100%;
            max-width: 100%;
            overflow: auto;
            position: relative;
            max-height: auto;
        }

        .coach-table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }

        .coach-table th,
        .coach-table td {
            border: 1px solid black;
            padding: 2px;

            text-align: start;
            background-color: #fff;
            font-size: 10px;
        }

        .coach-table th {
            background-color: #f1f1f1;
        }

        .coach-table-container th,
        .coach-table-container td {
            width: 45px;
        }

        .coach-table-container thead th {
            position: sticky;
            top: 0;
            z-index: 3;
            color: white;
            background-color: #00008B;
        }

        .coach-table-container tbody th {
            position: sticky;
            left: 0;
            z-index: 2;
            background-color: #f1f1f1;
        }

        .coach-table-container thead th:first-child {
            left: 0;
            z-index: 4;
        }

        .coach-card {
            height: 80px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            padding-top: 10px;
            padding-bottom: 0px;
            background-color: #f9f9f9;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.4);
            margin-bottom: 7px;
        }




        .coach-form-section button {
            background-color: #0044cc;
            color: white;
            border: none;
            cursor: pointer;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }



        .coach-split-section {
            display: flex;
            justify-content: space-between;
        }

        .coach-split-section>div {
            flex: 1;
        }

        .coach-split-section table {
            margin-right: 10px;
        }

        .coach-split-section .coach-form-container {
            max-width: 300px;

        }

        .coach-popup {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 10002;
            /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scrolling if needed */
            background-color: rgba(0, 0, 0, 0.5);
            /* Black background with transparency */
            display: flex;
            /* Enable flexbox */
            justify-content: center;
            /* Center horizontally */
            align-items: center;
            /* Center vertically */
        }

        .coach-popup-content {
            background-color: #F1F1F1;
            padding: 20px;
            border: 1px solid #888;
            width: 90%;
            max-width: 900px;
            /* Maximum width */
            max-height: 90%;
            /* Maximum height */
            border-radius: 10px;
            position: relative;
            overflow-y: auto;
            /* Enable vertical scroll if needed */
        }

        .coach-popup-content .close {
            color: #aaa;
            position: absolute;
            right: 20px;
            font-size: 28px;
            font-weight: bold;
        }

        .coach-popup-content .close:hover,
        .coach-popup-content .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }




        .a {
            background-color: blue;
            color: white;
            font-size: 10px;
            font-weight: bold;

        }

        .form-group label {
            font-weight: bold;
        }

        .form-group select,
        .form-group input[type="text"] {
            width: 100%;
        }

        .form-row {
            margin-bottom: 15px;
        }

        input[type="text"] {
            border: 2px solid #00008B;
            border-radius: 10px;
            outline: none;
            font-size: 16px;
            color: black;
        }

        .input_box {
            position: relative;
            font-weight: bold;
        }

        .input_box select,
        .input_box input[type="text"],
        .input_box input[type="date"] {
            width: 80%;
            padding: 10px;
            border: 1px solid #0000FF;
            border-radius: 5px;
            font-size: 10px;
            outline: none;
            height: 15px;
            color: black;
            font-weight: 500;

        }

        .input_box select:disabled,
        .input_box input[type="text"]:disabled,
        .input_box input[type="date"]:disabled {
            color: black;
            background-color: transparent;
            cursor: not-allowed;
        }

        .input_box label {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: white;
            padding: 0 5px;
            color: #0000FF;
            font-size: 16px;
            transition: all 0.3s;
            pointer-events: none;
        }

        .input_box input[type="text"]:focus+label,
        .input_box input[type="text"]:not(:placeholder-shown)+label,
        .input_box input[type="date"]:focus+label,
        .input_box input[type="date"]:not(:placeholder-shown)+label,
        .input_box select:focus+label,
        .input_box select:not(:placeholder-shown)+label {
            top: -10px;
            left: 10px;
            font-size: 10px;
            color: #808080;
        }

        .hidden {
            display: none;
        }

        .datacard {
            height:150px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 5px;
            background-color: #f9f9f9;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4);
            width: 104%;
            margin-left: -15px;
        }

        .input_box {
            position: relative;
            margin-bottom: 10px;
        }

        .input_box input[type="text"],
        .input_box input[type="date"] {
            width: 100%;
        }

        .label {
            position: absolute;
            top: -6px;
            left: 10px;
            background: #fff;
            padding: 0 5px;
        }

        .tablecard {
            height: 270px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            padding-top: 10px;
            background-color: #f9f9f9;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4);
            margin-top: 5px;
            margin-left: -20px;
            width: 600px;

        }


        .table-container {
            width: 100%;
            max-width: 100%;
            overflow: auto;
            position: relative;
            max-height: auto;
            /* Adjust as needed */
        }

        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid black;
            padding: 4px 8px;
            background-color: #fff;
        }

        th {
            background-color: #f1f1f1;
        }

        .table-container th,
        .table-container td {
            width: 80px;
            /* Adjust as needed */
        }

        .table-container thead th {
            position: sticky;
            top: 0;
            z-index: 3;
            /* Ensure header is above the rest of the table */
            color: white;
            background-color: #00008B;
        }

        .table-container tbody th {
            position: sticky;
            left: 0;
            z-index: 2;
            /* Ensure first column is above the rest of the table */
            background-color: #f1f1f1;
        }

        .table-container thead th:first-child {
            left: 0;
            z-index: 4;
            /* Ensure the intersection of header and first column is above the rest */
        }

        .boxcard {
            height: 270px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            padding-top: 10px;
            padding-bottom: 0px;
            background-color: #f9f9f9;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4);
            margin-bottom: 7px;
            margin-top: 7px;
            margin-right: -15px;
        }

        .custom-scrollbar {
            overflow-y: scroll;
            max-height: 200px;
            /* Adjust as needed */
        }

        /* Webkit-based browsers (Chrome, Safari) */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
            /* Decrease the scrollbar width */
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            /* Color of the scrollbar track */
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #888;
            /* Color of the scrollbar thumb */
            border-radius: 10px;
            /* Rounded corners for the scrollbar thumb */
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555;
            /* Color of the scrollbar thumb on hover */
        }

        /* Firefox */
        .custom-scrollbar {
            scrollbar-width: thin;
            /* Decrease the scrollbar width */
            scrollbar-color: #888 #f1f1f1;
            /* Scrollbar thumb and track color */
        }

        .changeLocation {
            font-family: 'Montserrat', sans-serif;
            font-size: 15px;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 10px;
            background-color: #00008B;
            border-radius: 30px;
            color: white;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition-duration: .2s;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.116);
            padding-left: 0.8em;
            height: 30px;
            width: 200px;
            /* Fixed width */
            margin-left: 10px;
        }

        .svgIcon {
            height: 22px;
            /* Match the height of the SVG */
            transition-duration: 1.5s;
        }

        .bell path {
            fill: rgb(19, 19, 19);
        }

        .changeLocation:hover {
            background-color: #00008B;
            transition-duration: .5s;
        }

        .changeLocation:active {
            transform: scale(0.97);
            transition-duration: .2s;
        }

        .changeLocation:hover .svgIcon {
            transform: rotate(250deg);
            transition-duration: 1.5s;
        }

        .changeLocation:focus {
            outline: none;
        }

        .page-wrapper {
            width: 100%;
            overflow-x: hidden;

        }

        html,
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background-color: #f0f4f8;
        }

        .page-wrapper {
            width: 100%;

            overflow: hidden;

        }

        .searchcard {
            height: 50px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            padding-top: 24px;
            background-color: #f9f9f9;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.4);
            margin-top: 67px;
            margin-bottom: 7px;
        }

        .searchbutton {
            font-family: 'Montserrat', sans-serif;
            font-size: 15px;
            padding: 0.7em 1em;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 10px;
            background-color: #00008B;
            border-radius: 30px;
            color: white;
            font-weight: 600;
            border: none;
            position: relative;
            cursor: pointer;
            transition-duration: .2s;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.116);
            padding-left: 0.8em;
            transition-duration: .5s;
            height: 36px;
            width: 110px;
            margin-left: 10px;

        }

        .svgIcon {
            height: 30px;
            transition-duration: 1.5s;
        }

        .bell path {
            fill: rgb(19, 19, 19);
        }

        .searchbutton:hover {
            background-color: #00008B;
            transition-duration: .5s;
        }

        .searchbutton:active {
            transform: scale(0.97);
            transition-duration: .2s;
        }

        .searchbutton:hover .svgIcon {
            transform: rotate(250deg);
            transition-duration: 1.5s;
        }

        .searchbutton:focus {
            outline: none;
        }


        .highlight {
            animation: highlight-animation 10s ease-in-out;

        }

        @keyframes highlight-animation {
            0% {
                background-color: yellow;
            }

            100% {
                background-color: #00008B;
            }
        }

        .navbar {
            height: 60px;
            background-color: #00008B;
            color: white;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 10000;
        }

        .navbar-brand,
        .navbar-nav .nav-link {
            color: white;
        }

        .sidebar {
            height: 100%;
            width: 230px; /* Set a fixed width */
            position: fixed;
            z-index: 10000;
            top: 0;
            left: -251px; /* Start off-screen */
            background-color: #111;
            overflow-x: hidden;
            overflow-y: hidden;
            transition: transform 0.2s ease-out;
            padding-top: 40px;
            display: flex;
            flex-direction: column;
            box-shadow: 1px 0 0 rgba(0,0,0,0.1);

        }

        .sidebar.open {
            transform: translateX(250px);
        }

        .sidebar-content {
            width: 250px; /* Match sidebar width */
            opacity: 0;
            transition: opacity 0.2s ease-out;
        }

        .sidebar.open .sidebar-content {
            opacity: 1;
        }

        .sidebar a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 15px;
            color: #818181;
            display: block;
            transition: 0.3s;
            white-space: nowrap;
        }

        .sidebar a:hover {
            color: #f1f1f1;
        }

        .sidebar .closebtn {
            position: absolute;
            top: 11px;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
            margin-top:-7px;
            transition: transform 0.2s ease-out;
        }

        .welcome-message {
            color: #f1f1f1;
            padding: 20px 8px;
            font-size: 18px;
            text-align: center;
            margin-top: auto;
            opacity: 0;
            transition: opacity 0.2s ease-out;
        }

        .sidebar.open .welcome-message {
            opacity: 1;
        }

        @keyframes glow {
            from {
                text-shadow: 0 0 5px #fff, 0 0 10px #fff, 0 0 15px #0073e6, 0 0 20px #0073e6;
            }
            to {
                text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #0073e6, 0 0 40px #0073e6;
            }
        }

        .sidebar.open .welcome-message {
            animation: glow 2s ease-in-out infinite alternate;
        }

        .openbtn {
            font-size: 26px;
            cursor: pointer;
            background-color: transparent;
            color: white;
            padding: 6px 9px;
            border: none;
            margin-right: 10px;
            margin-left: -10px;
            width: 30px;
            text-align: left;
            padding-left: 0px;
        }

        .openbtn:focus {
            outline:none;
        }

        .cimsp {
            color: white;
            font-size: 20px;

        }

        .logoutBtn {
            font-family: 'Montserrat', sans-serif;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition-duration: 0.3s;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.199);
            background-color: white;
            outline: none;
        }

        .logoutBtn .sign {
            width: 100%;
            transition-duration: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logoutBtn .sign svg {
            width: 20px;
        }

        .logoutBtn .sign svg path {
            fill: #00008B;
        }

        .logoutBtn .text {
            position: absolute;
            right: 0%;
            width: 0%;
            opacity: 0;
            color: #00008B;
            font-size: 1.2em;
            font-weight: 600;
            transition-duration: 0.3s;
        }

        .logoutBtn:hover {
            width: 125px;
            border-radius: 40px;
            transition-duration: 0.3s;
        }

        .logoutBtn:hover .sign {
            width: 35%;
            transition-duration: 0.3s;
            padding-left: 10px;
        }

        .logoutBtn:hover .text {
            opacity: 1;
            width: 70%;
            transition-duration: 0.3s;
            padding-right: 10px;
        }

        .logoutBtn:active {
            transform: translate(2px, 2px);
        }

        .logoutBtn:focus {
            outline: none;
        }

        .image {
            height: 45px;
            width: 45px;
            background-size: cover;
            border-radius: 50%;
        }

        .image1 {
            height: 45px;
            width: 45px;
            background-size: cover;
            border-radius: 50%;
            margin-left: 30px;
            margin-top: -20px;
        }

        .highlight {
            animation: blink 1s step-start infinite;
        }

        @keyframes blink {
            50% {
                background-color: yellow;
                color: black;
            }
        }

        #popupForm {
            display: none;
        }

       .frontcard{
        margin-right:-12px;
       }
       .secondcard{
        margin-left:-10px;
       }
       .oldcard
       {
        margin-right:-12px;
       }
       .thirdcard{
        margin-left:-10px;
       }
       .newcard{
        margin-right:-12px;
       }
       .carrcard{
        margin-left:-10px;
       }
       .corrcard{
        margin-left:-21px;
       }
       .power-car-shed-line{
        margin-right:-12px;
       }
       .demucard{
        margin-left:-17px;
       }
       @keyframes blink1 {
  0%, 50%, 100% {
    opacity: 1;
  }
  25%, 75% {
    opacity: 0;
  }
}

.blink1 {
  animation: blink1 1.5s infinite;
}
body::-webkit-scrollbar {
            width: 10px; /* Vertical scrollbar width */
            height: 10px; /* Horizontal scrollbar height */
        }

        body::-webkit-scrollbar-track {
            background: transparent; /* Background of the scrollbar track */
        }

        body::-webkit-scrollbar-thumb {
            background: #888; /* Color of the scrollbar thumb */
            border-radius: 10px; /* Roundness of the scrollbar thumb */
        }

        body::-webkit-scrollbar-thumb:hover {
            background: #555; /* Color of the scrollbar thumb on hover */
        }

        body::-webkit-scrollbar-button {
            display: none; /* Hide all buttons by default */
        }

        body::-webkit-scrollbar-button:single-button:vertical:decrement,
        body::-webkit-scrollbar-button:single-button:vertical:increment,
        body::-webkit-scrollbar-button:single-button:horizontal:decrement,
        body::-webkit-scrollbar-button:single-button:horizontal:increment {
            display: block; /* Display only the arrow buttons */
            width: 20px; /* Ensure the buttons fit within the scrollbar */
            height: 20px;
            background-size: 100% 100%; /* Ensure the background image fits the button */
        }

        body::-webkit-scrollbar-button:single-button:vertical:decrement {
            background: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24"><path fill="%23555555" d="M8 14l4-4 4 4z"/></svg>') center no-repeat;
        }

        body::-webkit-scrollbar-button:single-button:vertical:increment {
            background: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24"><path fill="%23555555" d="M8 10l4 4 4-4z"/></svg>') center no-repeat;
        }

        body::-webkit-scrollbar-button:single-button:horizontal:decrement {
            background: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24"><path fill="%23555555" d="M14 8l-4 4 4 4z"/></svg>') center no-repeat;
        }

        body::-webkit-scrollbar-button:single-button:horizontal:increment {
            background: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24"><path fill="%23555555" d="M10 8l4 4-4 4z"/></svg>') center no-repeat;
        }
        .empty-line {
             min-height: 43px; /* Adjust height as needed */
             height:auto;
        }


        /* Popup styles */
    </style>
</head>

<body onload="setCurrentDateTime()">


    <div class="page-wrapper custom-scrollbar">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <button class="openbtn" onclick="openNav()">☰</button>
                <img src="../../SCR_Logo.jpg" class="image mr-2" alt="Logo">
                <p class="navbar-brand mb-0">CIMS - Coach Position</p>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- No navigation links added -->
                </div>

                <span style="font-size: 13px; color: white; margin-left:600px; ">
                    <?php
                    date_default_timezone_set('Asia/Kolkata'); // Set the desired timezone
                    echo date('d-m-Y H:i'); // Format the date and time as per your requirement
                    ?>
                </span>



                <a href="../../logout.php">
                    <button class="logoutBtn ml-3">
                        <div class="sign">
                            <svg viewBox="0 0 512 512">
                                <path
                                    d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z">
                                </path>
                            </svg>
                        </div>
                        <div class="text">Logout</div>
                    </button>
                </a>
            </div>
        </nav>

        <div id="mySidebar" class="sidebar">
            <div class="sidebar-content">
                <div class="d-flex flex-row justify-content-start" style="margin-top:-20px;">
                    <img src="../../SCR_Logo.png" class="image1 mt-0 mr-3" alt="Logo">
                    <p class="cimsp mt-2">CIMS</p>
                </div>
                <br>
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
                <a href="../Dashboard/Dashboard.php">Home</a>
                <a href="../Coach_position/LinePosition.php">Coach Position</a>
                <a href="../Feed/Transactionaldata.php">Feed</a>
                <a href="../Holdings/Holdings.php">Holdings</a>
                <a href="../Progress_POH/POHprogress.php">POH Progress</a>
                <a href="../RS_certificate/RS_Certificate.php">RS Certificate</a>
            </div>
    
            <div class="welcome-message">
                Welcome,<br><?php echo htmlspecialchars($cusername); ?>!
            </div>
        </div>
    </div>
    

    <?php
    include 'functions.php'; // Include the functions file
    ?>
    <div class="container-fluid mt-2">
        <div class="searchcard d-flex justify-content-between align-items-center ml-0">
            <form class="mb-3 d-flex align-items-center" id="searchForm" style="margin-top: 5px;">
                <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search Coach No"
                    style="width: 200px; font-size: 14px; margin-top: 10px;">
                <button type="submit" class="searchbutton" id="searchButton" style="margin-top: 10px;">
                    <svg class="svgIcon" viewBox="0 0 512 512" height="1em" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm50.7-186.9L162.4 380.6c-19.4 7.5-38.5-11.6-31-31l55.5-144.3c3.3-8.5 9.9-15.1 18.4-18.4l144.3-55.5c19.4-7.5 38.5 11.6 31 31L325.1 306.7c-3.2 8.5-9.9 15.1-18.4 18.4zM288 256a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"
                            fill="white"></path>
                    </svg>
                    Search
                </button>
            </form>
            <div class="d-flex align-items-center">
  <a class="custom-box2 m-1 blink1" style="font-size: 15px; color: #00008B;font-weight:bold;">
    Total Holdings of LGDS: 
    <?php echo countCoaches($coachPositions, 'WL') +
              countCoaches($coachPositions, 'INSP') +
              countCoaches($coachPositions, 'WB') +
              countCoaches($coachPositions, 'PNT') +
              countCoaches($coachPositions, 'CAR') +
              countCoaches($coachPositions, 'LL') +
              countCoaches($coachPositions, 'PVC') +
              countCoaches($coachPositions, 'F') +
              countCoaches($coachPositions, 'COR') +
              countCoaches($coachPositions, 'PC') +
              countCoaches($coachPositions, 'D'); ?>
  </a>
</div>

        </div>
    </div>


    <div class="container-fluid">
        <div class="mt-2" style="height:100px;margin-left:-5px;margin-right:5px;">
            <div class="row mt-4">
                <div class="col-12">
                    <?php
                    // Display the coach positions for Wall Line
                    $shopCode = 'WL'; // Shop code for Wall Line
                    foreach ($shopsStructure["Wall Line"] as $lineName => $numLocations):
                        $lineCode = "Line" . filter_var($lineName, FILTER_SANITIZE_NUMBER_INT);
                        ?>
                        <div class="custom-box ml-1"
                            style="box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4); border-radius: 7px;   height:75px; margin-top:-17px;">
                            <!-- Adjust the width as needed -->
                            <h5 class="wall-line-heading mt-1 ml-2"><b>Wall
                                    Line<?php echo "[" . countCoaches($coachPositions, 'WL') . "]"; ?></b></h5>
                            <div class="row mb-4 mt-3" style="margin-left: -3px;">

                                <div class="col">
                                    <div class="row" style="margin-left:15px; margin-top:-10px;">
                                        <?php
                                        for ($i = 1; $i <= $numLocations; $i++):
                                            if (isset($coachPositions[$shopCode][$lineCode][$i])) {
                                                $coach = $coachPositions[$shopCode][$lineCode][$i];
                                                $category = getCoachCategory($conn, $coach);

                                                // Determine the button color based on the category
                                                $buttonColor = "grey"; // Default gray color
                                    
                                                if (!is_null($category)) {
                                                    if (strpos($category, 'ICF') === 0) {
                                                        $buttonColor = "#00008B"; // Blue for ICF category
                                                    } elseif (strpos($category, 'SPV') === 0) {
                                                        $buttonColor = "green"; // Green for SPV category
                                                    } elseif (strpos($category, 'LHB') === 0) {
                                                        $buttonColor = "red"; // Red for LHB category
                                                    }
                                                }
                                                ?>
                                                <div class="col-auto" style="margin-left: -22px;">
                                                    <button id="button-<?php echo $coach; ?>"
                                                        onclick="openPopup('<?php echo $coach; ?>')" class="btn btn-link"
                                                        style="background-color: <?php echo $buttonColor; ?>; color: white; font-size: 9px; padding: 3px 8px; border-radius: 5px; min-width:50px;"><?php echo $coach; ?></button>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="col-auto" style="margin-left: -20px; color: #D3D3D3;font-size:9px;">
                                                    Loc<?php echo $i; ?></div>
                                                <?php
                                            }
                                        endfor;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>



    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 ">
                <div class="frontcard p-1"  style="box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4); border-radius: 7px;background-color:white; margin-top:-35px;">
                    <h5 class="inspection-heading ml-2"><b>Inspection Line
                            <?php echo "[" . countCoaches($coachPositions, 'INSP') . "]"; ?> </b></h5>
                    <?php
                    $shopCode = 'INSP';
                    foreach ($shopsStructure["Inspection Line"] as $lineName => $numLocations):
                        $lineCode = "Line" . filter_var($lineName, FILTER_SANITIZE_NUMBER_INT);
                        ?>
                        <div class="line-container p-2 " style="font-size:12px;">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <b><?php echo $lineName; ?></b>
                                </div>
                                <div class="d-flex flex-wrap">
                                    <?php
                                    for ($i = 1; $i <= $numLocations; $i++):
                                        if (isset($coachPositions[$shopCode][$lineCode][$i])) {
                                            $coach = $coachPositions[$shopCode][$lineCode][$i];
                                            $category = getCoachCategory($conn, $coach);

                                            $buttonColor = "grey";
                                            if (!is_null($category)) {
                                                if (strpos($category, 'ICF') === 0) {
                                                    $buttonColor = "#00008B";
                                                } elseif (strpos($category, 'SPV') === 0) {
                                                    $buttonColor = "green";
                                                } elseif (strpos($category, 'LHB') === 0) {
                                                    $buttonColor = "red";
                                                }
                                            }
                                            ?>
                                            <div class="mr-5"style="margin-top:-10px;">
                                                <button id="button-<?php echo $coach; ?>"
                                                    onclick="openPopup('<?php echo $coach; ?>')"
                                                    class="btn btn-link btn-sm"
                                                    style="background-color: <?php echo $buttonColor; ?>; border-radius: 5px; font-size:9px; color: white; min-width:50px;"><?php echo $coach; ?></button>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="mr-5 " style="color: #D3D3D3; font-size:9px;">Loc<?php echo $i; ?></div>
                                            <?php
                                        }
                                    endfor;
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-md-6">
    <div class="secondcard p-1" style="box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4); border-radius: 7px; background-color: white;  height: 137px;  margin-top:-35px;">
        <h5 class="lift-lower-shop-heading ml-2" style="font-size: 15px; color: #00008B; margin-top:0px;"><b>Lift & Lower Shop <?php echo "[" . countCoaches($coachPositions, 'LL') . "]"; ?> </b></h5>
        <?php
        $shopCode = 'LL';
        foreach ($shopsStructure["Lift and Lower Shop"] as $lineName => $numLocations):
            $lineCode = "Line" . filter_var($lineName, FILTER_SANITIZE_NUMBER_INT);
        ?>
            <div class="line-container ml-2" style="font-size: 12px; width: auto">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <b><?php echo $lineName; ?></b>
                    </div>
                    <div class="d-flex flex-wrap">
                        <?php
                        for ($i = 1; $i <= $numLocations; $i++):
                            if (isset($coachPositions[$shopCode][$lineCode][$i])) {
                                $coach = $coachPositions[$shopCode][$lineCode][$i];
                                $category = getCoachCategory($conn, $coach);

                                $buttonColor = "grey";
                                if (!is_null($category)) {
                                    if (strpos($category, 'ICF') === 0) {
                                        $buttonColor = "#00008B";
                                    } elseif (strpos($category, 'SPV') === 0) {
                                        $buttonColor = "green";
                                    } elseif (strpos($category, 'LHB') === 0) {
                                        $buttonColor = "red";
                                    }
                                }
                                ?>
                                <div class="mr-3 mb-2">
                                    <button id="button-<?php echo $coach; ?>" onclick="openPopup('<?php echo $coach; ?>')" class="btn btn-link " style="background-color: <?php echo $buttonColor; ?>; border-radius: 5px; font-size: 9px; color: white; min-width:50px; padding:3px 8px;"><?php echo $coach; ?></button>
                                </div>
                            <?php
                            } else {
                                ?>
                                <div class="mr-3 mb-2 mt-2" style="font-size: 9px; color: #D3D3D3;">Loc<?php echo $i; ?></div>
                        <?php
                            }
                        endfor;
                        ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

    <div class="container-fluid">
        <div class="row mt-5">
        <div class="col-md-6">
                <div class="oldcard p-3"
                    style="box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4); border-radius: 7px; background-color:white;height:115px;margin-top:-40px;">
                    <h5 class="old-paint-shop-heading" style="margin-top:-10px; margin-left:-5px;"><b>Old Paint Shop
                            <?php echo "[" . countCoaches($coachPositions, 'PNT') . "]"; ?> </b></h5>
                    <?php
                    $shopCode = 'PNT';
                    foreach ($shopsStructure["Old Paint Shop"] as $lineName => $numLocations):
                        $lineCode = "Line" . filter_var($lineName, FILTER_SANITIZE_NUMBER_INT);
                        ?>
                        <div class="line-container mb-0" style="font-size:12px;color:black;margin-left:-5px;">
                            <div class="d-flex align-items-center flex-wrap">
                                <div class="mr-3"><b><?php echo $lineName; ?></b></div>
                                <div class="d-flex flex-wrap">
                                    <?php
                                    for ($i = 1; $i <= $numLocations; $i++):
                                        if (isset($coachPositions[$shopCode][$lineCode][$i])) {
                                            $coach = $coachPositions[$shopCode][$lineCode][$i];
                                            $category = getCoachCategory($conn, $coach);

                                            $buttonColor = "grey";
                                            if (!is_null($category)) {
                                                if (strpos($category, 'ICF') === 0) {
                                                    $buttonColor = "#00008B";
                                                } elseif (strpos($category, 'SPV') === 0) {
                                                    $buttonColor = "green";
                                                } elseif (strpos($category, 'LHB') === 0) {
                                                    $buttonColor = "red";
                                                }
                                            }
                                            ?>
                                            <div class="mr-2 mb-2 mt-2">
                                                <button id="button-<?php echo $coach; ?>"
                                                    onclick="openPopup('<?php echo $coach; ?>')" class="btn btn-link"
                                                    style="background-color: <?php echo $buttonColor; ?>; font-size: 9px; padding: 3px 8px; border-radius: 5px; color: white; min-width:50px;"><?php echo $coach; ?></button>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="mr-2 mb-2 mt-2" style="color: #D3D3D3; font-size:9px;">Loc<?php echo $i; ?>
                                            </div>
                                            <?php
                                        }
                                    endfor;
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
       
            <div class="col-md-6 ">
                <div class="thirdcard p-3"
                    style="box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4); border-radius: 7px; background-color:white;height:115px;margin-top:-39px;">
                    <h5 class="weigh-bridge-line-heading" style="color:#00008B;font-size:15px; margin-left:-10px;margin-top:-10px;"><b>Weigh Bridge Line
                            <?php echo "[" . countCoaches($coachPositions, 'WB') . "]"; ?></b></h5>
                    <?php
                    $shopCode = 'WB';
                    foreach ($shopsStructure["Weigh Bridge Line"] as $lineName => $numLocations):
                        $lineCode = "Line" . filter_var($lineName, FILTER_SANITIZE_NUMBER_INT);
                        ?>
                        <div class="line-container mb-0" style="font-size:12px;margin-left:-5px;">
                            <div class="d-flex align-items-center">
                                <div class="mr-3"><b><?php echo $lineName; ?></b></div>
                                <div class="d-flex flex-wrap">
                                    <?php
                                    for ($i = 1; $i <= $numLocations; $i++):
                                        if (isset($coachPositions[$shopCode][$lineCode][$i])) {
                                            $coach = $coachPositions[$shopCode][$lineCode][$i];
                                            $category = getCoachCategory($conn, $coach);

                                            $buttonColor = "grey";
                                            if (!is_null($category)) {
                                                if (strpos($category, 'ICF') === 0) {
                                                    $buttonColor = "#00008B";
                                                } elseif (strpos($category, 'SPV') === 0) {
                                                    $buttonColor = "green";
                                                } elseif (strpos($category, 'LHB') === 0) {
                                                    $buttonColor = "red";
                                                }
                                            }
                                            ?>
                                            <div class="mr-5 mb-2 mt-1">
                                                <button id="button-<?php echo $coach; ?>"
                                                    onclick="openPopup('<?php echo $coach; ?>')" class="btn btn-link"
                                                    style="background-color: <?php echo $buttonColor; ?>; font-size: 9px; padding: 3px 8px; border-radius: 5px; color: white; min-width:50px;"><?php echo $coach; ?></button>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="mr-5 mb-2 mt-2" style="color: #D3D3D3; font-size:9px;">Loc<?php echo $i; ?>
                                            </div>
                                            <?php
                                        }
                                    endfor;
                                    ?>
                                    
                                </div>
                                
                            </div>
                        </div>
                    <?php endforeach; ?>
                <div class="empty-line"></div>
                </div>
            </div>

            
        </div>
    </div>




    <div class="container-fluid mt-2">
        <div class="row">
            <!-- New Paint Shop -->
            <div class="col-md-3">
                <div class="newcard" style="height:420px;  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4); border-radius: 7px; background-color:white;">
                    <div class="container-fluid">
                        <div class="card-body">
                        
                            <h5 class="shop-heading  " style="font-size:15px;color:#00008B; margin-top:-10px; margin-left:-15px;">
                                <b>New Paint Shop</b>
                            </h5>
                            
                            <?php
                            $shopCode = 'PNT'; // Shop code for New Paint Shop
                            foreach ($shopsStructure["New Paint Shop"] as $lineName => $numLocations):
                                ?>
                                <div class="line-container mt-2" style="font-size:12px; margin-left:-15px;">
                                    <div class="row">
                                        <div class="col-12"><b><?php echo $lineName; ?></b></div>
                                    </div>
                                    <div class="d-flex flex-wrap custom-box"
                                        style="margin-left:70px; margin-top:-20px; width:auto;">
                                        <?php
                                        $lineCode = "Line" . filter_var($lineName, FILTER_SANITIZE_NUMBER_INT);
                                        for ($i = 1; $i <= $numLocations; $i++):
                                            if (isset($coachPositions[$shopCode][$lineCode][$i])) {
                                                $coach = $coachPositions[$shopCode][$lineCode][$i];
                                                $category = getCoachCategory($conn, $coach);

                                                // Determine the button color based on the starting part of the category
                                                $buttonColor = "grey"; // Default gray color
                                    
                                                if (!is_null($category)) {
                                                    if (strpos($category, 'ICF') === 0) {
                                                        $buttonColor = "#00008B"; // Blue for ICF category
                                                    } elseif (strpos($category, 'SPV') === 0) {
                                                        $buttonColor = "green"; // Green for SPV category
                                                    } elseif (strpos($category, 'LHB') === 0) {
                                                        $buttonColor = "red"; // Red for LHB category
                                                    }
                                                }
                                                ?>
                                                <div class="p-1 mr-4">
                                                    <button id="button-<?php echo $coach; ?>"
                                                        onclick="openPopup('<?php echo $coach; ?>')" class="btn btn-link"
                                                        style="background-color: <?php echo $buttonColor; ?>; color: white; font-size: 9px; padding: 3px 8px; border-radius: 5px; min-width:50px;"><?php echo $coach; ?></button>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="p-1 mr-4" style="color: #D3D3D3; font-size:9px;">Loc<?php echo $i; ?></div>
                                                <?php
                                            }
                                        endfor;
                                        ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                    
                    </div><div>    </div>
                </div>
            </div>
        </div>

            <!-- Carriage Shop -->
            <div class="col-md-5">
                <div class="carrcard" style="height:420px; box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4); border-radius: 7px; background-color:white;">
                    <div class="container-fluid">
                    <div class="card-body">
                        <h5 class="shop-heading p-1" style="font-size:15px; color:#00008B; margin-top:-20px; margin-left:-23px;">
                            <b>Carriage Shop [<?php echo countCoaches($coachPositions, 'CAR'); ?>]</b>
                            <b>PVC [<?php echo countCoaches($coachPositions, 'PVC'); ?>]</b>
                            <b>FEED [<?php echo countCoaches($coachPositions, 'F'); ?>]</b>
                        </h5>

                        <?php
                        $shopCode = 'CAR'; // Shop code for Carriage Shop
                        foreach ($shopsStructure["Carriage Shop"] as $lineName => $numLocations):
                            ?>
                            <div class="line-container mt-2" style="font-size:12px; margin-left:-20px;">
                                <div class="row">
                                    <div class="col-12"><b><?php echo $lineName; ?></b></div>
                                </div>
                                <div class="d-flex flex-wrap custom-box"
                                    style="margin-left:70px; margin-top:-20px; width:auto;">
                                    <?php
                                    $lineCode = "Line" . filter_var($lineName, FILTER_SANITIZE_NUMBER_INT);
                                    for ($i = 1; $i <= $numLocations; $i++):
                                        if (isset($coachPositions[$shopCode][$lineCode][$i])) {
                                            $coach = $coachPositions[$shopCode][$lineCode][$i];
                                            $category = getCoachCategory($conn, $coach);

                                            // Determine the button color based on the starting part of the category
                                            $buttonColor = "grey"; // Default gray color
                                
                                            if (!is_null($category)) {
                                                if (strpos($category, 'ICF') === 0) {
                                                    $buttonColor = "#00008B"; // Blue for ICF category
                                                } elseif (strpos($category, 'SPV') === 0) {
                                                    $buttonColor = "green"; // Green for SPV category
                                                } elseif (strpos($category, 'LHB') === 0) {
                                                    $buttonColor = "red"; // Red for LHB category
                                                }
                                            }
                                            ?>
                                            <div class="p-1 mr-1">
                                                <button id="button-<?php echo $coach; ?>"
                                                    onclick="openPopup('<?php echo $coach; ?>')" class="btn btn-link"
                                                    style="background-color: <?php echo $buttonColor; ?>; color: white; font-size: 9px; padding: 3px 8px; border-radius: 5px; min-width:50px;"><?php echo $coach; ?></button>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="p-1 mr-1" style="color: #D3D3D3; font-size:9px;">Loc<?php echo $i; ?></div>
                                            <?php
                                        }
                                    endfor;
                                    ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        </div>

                        <!-- PVC Shop -->
                        <?php
                        $shopCode = 'PVC'; // Shop code for PVC Shop
                        foreach ($shopsStructure["PVC Shop"] as $lineName => $numLocations):
                            ?>
                            <div class="line-container " style="font-size:12px; margin-left:-3px;">
                                <div class="row">
                                    <div class="col-12"><b><?php echo $lineName; ?></b></div>
                                </div>
                                <div class="d-flex flex-wrap custom-box"
                                    style="margin-left:80px; margin-top:-20px; width:auto;">
                                    <?php
                                    $lineCode = "Line" . filter_var($lineName, FILTER_SANITIZE_NUMBER_INT);
                                    for ($i = 1; $i <= $numLocations; $i++):
                                        if (isset($coachPositions[$shopCode][$lineCode][$i])) {
                                            $coach = $coachPositions[$shopCode][$lineCode][$i];
                                            $category = getCoachCategory($conn, $coach);

                                            // Determine the button color based on the starting part of the category
                                            $buttonColor = "grey"; // Default gray color
                                
                                            if (!is_null($category)) {
                                                if (strpos($category, 'ICF') === 0) {
                                                    $buttonColor = "#00008B"; // Blue for ICF category
                                                } elseif (strpos($category, 'SPV') === 0) {
                                                    $buttonColor = "green"; // Green for SPV category
                                                } elseif (strpos($category, 'LHB') === 0) {
                                                    $buttonColor = "red"; // Red for LHB category
                                                }
                                            }
                                            ?>
                                            <div class="p-1 mr-2">
                                                <button id="button-<?php echo $coach; ?>"
                                                    onclick="openPopup('<?php echo $coach; ?>')" class="btn btn-link"
                                                    style="background-color: <?php echo $buttonColor; ?>; color: white; font-size: 9px; padding: 3px 8px; border-radius: 5px; min-width:50px;"><?php echo $coach; ?></button>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="p-1 mr-2" style="color: #D3D3D3; font-size:9px;">Loc<?php echo $i; ?></div>
                                            <?php
                                        }
                                    endfor;
                                    ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Feed Line -->
                        <?php
                        $shopCode = 'F'; // Shop code for Feed Line
                        foreach ($shopsStructure["Feed Line"] as $lineName => $numLocations):
                            ?>
                            <div class="line-container mt-2" style="font-size:12px; margin-left:-3px;">
                                <div class="row">
                                    <div class="col-12"><b><?php echo $lineName; ?></b></div>
                                </div>
                                <div class="d-flex flex-wrap custom-box"
                                    style="margin-left:80px; margin-top:-20px;width:auto">
                                    <?php
                                    $lineCode = "Line" . filter_var($lineName, FILTER_SANITIZE_NUMBER_INT);
                                    for ($i = 1; $i <= $numLocations; $i++):
                                        if (isset($coachPositions[$shopCode][$lineCode][$i])) {
                                            $coach = $coachPositions[$shopCode][$lineCode][$i];
                                            $category = getCoachCategory($conn, $coach);

                                            // Determine the button color based on the starting part of the category
                                            $buttonColor = "grey"; // Default gray color
                                
                                            if (!is_null($category)) {
                                                if (strpos($category, 'ICF') === 0) {
                                                    $buttonColor = "#00008B"; // Blue for ICF category
                                                } elseif (strpos($category, 'SPV') === 0) {
                                                    $buttonColor = "green"; // Green for SPV category
                                                } elseif (strpos($category, 'LHB') === 0) {
                                                    $buttonColor = "red"; // Red for LHB category
                                                }
                                            }
                                            ?>
                                            <div class="p-1 mr-2">
                                                <button id="button-<?php echo $coach; ?>"
                                                    onclick="openPopup('<?php echo $coach; ?>')" class="btn btn-link"
                                                    style="background-color: <?php echo $buttonColor; ?>; color: white; font-size: 9px; padding: 3px 8px; border-radius: 5px; min-width:50px;"><?php echo $coach; ?></button>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="p-1 mr-2" style="color: #D3D3D3; font-size:9px;">Loc<?php echo $i; ?></div>
                                            <?php
                                        }
                                    endfor;
                                    ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Corrosion Shop -->
            <div class="col-md-4">
                <div class="corrcard" style="height:420px;  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4); border-radius: 7px; background-color:white;">
                    <div class="card-body">
                        <h5 class="shop-heading p-1 " style="font-size:15px;color:#00008B; margin-top:-20px; margin-left:-15px;">
                            <b>Corrosion Shop [<?php echo countCoaches($coachPositions, 'COR'); ?>]</b>
                        </h5>

                        <?php
                        $shopCode = 'COR'; // Shop code for Corrosion Shop
                        foreach ($shopsStructure["Corrosion Shop"] as $lineName => $numLocations):
                            ?>
                            <div class="line-container mt-1" style="font-size:12px;margin-left:-10px;">
                                <div class="row">
                                    <div class="col-12"><b><?php echo $lineName; ?></b></div>
                                </div>
                                <div class="d-flex flex-wrap custom-box"
                                    style="margin-left:80px; margin-top:-20px;width:auto;">
                                    <?php
                                    $lineCode = "Line" . filter_var($lineName, FILTER_SANITIZE_NUMBER_INT);
                                    for ($i = 1; $i <= $numLocations; $i++):
                                        if (isset($coachPositions[$shopCode][$lineCode][$i])) {
                                            $coach = $coachPositions[$shopCode][$lineCode][$i];
                                            $category = getCoachCategory($conn, $coach);

                                            // Determine the button color based on the starting part of the category
                                            $buttonColor = "grey"; // Default gray color
                                
                                            if (!is_null($category)) {
                                                if (strpos($category, 'ICF') === 0) {
                                                    $buttonColor = "#00008B"; // Blue for ICF category
                                                } elseif (strpos($category, 'SPV') === 0) {
                                                    $buttonColor = "green"; // Green for SPV category
                                                } elseif (strpos($category, 'LHB') === 0) {
                                                    $buttonColor = "red"; // Red for LHB category
                                                }
                                            }
                                            ?>
                                            <div class="p-1 mr-4">
                                                <button id="button-<?php echo $coach; ?>"
                                                    onclick="openPopup('<?php echo $coach; ?>')" class="btn btn-link"
                                                    style="background-color: <?php echo $buttonColor; ?>; color: white; font-size: 9px; padding: 3px 8px; border-radius: 5px;min-width:50px;"><?php echo $coach; ?></button>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="p-1 mr-4" style="color: #D3D3D3; font-size:9px;">Loc<?php echo $i; ?></div>
                                            <?php
                                        }
                                    endfor;
                                    ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid">
        <div class="row mt-4">
            <!-- Power Car Shed -->
            <div class="col-md-6 mb-4">
            <div class="power-car-shed-line" style="margin-top: -15px; box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4); background-color: white; margin-left: 4px; height: 170px; border-radius: 7px;">
            <h5 class="power-car-shed-heading ml-1" style="color: #00008B; font-size: 15px;"> <b>Power Car Shed
                    <?php echo "[" . countCoaches($coachPositions, 'PC') . "]"; ?> </b>
                </h5>
                <?php
                $shopCode = 'PC'; // Shop code for Power Car Shed
                foreach ($shopsStructure["Power Car Shed"] as $lineName => $numLocations):
                ?>
                    <div class="line-container" style="font-size: 12px; margin-top: 10px;">
                        <div class="row">
                            <div class="col-auto"><b><?php echo $lineName; ?></b></div>
                            <div class="col">
                                <div class="row">
                                    <?php
                                    $lineCode = "Line" . filter_var($lineName, FILTER_SANITIZE_NUMBER_INT);
                                    for ($i = 1; $i <= $numLocations; $i++):
                                        if (isset($coachPositions[$shopCode][$lineCode][$i])) {
                                            $coach = $coachPositions[$shopCode][$lineCode][$i];
                                            $category = getCoachCategory($conn, $coach);

                                            // Determine the button color based on the starting part of the category
                                            $buttonColor = "grey"; // Default gray color

                                            if (!is_null($category)) {
                                                if (strpos($category, 'ICF') === 0) {
                                                    $buttonColor = "#00008B"; // Blue for ICF category
                                                } elseif (strpos($category, 'SPV') === 0) {
                                                    $buttonColor = "green"; // Green for SPV category
                                                } elseif (strpos($category, 'LHB') === 0) {
                                                    $buttonColor = "red"; // Red for LHB category
                                                }
                                            }
                                    ?>
                                            <div class="col-auto mb-2">
                                                <button id="button-<?php echo $coach; ?>" onclick="openPopup('<?php echo $coach; ?>')" class="btn btn-link" style="color: white; font-size: 9px; padding: 3px 8px; background-color: <?php echo $buttonColor; ?>; border-radius: 5px; min-width:50px;"><?php echo $coach; ?></button>
                                            </div>
                                    <?php
                                        } else {
                                    ?>
                                            <div class="col-auto mb-2 " style="font-size: 9px; color: #D3D3D3;">Loc<?php echo $i; ?></div>
                                    <?php
                                        }
                                    endfor;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>


           
            <!-- DEMU Shed -->
            <div class="col-md-6 " >
                <div class="demucard"
                    style="box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4); border-radius: 7px; background-color:white; height:170px;margin-top:-15px; ">
                    <div class="card-body">
                        <h5 class="card-title mb-3" style="color:#00008B; font-size:15px; margin-top:-10px;margin-left:-10px;"><b>DEMU
                                Shed [<?php echo countCoaches($coachPositions, 'D'); ?>]</h5></b>
                        <?php
                        $shopCode = 'D'; // Shop code for DEMU Shed
                        foreach ($shopsStructure["DEMU Shed"] as $lineName => $numLocations):
                            ?>
                            <div class="mb-3">
                                <h6 class="card-subtitle mb-2 " style="font-size:12px; color:black; font-weight:bold; margin-left:-10px;">
                                    <?php echo $lineName; ?>
                                </h6>
                                <div class="row" style="margin-left:10px; margin-top:-25px;width:530px;">
                                    <?php
                                    $lineCode = "Line" . filter_var($lineName, FILTER_SANITIZE_NUMBER_INT);
                                    for ($i = 1; $i <= $numLocations; $i++):
                                        if (isset($coachPositions[$shopCode][$lineCode][$i])) {
                                            $coach = $coachPositions[$shopCode][$lineCode][$i];
                                            $category = getCoachCategory($conn, $coach);

                                            // Determine the button color based on the starting part of the category
                                            $buttonColor = "grey"; // Default gray color
                                
                                            if (!is_null($category)) {
                                                if (strpos($category, 'ICF') === 0) {
                                                    $buttonColor = "#00008B"; // Blue for ICF category
                                                } elseif (strpos($category, 'SPV') === 0) {
                                                    $buttonColor = "green"; // Green for SPV category
                                                } elseif (strpos($category, 'LHB') === 0) {
                                                    $buttonColor = "red"; // Red for LHB category
                                                }
                                            }
                                            ?>
                                            <div class="col-auto mr-1">
                                                <button id="button-<?php echo $coach; ?>"
                                                    onclick="openPopup('<?php echo $coach; ?>')" class="btn btn-link"
                                                    style="color: white; font-size: 9px; padding: 3px 8px; background-color: <?php echo $buttonColor; ?>; border-radius: 5px; min-width:50px;"><?php echo $coach; ?></button>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="col-auto  mr-1">
                                                <div class="" style="font-size:9px; color:#D3D3D3">Loc<?php echo $i; ?></div>
                                            </div>
                                            <?php
                                        }
                                    endfor;
                                    ?>
                                </div>
                                
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wall Line -->


    <div id="popupForm" class="coach-popup" style="border-radius:15px;">

        <div class="coach-popup-content container-fluid">
            <span class="close" id="closePopup" style="margin-top:-20px;color:white;"
                onclick="closePopup()">&times;</span>
            <div style="color:white; background-color:#00008B;margin-top:-20px;margin-left:-20px; margin-right:-20px; ">
                <h1 style="font-size:20px; text-align:center; padding:5px;">Current Information</h1>
            </div>

            <div class="container-fluid">
                <div class="datacard">
                    <form id="coachInfoForm" action="/submit-form" method="post" class="coach-form-horizontal"
                        style="margin-top:7px; margin-left:3px;">
                        <div class="form-row">
                            <div class="col-md-2">
                                <div class="input_box">
                                    <input type="text" class="form-control" name="CoachNo" id="CoachNo" disabled>
                                    <label for="CoachNo" class="label" style="font-size:10px;">CoachNo</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input_box">
                                    <input type="text" class="form-control" name="Drawing_No" id="Drawing_No" disabled>
                                    <label for="Drawing_No" class="label" style="font-size:10px;">DrawingNo</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input_box">
                                    <input type="text" class="form-control" name="trp_code" id="trp_code" disabled>
                                    <label for="trp_code" class="label" style="font-size:10px;">TrpCode</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input_box">
                                    <input type="text" class="form-control" name="mfg_type" id="mfg_type" disabled>
                                    <label for="mfg_type" class="label" style="font-size:10px;">MfgType</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input_box">
                                    <input type="text" class="form-control" name="built" id="built" disabled>
                                    <label for="built" class="label" style="font-size:10px;">Built</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input_box">
                                    <input type="text" class="form-control" name="owning_railway" id="owning_railway"
                                        disabled>
                                    <label for="owning_railway" class="label" style="font-size:10px;">Owning
                                        Railway</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-row" style="margin-top:-10px;">
                            <div class="col-md-2">
                                <div class="input_box">
                                    <input type="text" class="form-control" name="division" id="division" disabled>
                                    <label for="division" class="label" style="font-size:10px;">Division</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input_box">
                                    <input type="text" class="form-control" name="base_depot" id="base_depot" disabled>
                                    <label for="base_depot" class="label" style="font-size:10px;">BaseDepot</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input_box">
                                    <input type="text" class="form-control" name="coupling_type" id="coupling_type"
                                        disabled>
                                    <label for="coupling_type" class="label"
                                        style="font-size:10px;">CouplingType</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input_box">
                                    <input type="text" class="form-control" name="toilet_system" id="toilet_system"
                                        disabled>
                                    <label for="toilet_system" class="label"
                                        style="font-size:10px;">ToiletSystem</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input_box">
                                    <input type="text" class="form-control" name="transferred_from"
                                        id="transferred_from" disabled>
                                    <label for="transferred_from" class="label" style="font-size:10px;">
                                        RailwayTransferredFrom
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input_box">
                                    <input type="date" class="form-control" name="date_transferred_from"
                                        id="date_transferred_from" disabled>
                                    <label for="date_transferred_from" class="label"
                                        style="font-size:10px;">DateTransferredFrom</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-row" style="margin-top:-10px;">
                            <div class="col-md-2">
                                <div class="input_box">
                                    <input type="text" class="form-control" name="brake_system" id="brake_system"
                                        disabled>
                                    <label for="brake_system" class="label" style="font-size:10px;">BrakeSystem</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input_box">
                                    <input type="text" class="form-control" name="body_type" id="body_type" disabled>
                                    <label for="body_type" class="label" style="font-size:10px;">BodyType</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input_box">
                                    <input type="text" class="form-control" name="transferred_to" id="transferred_to"
                                        disabled>
                                    <label for="transferred_to" class="label"
                                        style="font-size:10px;">RailwayTransferredTo</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input_box">
                                    <input type="date" id="date_transferred_to" name="date_transferred_to"
                                        class="form-control" disabled>
                                    <label for="date_transferred_to" class="label" style="font-size:10px;">Date
                                        Transferred To</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input_box">
                                    <input type="text" class="form-control" name="periodicity_poh" id="periodicity_poh"
                                        disabled>
                                    <label for="periodicity_poh" class="label" style="font-size:10px;">Periodicity
                                        POH</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input_box">
                                    <input type="text" class="form-control" name="status" id="status" disabled>
                                    <label for="status" class="label" style="font-size:10px;">Status</label>
                                </div>
                            </div>
                        </div>


                        <div class="form-row"style="margin-top:-10px;">
                            <div class="col-md-6">
                                <div class="input_box">
                                    <input type="text" class="form-control" name="db" id="db" disabled>
                                    <label for="db" class="label" style="font-size:10px;">DB</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input_box">
                                    <input type="date" id="return_date" name="return_date" class="form-control"
                                        disabled>
                                    <label for="return_date" class="label " style="font-size:10px;">Return
                                        Date</label>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <div class="coach-split-section">

                <div class="container-fluid mt-1">
                    <div class="container-fluid">
                        <div class="tablecard d-flex justify-content-between"
                            style="margin-left:-30px; margin-top:2px;">
                            <div class="table-container custom-scrollbar">
                                <table id="dynamic-table" style="font-size:10px;" class="custom-scrollbar coach-table">
                                    <thead>
                                        <tr>
                                            <!-- Table headers -->
                                            <th style="width:220px;">Coach Location</th>
                                            <th>Shop Activity</th>
                                            <th style="width:100px;">Date In</th>
                                            <th style="width:100px;">Date Out</th>
                                            <th style="width:100px;">Shifting Time</th>
                                            <th>Work Type</th>

                                        </tr>
                                    </thead>
                                    <tbody style="text-align:center;">
                                        <!-- Dynamic rows will be appended here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                // Include your database connection file
                include '../../db.php';

                // Function to fetch shops
                function getShops($conn)
                {
                    $query = "SELECT DISTINCT [Shop_Code], [Shop_Name] FROM [CoachMaster_V_2018].[dbo].[tbl_CMS_ShopInformation]";
                    $result = sqlsrv_query($conn, $query);
                    $shops = array();
                    if ($result !== false) {
                        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                            $shops[] = $row;
                        }
                    }
                    return $shops;
                }

                // Function to fetch lines for a given shop
                function getLines($conn, $shopCode)
                {
                    $query = "SELECT Line FROM [CoachMaster_V_2018].[dbo].[tbl_CMS_ShopInformation] WHERE Shop_Code = ?";
                    $params = array($shopCode);
                    $result = sqlsrv_query($conn, $query, $params);
                    $lines = array();
                    if ($result !== false) {
                        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                            $lines[] = $row['Line'];
                        }
                    }
                    return $lines;
                }

                // Function to get count for a given shop and line
                function getCount($conn, $shopCode, $line)
                {
                    $query = "SELECT Count FROM [CoachMaster_V_2018].[dbo].[tbl_CMS_ShopInformation] WHERE Shop_Code = ? AND Line = ?";
                    $params = array($shopCode, $line);
                    $result = sqlsrv_query($conn, $query, $params);
                    if ($result !== false) {
                        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                        return $row['Count'];
                    }
                    return 0;
                }

                // Fetch shops
                $shops = getShops($conn);

                // Get selected shop and line
                $selectedShop = isset($_POST['shop']) ? $_POST['shop'] : '';
                $selectedLine = isset($_POST['lineNumber']) ? $_POST['lineNumber'] : '';

                // Fetch lines if a shop is selected
                $lines = $selectedShop ? getLines($conn, $selectedShop) : array();

                // Fetch count if both shop and line are selected
                $count = ($selectedShop && $selectedLine) ? getCount($conn, $selectedShop, $selectedLine) : 0;
                ?>

                <div class="container-fluid">
                    <div class="boxcard" style="margin-left:-30px;  overflow: hidden;">

                        <div class="coach-form-container" style="border-color:blue; margin-top:-30px;">
                            <div class="coach-form-row">
                                <button onclick="toggleLocationFields(event)"
                                    style="font-size:10px; border-color:blue; border-radius:5px; border-width:1px; border-color:#0000FF; margin-top:25px; margin-left:40px; width:130px; height:30px;"
                                    class="changeLocation">
                                    <svg width="22px" height="22px">
                                        <path fill="white"
                                            d="M12 2c-4.42 0-8 .5-8 4v10a3 3 0 0 0 1 2.22V20a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1h6a8 8 0 0 1-1-3.5 5.55 5.55 0 0 1 2.38-4.5H6V6h12v4a4 4 0 0 1 .5 0 5.3 5.3 0 0 1 1.5.22V6c0-3.5-3.58-4-8-4M7.5 14A1.5 1.5 0 1 1 6 15.5 1.5 1.5 0 0 1 7.5 14m11-2a3.54 3.54 0 0 0-3.5 3.5c0 2.6 3.5 6.5 3.5 6.5s3.5-3.9 3.5-6.5a3.54 3.54 0 0 0-3.5-3.5m0 4.8a1.2 1.2 0 1 1 0-2.4 1.29 1.29 0 0 1 1.2 1.2 1.15 1.15 0 0 1-1.2 1.2" />
                                    </svg>
                                    Change Location
                                </button>
                            </div>
                        </div>


                        <div class="additional-fields hidden">
                            <div class="coach-form-row d-flex align-items-center"
                                style="margin-top:30px; margin-left:-10px;">
                                <label for="dateOut" style="font-size:10px; font-weight:bold;">Date Out:</label>
                                <input type="datetime-local" id="dateOut" name="dateOut"
                                    style="font-size:10px; border-color:blue; border-width:1px; border-color:#0000FF; width:130px; border-radius:5px; padding:1px; margin-left:30px; margin-top:-10px;">
                            </div>

                            <div class="coach-form-row" style="margin-left:-10px; margin-top:-5px;">
                                <label for="shop" style="font-size:10px; font-weight:bold;">Shop:</label>
                                <select id="shop" name="shop"
                                    style="font-size:10px; border-color:blue; border-width:1px; border-color:#0000FF; width:130px; border-radius:5px; height:20px; margin-left:42px; margin-top:-20px;">
                                    <option value="" disabled selected hidden></option>
                                    <?php foreach ($shops as $shop): ?>
                                        <option value="<?php echo $shop['Shop_Code']; ?>" <?php echo ($selectedShop == $shop['Shop_Code']) ? 'selected' : ''; ?>>
                                            <?php echo $shop['Shop_Name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="coach-form-row d-flex align-items-center mt-1" style="margin-left:-10px;">
                                <label for="lineNumber" style="font-size:10px; font-weight:bold;">LineNumber:</label>
                                <select id="lineNumber" name="lineNumber"
                                    style="font-size:10px; border-color:blue; border-width:1px; border-color:#0000FF; width:130px; border-radius:5px; padding:3px; margin-left:13px; margin-top:-10px;">
                                    <option value="" disabled selected hidden></option>
                                    <?php foreach ($lines as $line): ?>
                                        <option value="<?php echo $line; ?>" <?php echo ($selectedLine == $line) ? 'selected' : ''; ?>>
                                            <?php echo $line; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>



                            <div class="coach-form-row d-flex align-items-center mt-1" style="margin-left:-10px;">
                                <label for="newLocation" style="font-size:10px; font-weight:bold;">NewLocation:</label>
                                <select id="newLocation" name="newLocation"
                                    style="font-size:10px; border-color:blue; border-width:1px; border-color:#0000FF; border-radius:5px; margin-top:-2px; width:130px; padding:3px; margin-left:10px; margin-top:-10px;">
                                    <option value="" disabled selected hidden></option>
                                    <?php for ($i = 1; $i <= $count; $i++): ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>


                            <div class="coach-form-row d-flex align-items-center mt-1" style="margin-left:-10px;">
                                <label for="activity" style="font-size:10px; font-weight:bold;">Activity:</label>
                                <select id="activity" name="activity"
                                    style="font-size:10px; border-color:blue; border-width:1px; border-color:#0000FF; width:130px; border-radius:5px; height:20px; margin-left:35px; margin-top:-10px;">
                                    <option value="" disabled selected hidden></option>
                                    <?php foreach ($activities as $activity): ?>
                                        <option value="<?php echo htmlspecialchars($activity); ?>" <?php echo ($selectedActivity == $activity) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($activity); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>



                            <div class="coach-form-row " style="margin-left:30px; margin-top:-3px;">
                                <label for="dateIn" style="font-size:10px; font-weight:bold; margin-left:-43px;">Date
                                    In:</label>
                                <input type="datetime-local" id="dateIn" name="dateIn"
                                    style="font-size:10px; border-color:blue; border-width:1px; border-color:#0000FF; width:130px; border-radius:5px; padding:2px; margin-left:37px; margin-top:-10px;">
                            </div>

                            <div class="d-flex flex-row justify-content-center coach-form-row mt-1">
                                <button
                                    style="font-size:10px; border-width:1px; border-color:none; border-radius:5px; padding:3px; margin-bottom:3px; background-color:#00008B; color:white; padding:5px; margin-left:-25px;"
                                    type="button" onclick="updateLocation()"><b>Update</b></button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function toggleLocationFields(event) {
                        const additionalFields = document.querySelector('.additional-fields');
                        additionalFields.classList.toggle('hidden');
                    }

                    document.addEventListener("DOMContentLoaded", function () {
                        var dateOut = document.getElementById('dateOut');
                        var now = new Date();
                        var year = now.getFullYear();
                        var month = ('0' + (now.getMonth() + 1)).slice(-2);
                        var day = ('0' + now.getDate()).slice(-2);
                        var hours = ('0' + now.getHours()).slice(-2);
                        var minutes = ('0' + now.getMinutes()).slice(-2);

                        var currentDateTime = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
                        dateOut.value = currentDateTime;
                    });
                </script>

                <script>
                    function openNav() {
                        document.getElementById("mySidebar").classList.add("open");
                    }

                    function closeNav() {
                        document.getElementById("mySidebar").classList.remove("open");
                    }

                    document.addEventListener('click', function (event) {
                        var sidebar = document.getElementById('mySidebar');
                        var openBtn = document.querySelector('.openbtn');

                        if (!sidebar.contains(event.target) && !openBtn.contains(event.target)) {
                            closeNav();
                        }
                    });
                </script>


                <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
                    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
                    crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"
                    integrity="sha384-BDeGvOvxVH2M9fHAhrVZ3sCVyZESeIsfHfIYBEGi/lwDlKwF/9pFgpKcuX+kIqBk"
                    crossorigin="anonymous"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
                    integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
                    crossorigin="anonymous"></script>

                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        var dateOut = document.getElementById('dateIn');
                        var now = new Date();
                        var year = now.getFullYear();
                        var month = ('0' + (now.getMonth() + 1)).slice(-2);
                        var day = ('0' + now.getDate()).slice(-2);
                        var hours = ('0' + now.getHours()).slice(-2);
                        var minutes = ('0' + now.getMinutes()).slice(-2);

                        var currentDateTime = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
                        dateOut.value = currentDateTime;
                    });
                </script>

                <script>
                    function setCurrentDateTime() {
                        // Get the current date and time
                        const now = new Date();

                        // Format the date and time in the required format
                        const formattedDateTime = now.toISOString().slice(0, 16);

                        // Set the default value for Date In and Date Out fields
                        document.getElementById('dateIn').value = formattedDateTime;
                        document.getElementById('dateOut').value = formattedDateTime;
                    }
                </script>

                <script>
                    document.getElementById('searchForm').addEventListener('submit', function (e) {
                        e.preventDefault();
                        var searchValue = document.getElementById('searchInput').value.trim();
                        if (searchValue) {
                            var found = false;

                            var buttonCar = document.getElementById('button-car-' + searchValue);
                            var buttonPvc = document.getElementById('button-pvc-' + searchValue);
                            var buttonFeedline = document.getElementById('button-feedline-' + searchValue);
                            var buttonFeed = document.getElementById('button-feed-' + searchValue);
                            var buttonCor = document.getElementById('button-cor-' + searchValue);
                            var buttonPc = document.getElementById('button-pc-' + searchValue);
                            var buttonHcor = document.getElementById('button-hcor-' + searchValue);
                            var buttonAc = document.getElementById('button-ac-' + searchValue);
                            var button = document.getElementById('button-' + searchValue);

                            if (buttonCar) {
                                highlightButton(buttonCar);
                                found = true;
                            } else if (buttonPvc) {
                                highlightButton(buttonPvc);
                                found = true;
                            } else if (buttonFeedline) {
                                highlightButton(buttonFeedline);
                                found = true;
                            } else if (buttonFeed) {
                                highlightButton(buttonFeed);
                                found = true;
                            } else if (buttonCor) {
                                highlightButton(buttonCor);
                                found = true;
                            } else if (buttonPc) {
                                highlightButton(buttonPc);
                                found = true;
                            } else if (buttonHcor) {
                                highlightButton(buttonHcor);
                                found = true;
                            } else if (buttonAc) {
                                highlightButton(buttonAc);
                                found = true;
                            } else if (button) {
                                highlightButton(button);
                                found = true;
                            }

                            if (!found) {
                                alert('Coach ' + searchValue + ' not found.');
                                location.reload();
                            }
                        }
                    });

                    function highlightButton(button) {
                        button.classList.add('highlight');
                        button.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        setTimeout(function () {
                            button.classList.remove('highlight');
                        }, 10000);
                    }

                    function openPopup(coach) {
                        alert("Popup for coach: " + coach);

                    }
                </script>


                <script>
                    // Function to show the popup
                    function showPopup() {
                        document.getElementById('popupForm').style.display = 'flex';
                    }

                    // Function to close the popup and refresh the page
                    document.getElementById('closePopup').onclick = function () {
                        document.getElementById('popupForm').style.display = 'none';
                        location.reload();
                    }

                    // Example function to show the popup for demonstration purposes
                    window.onload = function () {
                        showPopup();
                    }
                </script>

                <script>
                    function toggleLocationFields(event) {
                        event.preventDefault();
                        var elements = document.getElementsByClassName('additional-fields');
                        for (var i = 0; i < elements.length; i++) {
                            elements[i].classList.toggle('hidden');
                        }
                    }

                    document.addEventListener('DOMContentLoaded', function () {
                        var popupForm = document.getElementById('popupForm');
                        if (popupForm) {
                            popupForm.style.display = 'none';
                        }
                    });

                    window.onload = function () {
                        var popupForm = document.getElementById('popupForm');
                        if (popupForm) {
                            popupForm.style.display = 'none';
                        }
                    };
                </script>
                <script>

                    document.getElementById('shop').addEventListener('change', function () {
                        updateLineDropdown();
                        updateActivityDropdown();
                    });

                    document.getElementById('lineNumber').addEventListener('change', function () {
                        updateLocationDropdown();
                    });

                    function updateLineDropdown() {
                        var shopCode = document.getElementById('shop').value;
                        var lineDropdown = document.getElementById('lineNumber');
                        lineDropdown.innerHTML = '<option value="" disabled selected hidden></option>';

                        // AJAX request to get lines for the selected shop
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function () {
                            if (this.readyState == 4 && this.status == 200) {
                                var lines = JSON.parse(this.responseText);
                                lines.forEach(function (line) {
                                    var option = document.createElement('option');
                                    option.value = line;
                                    option.text = line;
                                    lineDropdown.appendChild(option);
                                });
                            }
                        };
                        xhr.open("GET", "get_lines.php?shop=" + shopCode, true);
                        xhr.send();
                    }

                    function updateActivityDropdown() {
                        var shopCode = document.getElementById('shop').value;
                        var activityDropdown = document.getElementById('activity');
                        activityDropdown.innerHTML = '<option value="" disabled selected hidden></option>';

                        // AJAX request to get activities for the selected shop
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function () {
                            if (this.readyState == 4 && this.status == 200) {
                                var activities = JSON.parse(this.responseText);
                                activities.forEach(function (activity) {
                                    var option = document.createElement('option');
                                    option.value = activity;
                                    option.text = activity;
                                    activityDropdown.appendChild(option);
                                });
                            }
                        };
                        xhr.open("GET", "get_activities_pos.php?shop=" + shopCode, true);
                        xhr.send();
                    }

                    function updateLocationDropdown() {
                        var shopCode = document.getElementById('shop').value;
                        var lineNumber = document.getElementById('lineNumber').value;
                        var locationDropdown = document.getElementById('newLocation');
                        locationDropdown.innerHTML = '<option value="" disabled selected hidden></option>';

                        // AJAX request to get count for the selected shop and line
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function () {
                            if (this.readyState == 4 && this.status == 200) {
                                var count = parseInt(this.responseText);
                                for (var i = 1; i <= count; i++) {
                                    var option = document.createElement('option');
                                    option.value = i;
                                    option.text = i;
                                    locationDropdown.appendChild(option);
                                }
                            }
                        };
                        xhr.open("GET", "get_count.php?shop=" + shopCode + "&line=" + lineNumber, true);
                        xhr.send();
                    }

                    function updateRecord() {
                        // Implement the logic for updating the record
                        alert("Record updated successfully!");
                    }
                </script>


                <script>
                    function closePage() {
                        window.close();
                    }

                    function updateRecord() {
                        alert("Record updated successfully!");
                    }
                </script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        document.getElementById('searchForm').addEventListener('submit', function (event) {
                            var searchValue = document.getElementById('searchInput').value.trim();
                            if (!searchValue) {
                                event.preventDefault(); // Prevent form submission
                                // Reload the page if search input is empty
                            }
                        });
                    });
                </script>

                <style>
                    .hidden {
                        display: none;
                    }
                </style>

                <script src="pos.js"></script>
            </div>
</body>

</html>
