<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../check_session.php';
include '../../db.php';

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Master</title>
    <link rel="icon" href="../../SCR_Logo.png" type="image/jpg">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<style>
        .container {
            margin: 20px auto;
            max-width: 1200px;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-group select, .form-group input[type="text"] {
            width: 100%;
        }
        .form-row {
            margin-bottom: 10px;
        }
        .btn {
            padding: 5px 10px;
            font-size: 16px;
            background-color: red;
            color: yellow;
            border-radius: 5px;
            margin-left:1000px;
        }
        input[type="text"] {
            border: 2px solid #00008b;
            border-radius: 10px;
            outline: none;
            font-size: 16px;
            color: black;
        }
        select {
            font-size: 16px;
        }
        
        .image {
            height: 45px;
            width: 45px;
            background-size: cover;
            border-radius:50%;
        }
        body {
            background-color: #f0f4f8;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }
        
        .input_box {
            position: relative;
            margin: 7px 0;
            font-weight: bold;
            
        }
        .input_box select,
        .input_box input[type="text"],
        .input_box input[type="date"],
        .input_box input[type="datetime-local"]{
            width: 100%;
            padding: 7px;
            border: 1px solid #0000ff;
            border-radius: 10px;
            font-size: 12px;
            outline: none;
            height: 32px;
            color: black;
            font-weight:600;
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
        .input_box input[type="text"]:focus + label,
        .input_box input[type="text"]:not(:placeholder-shown) + label,
        .input_box input[type="date"]:focus + label,
        .input_box input[type="date"]:not(:placeholder-shown) + label,
        .input_box input[type="datetime-local"]:not(:placeholder-shown) + label,
        .input_box select:focus + label,
        .input_box select:not([value=""]):not([value="undefined"]) + label {
            top: -10px;
            left: 10px;
            font-size: 12px;
            color: #808080;
        }
        .input_box select:focus,
        .input_box input[type="date"]:focus,
        .input_box input[type="datetime-local"]:focus {
            outline: none;
            border-bottom: 2px solid #5264AE;
        }
        .col-md-1-25 {
            flex: 0 0 10.5%;
            max-width: 10.5%;
        }
        .col-md-1-5 {
            flex: 0 0 12.5%;
            max-width: 12.5%;
        }

        .col-md-2-5 {
            flex: 0 0 20.833333%;
            max-width: 20.833333%;
        }

        .col-md-3-5 {
            flex: 0 0 29%;
            max-width: 29%;
        }
        
        .navbar {
            height: 60px; 
            background-color: #00008B; 
            color: white;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        .navbar-brand,
        .navbar-nav .nav-link {
            color: white;
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
        
        .saveBtn {
            font-family: 'Montserrat', sans-serif;
            font-size: 18px;
            background: #00008B;
            color: white;
            padding: 10px 20px;
            padding-left: 0.9em;
            display: flex;
            align-items: center;
            border: none;
            border-radius: 25px;
            overflow: hidden;
            transition: all 0.2s;
            cursor: pointer;
            font-weight: 600;
            height: 36px;
            width: 100px;
            background-size: cover;
            outline: none;
            margin-left:50px;
        }

        .saveBtn span {
            display: block;
            margin-left: 0.3em;
            transition: all 0.3s ease-in-out;
        }

        .saveBtn svg {
            display: block;
            transform-origin: center center;
            transition: transform 0.3s ease-in-out;
        }

        .saveBtn:hover .svg-wrapper {
            animation: fly-1 0.6s ease-in-out infinite alternate;
        }

        .saveBtn:hover svg {
            transform: translateX(1.2em) rotate(0deg) scale(1.1);
        }

        .saveBtn:hover span {
            transform: translateX(5em);
        }

        .saveBtn:active {
            transform: scale(0.95);
        }

        @keyframes fly-1 {
            from {
                transform: translateY(0.1em);
            }
            to {
                transform: translateY(-0.1em);
            }
        }
        .saveBtn:focus {
            outline: none;
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

        .addbutton {
        font-family: 'Montserrat', sans-serif;
        font-size: 18px;
        background: #00008B;
        position: relative;
        height: 36px;
        width: 110px;
        cursor: pointer;
        display: flex;
        align-items: center;
        border: none;
        outline: none;
        border-radius: 25px;
        /* margin-left: auto; */
        }

        .addbutton, .addbutton_icon, .addbutton_text {
        transition: all 0.3s;
        }

        .addbutton .addbutton__text {
        transform: translateX(45px);
        color: #fff;
        font-weight: 600;
        transition: all 0.3s ease-in-out;
        }

        .addbutton .addbutton__icon {
        position: absolute;
        height: 36px;
        width: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease-in-out;
        }

        .addbutton .svg {
        width: 30px;
        stroke: #fff;
        }

        .addbutton:hover {
        background: #00008b;
        }


        .addbutton:hover .addbutton__text {
        transform: translateX(5em);
        color: transparent;
        }

        .addbutton:hover .addbutton__icon {
        width: 100px;
        transform: translateX(0);
        animation: fly-1 0.6s ease-in-out infinite alternate;
        }

        @keyframes fly-1 {
            from {
                transform: translateY(0.1em);
            }
            to {
            transform: translateY(-0.1em);
            }
        }

        .addbutton:active .addbutton__icon {
        background-color: none;
        }

        .addbutton:active {
        border: 1px solid #00008b;
        }

        .addbutton:focus {
            outline:none;
        }

        .delbutton {
        font-family: 'Montserrat', sans-serif;
        font-size: 18px;
        position: relative;
        height: 36px;
        width: 110px;
        cursor: pointer;
        display: flex;
        align-items: center;
        border: none;
        outline: none;
        border-radius: 25px;
        background-color: #00008b;
        overflow: hidden;
        margin-left: -500px;
        }

        .delbutton, .delbutton_icon, .delbutton_text {
        transition: all 0.3s;
        }

        .delbutton .delbutton__text {
        transform: translateX(35px);
        color: white;
        font-weight: 600;
        }

        .delbutton .delbutton__icon {
        position: absolute;
        height: 36px;
        width: 39px;
        display: flex;
        align-items: center;
        justify-content: center;
        }

        .delbutton .svg {
        width: 20px;
        }

        .delbutton:hover {
        background: #00008b;
        }

        .delbutton:hover .delbutton__text {
        color: transparent;
        transform: translateX(5em);
        }

        .delbutton:hover .delbutton__icon {
        width: 100px;
        transform: translateX(0);
        animation: fly-1 0.6s ease-in-out infinite alternate;
        }

        .delbutton:active .delbutton__icon {
        background-color: none;
        }

        @keyframes fly-1 {
            from {
                transform: translateY(0.1em);
            }
            to {
            transform: translateY(-0.1em);
            }
        }

        .delbutton:active {
        border: 1px solid #00008b;
        background-color: #00008b;
        }

        .delbutton:focus {
            outline:none;
            box-shadow: none;
            background-color: #00008b;
        }

        .cimsp {
            color: white;
            font-size: 20px;
            
        }

        .image1 {
            height: 45px;
            width: 45px;
            background-size: cover;
            border-radius:50%;
            margin-left:30px;
            margin-top: -20px;
        }
        .datacard {
            height:auto;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 5px;
            padding-top:12px;
            padding-bottom:0px;
            background-color: #f9f9f9;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4);
            margin-bottom: 9px; 
        }
        .card {
            height: 130px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            padding-top: 10px;
            padding-bottom: 0px;
            background-color: #f9f9f9;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4);
            margin-bottom: 7px;
        }
        .container-fluid.d-flex.justify-content-center {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .searchcard {
            height: 50px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            padding-top: 24px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
            margin-bottom: 8px;
            margin-top: 8px;
        }

    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div class="d-flex align-items-center">
            <button class="openbtn" onclick="openNav()">☰</button> 
            <img src="../../SCR_Logo.jpg" class="image mr-2" alt="Logo">
            <p class="navbar-brand mb-0">CIMS - Coach Master</p>
            </div>
            <div class="d-flex align-items-center">
                <p class="mb-0 text-right mr-3">कार्यशाला सूचना प्रणाली <br> Workshop Information System</p>
                <a href="../../logout.php">   
                    <button class="logoutBtn">
                        <div class="sign">
                            <svg viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path></svg>
                        </div>
                        <div class="text">Logout</div>
                    </button>
                </a>
            </div>
        </div>
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
        <a href="../Coach_Master/CoachMaster.php">Coach Master</a>
        <a href="../Holdings/Holdings.php">Holdings</a>
        <a href="../Progress_POH/POHprogress.php">POH Progress</a>
        <a href="../RS_certificate/RS_Certificate.php">RS Certificate</a>
    </div>
    
    <div class="welcome-message">
        Welcome,<br><?php echo htmlspecialchars($username); ?>!
    </div>
</div>

<script src="home.js"></script>


<div class= "container-fluid mt-1">
    <div class="searchcard d-flex justify-content-between">
        <form class="mb-3 d-flex align-items-center" id="searchform">
            <input type="text" name="search" class="form-control" id="search" placeholder="Search Coach No" style="width: 200px; font-size: 14px;">
            <button class="searchbutton">
            <svg class="svgIcon" viewBox="0 0 512 512" height="1em" xmlns="http://www.w3.org/2000/svg">
            <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm50.7-186.9L162.4 380.6c-19.4 7.5-38.5-11.6-31-31l55.5-144.3c3.3-8.5 9.9-15.1 18.4-18.4l144.3-55.5c19.4-7.5 38.5 11.6 31 31L325.1 306.7c-3.2 8.5-9.9 15.1-18.4 18.4zM288 256a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z" fill="white"></path>
            </svg>
            Search
            </button>
        </form>
    </div>
</div>    


<div class= "container-fluid">
    <form id="form1" method="post" action=" ">
        <div class="datacard">
            <div class= "container-fluid">
                <div class="form-row" >
                    <div class="input_box col-md-1-5">
                        <input type="text" class="form-control" name="coachID" id="coachID" size="15" maxlength="15">
                        <label for="coachID" class="label">Coach ID</label>
                    </div>

                    <div class="input_box col-md-1-5">
                        <input type="text" class="form-control" name="coachNo" id="coachNo" size="10" maxlength="10">
                        <label for="coachNo" class="label">Coach No</label>
                    </div>

                    <div class="input_box col-md-1-5">
                        <input type="text" class="form-control" name="oldCoachNo" id="oldCoachNo" size="10" maxlength="10">
                        <label for="oldCoachNo" class="label">Old Coach No</label>
                    </div>

                    <div class="input_box col-md-4">
                        <select id="code" name="code" class="form-control">
                            <option value="" disabled selected hidden></option>
                            <?php
                            include '../../db.php';
                            // Assuming $conn is your database connection
                            if ($conn) {
                                // Query to retrieve data from the database
                                $query = "SELECT CoachType, Description FROM dbo.tbl_CMS_TransportationCodeMaster";
                                $result = sqlsrv_query($conn, $query);

                                if ($result !== false) {
                                    // Output data of each row
                                    echo '<option value="" selected hidden></option>';
                                    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                        echo '<option value="' . $row['CoachType'] . '" >' . $row['CoachType'] . ' - ' . $row['Description'] . '</option>';

                                    }
                                    
                                } else {
                                    echo '<option value="" selected hidden>Error fetching data</option>';
                                    die(print_r(sqlsrv_errors(), true));
                                }
                            } else {
                                echo '<option value="" selected hidden>Error connecting to database</option>';
                            }
                            ?>
                        </select>
                        <label for="code" class="label">Code</label>
                    </div>
                    
                    <div class="input_box col-md-1">
                        <select id="type" name="type" class="form-control">
                            <option value="" disabled selected hidden></option>
                            <?php
                            include '../../db.php';
                            // Assuming $conn is your database connection
                            if ($conn) {
                                // Query to retrieve data from the database
                                $query = "SELECT type FROM dbo.tbl_CMS_TypeMaster";
                                $result = sqlsrv_query($conn, $query);

                                if ($result !== false) {
                                    // Output data of each row
                                    echo '<option value="" selected hidden></option>';
                                    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                        echo '<option value="' . $row['type'] . '" >' . $row['type'] .'</option>';  
                                    }
                                } else {
                                    echo '<option value="" selected hidden>Error fetching data</option>';
                                    die(print_r(sqlsrv_errors(), true));
                                }
                            } else {
                                echo '<option value="" selected hidden>Error connecting to database</option>';
                            }
                            ?>
                        </select>
                        <label for="type" class="label">Type</label>
                    </div>
                    <div class="input_box col-md-2-5">
                        <select id="railway" name="railway" class="form-control">
                            <option value="" disabled selected hidden></option>
                            <?php
                            include '../../db.php';
                            // Assuming $conn is your database connection
                            if ($conn) {
                                // Query to retrieve data from the database
                                $query = "SELECT Zone,Description FROM dbo.tbl_CMS_ZoneMaster";
                                $result = sqlsrv_query($conn, $query);

                                if ($result !== false) {
                                    // Output data of each row
                                    echo '<option value="" selected hidden></option>';
                                    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                        echo '<option value="' . $row['Zone'] . '" >' . $row['Zone'] . ' - ' . $row['Description'] . '</option>';
                                    }
                                } else {
                                    echo '<option value="" selected hidden>Error fetching data</option>';
                                    die(print_r(sqlsrv_errors(), true));
                                }
                            } else {
                                echo '<option value="" selected hidden>Error connecting to database</option>';
                            }
                            ?>
                        </select>
                        <label for="railway" class="label">Railway</label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="input_box col-md-1">
                        <select id="vehicleType" name="vehicleType" class="form-control" .>
                            <option value="" disabled selected hidden></option>
                            <?php
                            include '../../db.php';
                            // Assuming $conn is your database connection
                            if ($conn) {
                                // Query to retrieve data from the database
                                $query = "SELECT vehicle_type FROM dbo.tbl_CMS_VehTypeMaster";
                                $result = sqlsrv_query($conn, $query);

                                if ($result !== false) {
                                    // Output data of each row
                                    echo '<option value="" selected hidden></option>';
                                    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                        echo '<option value="' . $row['vehicle_type'] . '">' . $row['vehicle_type'] .'</option>';
                                    }
                                } else {
                                    echo '<option value="" selected hidden>Error fetching data</option>';
                                    die(print_r(sqlsrv_errors(), true));
                                }
                            } else {
                                echo '<option value="" selected hidden>Error connecting to database</option>';
                            }
                            ?>
                        </select>
                        <label for="vehicleType" class="label">Vehicle Type</label>
                    </div>

                    <div class="input_box col-md-1-5">
                        <select id="category" name="category" class="form-control">
                            <option value="" disabled selected hidden></option>
                            <?php
                                include '../../db.php';
                                // Assuming $conn is your database connection
                                if ($conn) {
                                    // Query to retrieve data from the database
                                    $query = "SELECT Category FROM dbo.tbl_CMS_CategoryMaster";
                                    $result = sqlsrv_query($conn, $query);

                                    if ($result !== false) {
                                        // Output data of each row
                                        echo '<option value="" selected hidden></option>';
                                        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                            echo '<option value="' . $row['Category'] . '">' . $row['Category'] .'</option>';
                                        }
                                    } else {
                                        echo '<option value="" selected hidden>Error fetching data</option>';
                                        die(print_r(sqlsrv_errors(), true));
                                    }
                                } else {
                                    echo '<option value="" selected hidden>Error connecting to database</option>';
                                }
                                ?>
                        </select>
                        <label for="category" class="label">Category</label>
                    </div>
                    <div class="input_box col-md-1">
                        <select id="AC_Flag" name="AC_Flag" class="form-control">
                            <option value="" disabled selected hidden></option>
                            <?php
                                        include ('../../db.php');

                                        if ($conn) {
                                            $query = "SELECT ac_flag FROM tbl_CMS_ACFlagMaster";
                                            $result = sqlsrv_query($conn, $query);

                                            if ($result !== false) {
                                                echo '<option value="" selected hidden></option>';
                                                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                    echo '<option value="' . $row['ac_flag'] . '">' . $row['ac_flag'] . '</option>';
                                                }
                                            } else {
                                                echo '<option value="" selected hidden>Error fetching data</option>';
                                                die(print_r(sqlsrv_errors(), true));
                                            }
                                        } else {
                                            echo '<option value="" selected hidden>Error connecting to database</option>';
                                        }
                                        ?>
                        </select>
                        <label for="AC_Flag" class="label">AC Flag</label>
                    </div>
                    <div class="input_box col-md-1-5">
                        <select id="brakesystem" name="brakesystem" class="form-control">
                            <option value="" disabled selected hidden></option>
                            <?php
                                include '../../db.php';
                                // Assuming $conn is your database connection
                                if ($conn) {
                                    // Query to retrieve data from the database
                                    $query = "SELECT brake_system FROM dbo.tbl_CMS_BrakeSystemMaster";
                                    $result = sqlsrv_query($conn, $query);

                                    if ($result !== false) {
                                        // Output data of each row
                                        echo '<option value="" selected hidden></option>';
                                        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                            echo '<option value="'.$row['brake_system'].'">' . $row['brake_system'] .'</option>';
                                        }
                                    } else {
                                        echo '<option value="" selected hidden>Error fetching data</option>';
                                        die(print_r(sqlsrv_errors(), true));
                                    }
                                } else {
                                    echo '<option value="" selected hidden>Error connecting to database</option>';
                                }
                                ?>
                        </select>
                        <label for="brakesystem" class="label">Brake System</label>
                    </div>

                    <div class="input_box col-md-1-5">
                        <input type="datetime-local" class="form-control" name="builtDate" id="builtDate">
                        <label for="builtDate" class="label">Built Date</label> 
                    </div>

                    <div class="input_box col-md-1">
                        <input type="text" class="form-control" name="built" id="built" value="<?= isset($_GET['built']) ? htmlspecialchars($_GET['built']) : '' ?>">
                        <label for="built" class="label">Built</label> 
                    </div>

                    <div class="input_box col-md-1-5">
                        <input type="datetime-local" class="form-control" name="inductionDate" id="inductionDate">
                        <label for="inductionDate" class="label">Induction Date</label> 
                    </div>

                    <div class="input_box col-md-1-5">
                        <select id="periodicity" name="periodicity" class="form-control">
                        <option value="" disabled selected hidden></option>
                        <?php
                                include '../../db.php';
                                // Assuming $conn is your database connection
                                if ($conn) {
                                    // Query to retrieve data from the database
                                    $query = "SELECT periodicity FROM dbo.tbl_CMS_PeriodicityMaster";
                                    $result = sqlsrv_query($conn, $query);

                                    if ($result !== false) {
                                        echo '<option value="" selected hidden></option>';

                                        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                            echo '<option value="' . $row['periodicity'] . '">' .$row['periodicity'] .'</option>';
                                        }
                                    } else {
                                        echo '<option value="" selected hidden>Error fetching data</option>';
                                        die(print_r(sqlsrv_errors(), true));
                                    }
                                } else {
                                    echo '<option value="" selected hidden>Error connecting to database</option>';
                                }
                            ?>
    
                        </select>
                        <label for="periodicity" class="label">Periodicity</label>
                    </div>
                    <div class="input_box col-md-1-5">
                        <input type="text" class="form-control" name="Tareweight" id="Tareweight" >
                        <label for="Tareweight" class="label">Tare weight</label>
                    </div>
                </div>
                <div class="form-row" >
                    <div class="input_box col-md-1">
                        <select id="owningDivision" name="owningDivision" class="form-control">
                            <option value="" disabled selected hidden></option>
                            <?php
                                include '../../db.php';
                                // Assuming $conn is your database connection
                                if ($conn) {
                                    // Query to retrieve data from the database
                                    $query = "SELECT DivCode FROM dbo.tbl_CMS_DivisionMaster";
                                    $result = sqlsrv_query($conn, $query);

                                    if ($result !== false) {
                                        // Output data of each row
                                        echo '<option value="" selected hidden></option>';

                                        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                            echo '<option value="' . $row['DivCode'] . '">' . $row['DivCode'].'</option>';
                                        }
                                    } else {
                                        echo '<option value="" selected hidden>Error fetching data</option>';
                                        die(print_r(sqlsrv_errors(), true));
                                    }
                                } else {
                                    echo '<option value="" selected hidden>Error connecting to database</option>';
                                }
                            ?>
                        </select>
                        <label for="owningDivision" class="label"> Division</label>
                    </div>
                    <div class="input_box col-md-1">
                        <select id="baseDepot" name="baseDepot" class="form-control">
                            <option value="" disabled selected hidden></option>
                            <?php
                                include '../../db.php';
                                // Assuming $conn is your database connection
                                if ($conn) {
                                    // Query to retrieve data from the database
                                    $query = "SELECT DptCode FROM dbo.tbl_CMS_DepotMaster";
                                    $result = sqlsrv_query($conn, $query);

                                    if ($result !== false) {
                                        // Output data of each row
                                        echo '<option value="" selected hidden></option>';
                                        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                            echo '<option value="' . $row['DptCode'] . '">' . $row['DptCode'] .'</option>';
                                        }
                                    } else {
                                        echo '<option value="" selected hidden>Error fetching data</option>';
                                        die(print_r(sqlsrv_errors(), true));
                                    }
                                } else {
                                    echo '<option value="" selected hidden>Error connecting to database</option>';
                                }
                            ?>
                        </select>
                        <label for="baseDepot" class="label"> Depot</label>
                    </div>
                    <div class="input_box col-md-3-5">
                        <select id="workshop" name="workshop" class="form-control">
                        <?php
                                include '../../db.php';
                                // Assuming $conn is your database connection
                                if ($conn) {
                                    // Query to retrieve data from the database
                                    $query = "SELECT WorkShopCode,Workshop FROM dbo.tbl_CMS_WorkshopMaster";
                                    $result = sqlsrv_query($conn, $query);

                                    if ($result !== false) {
                                        // Output data of each row
                                        echo '<option value="" selected hidden></option>';
                                        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                            echo '<option value="' . $row['WorkShopCode'] . '">' . $row['WorkShopCode'] .' - '.$row['Workshop'].'</option>';
                                        }
                                    } else {
                                        echo '<option value="" selected hidden>Error fetching data</option>';
                                        die(print_r(sqlsrv_errors(), true));
                                    }
                                } else {
                                    echo '<option value="" selected hidden>Error connecting to database</option>';
                                }
                        ?>
                        </select>
                        <label for="workshop" class="label">Workshop</label>
                    </div>
                    <div class="input_box col-md-1">
                        <select id="codalLife" name="codalLife" class="form-control">
                            <option value="" disabled selected hidden></option>
                            <?php
                                include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT codal_life FROM dbo.tbl_CMS_CodalLifeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="' . $row['codal_life'] . '">' . $row['codal_life'] .'</option>';
                                            }
                                        } else {
                                            echo '<option value="" selected hidden>Error fetching data</option>';
                                            die(print_r(sqlsrv_errors(), true));
                                        }
                                    } else {
                                        echo '<option value="" selected hidden>Error connecting to database</option>';
                                    }
                            ?>
                        </select>
                        <label for="codalLife" class="label">Codal Life</label>
                    </div>
                    
                    <div class="input_box col-md-1-5">
                        <select id="powerGenerationType" name="powerGenerationType" class="form-control">
                            <option value="" disabled selected hidden></option>
                            <?php
                                include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT power_gen FROM dbo.tbl_CMS_PowerGenMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="' . $row['power_gen']. '">'. $row['power_gen'] .'</option>';
                                            }
                                        } else {
                                            echo '<option value="" selected hidden>Error fetching data</option>';
                                            die(print_r(sqlsrv_errors(), true));
                                        }
                                    } else {
                                        echo '<option value="" selected hidden>Error connecting to database</option>';
                                    }
                                ?>
                        </select>
                        <label for="powerGenerationType" class="label">Generation Type</label>
                    </div>
                    <div class="input_box col-md-1-5">
                        <select id="couplingType" name="couplingType" class="form-control">
                            <option value="" disabled selected hidden></option>
                            <?php
                                include '../../db.php';
                                // Assuming $conn is your database connection
                                if ($conn) {
                                    // Query to retrieve data from the database
                                    $query = "SELECT Coupling_type FROM dbo.tbl_CMS_Coupling_type";
                                    $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="' . $row['Coupling_type'] . '" >' .  $row['Coupling_type'].'</option>';
                                            }
                                        } else {
                                            echo '<option value="" selected hidden>Error fetching data</option>';
                                            die(print_r(sqlsrv_errors(), true));
                                        }
                                    } else {
                                        echo '<option value="" selected hidden>Error connecting to database</option>';
                                    }
                                ?>
                        </select>
                        <label for="couplingType" class="label">Coupling Type</label>
                    </div>
            
                    <div class="input_box col-md-1-25">
                        <input type="text" class="form-control" name="Seating" id="Seating"> 
                        <label for="Seating" class="label">Seating</label>
                    </div>
                    <div class="input_box col-md-1-25">
                        <input type="text" class="form-control" name="Sleeping" id="Sleeping"> 
                        <label for="Sleeping" class="label">Sleeping</label>
                    </div>
                </div>
            </div>
        </div>
            
       
            <div class="card">
                <div class="form-row mr-1 ml-0" >
                    <div class="input_box col-md-4">
                        <input type="text" class="form-control" name="conversionLr" id="conversionLr" size="10" maxlength="10" value="<?= isset($_GET['conversionLr']) ? htmlspecialchars($_GET['conversionLr']) : '' ?>">
                        <label for="conversionLr" class="label">Conversion Lr</label>
                    </div>
                    <div class="input_box col-md-2">
                        <input type="text" class="form-control" name="conversionDate" id="conversionDate" size="10" maxlength="10" value="<?= isset($_GET['conversionDate']) ? htmlspecialchars($_GET['conversionDate']) : '' ?>">
                        <label for="conversionDate" class="label">Conversion Date</label>
                    </div>

                    <div class="input_box col-md-1-5">
                        <select id="transferredFrom" name="transferredFrom" class="form-control">
                            <option value="" disabled selected hidden></option>
                        </select>
                        <label for="transferredFrom" class="label">Transferred From</label>
                    </div>

                    <div class="input_box col-md-1-5">
                        <input type="text" class="form-control" name="transferredFromDate" id="transferredFromDate" size="10" maxlength="10" value="<?= isset($_GET['transferredFromDate']) ? htmlspecialchars($_GET['transferredFromDate']) : '' ?>">
                        <label for="transferredFromDate" class="label">Date</label>
                    </div>

                    <div class="input_box col-md-1-5">
                        <select id="transferredTo" name="transferredTo" class="form-control">
                            <option value="" disabled selected hidden></option>  
                        </select>
                        <label for="transferredTo" class="label">Transferred To</label>
                    </div>

                    <div class="input_box col-md-1-5">
                        <input type="text" class="form-control" name="transferredToDate" id="transferredToDate" size="10" maxlength="10" value="<?= isset($_GET['transferredToDate']) ? htmlspecialchars($_GET['transferredToDate']) : '' ?>">
                        <label for="transferredToDate" class="label">Date</label>
                    </div>
                </div>

                <div class="form-row mr-1 ml-0" >
                    <div class="input_box col-md-4">
                        <input type="text" class="form-control" name="condemnationNo" id="condemnationNo" size="10" maxlength="10" value="<?= isset($_GET['condemnationNo']) ? htmlspecialchars($_GET['condemnationNo']) : '' ?>">
                        <label for="condemnationNo" class="label">Condemnation No</label>
                    </div>
                    <div class="input_box col-md-2">
                        <input type="text" class="form-control" name="condemnationDate" id="condemnationDate" size="10" maxlength="10" value="<?= isset($_GET['condemnationDate']) ? htmlspecialchars($_GET['condemnationDate']) : '' ?>">
                        <label for="condemnationDate" class="label">Date</label>
                    </div>
                    <div class="input_box col-md-6">
                        <input type="text" class="form-control" name="remarks" id="remarks" size="10" maxlength="10" value="<?= isset($_GET['remarks']) ? htmlspecialchars($_GET['remarks']) : '' ?>">
                        <label for="remarks" class="label">Remarks</label>
                    </div>
                </div>
            </div>

            <div style="margin-top:45px;">

            <div class="form-row">
                <div class="container-fluid d-flex justify-content-center">

                    <div>
                        <button type="click" class="addbutton" id="addbutton">
                        <span class="addbutton__text">Add</span>
                        <span class="addbutton__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="white" d="M12 4a1 1 0 0 1 1 1v6h6a1 1 0 1 1 0 2h-6v6a1 1 0 1 1-2 0v-6H5a1 1 0 1 1 0-2h6V5a1 1 0 0 1 1-1" /></svg>
                        </span>
                        </button>
                    </div>

                    <div>
                        <button class="saveBtn">
                        <div class="svg-wrapper-1">
                            <div class="svg-wrapper">
                            <svg
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                stroke-linejoin="round"
                                stroke-linecap="round"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                fill="none"
                            >
                                <path
                                d="m19,21H5c-1.1,0-2-.9-2-2V5c0-1.1.9-2,2-2h11l5,5v11c0,1.1-.9,2-2,2Z"
                                stroke-linejoin="round"
                                stroke-linecap="round"
                                data-path="box"
                                ></path>
                                <path
                                d="M7 3L7 8L15 8"
                                stroke-linejoin="round"
                                stroke-linecap="round"
                                data-path="line-top"
                                ></path>
                                <path
                                d="M17 20L17 13L7 13L7 20"
                                stroke-linejoin="round"
                                stroke-linecap="round"
                                data-path="line-bottom"
                                ></path>
                            </svg>
                            </div>
                        </div>
                        <span>Save</span>
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openNav() {
        document.getElementById("mySidebar").classList.add("open");
    }

    function closeNav() {
        document.getElementById("mySidebar").classList.remove("open");
    }

    document.addEventListener('click', function(event) {
            var sidebar = document.getElementById('mySidebar');
            var openBtn = document.querySelector('.openbtn');

            if (!sidebar.contains(event.target) && !openBtn.contains(event.target)) {
                closeNav();
            }
        });
    </script>

</body>
</html>