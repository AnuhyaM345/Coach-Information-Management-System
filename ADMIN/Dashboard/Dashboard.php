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
    <title>Home</title>
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
            background-color: #00008B;
            color: white;
            border-radius: 5px;
            margin-left:1000px;
        }
        input[type="text"] {
            border: 2px solid #00008B;
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
            margin: 0;
            padding: 0;
        }
        
        .input_box {
            position: relative;
            margin: 10px 0;
            font-weight: bold;
            
        }
        .input_box select,
        .input_box input[type="text"],
        .input_box input[type="date"]{
            width: 100%;
            padding: 10px;
            border: 1px solid #0000FF;
            border-radius: 10px;
            font-size: 12px;
            outline: none;
            height: 36px;
            color: black;
            font-weight: 500;
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
        .input_box select:focus + label,
        .input_box select:not([value=""]):not([value="undefined"]) + label {
            top: -10px;
            left: 10px;
            font-size: 12px;
            color: #808080;
        }
        .input_box select:focus,
        .input_box input[type="date"]:focus {
            outline: none;
            border-bottom: 2px solid #5264AE;
        }
        .col-md-1-5 {
            flex: 0 0 12.5%;
            max-width: 12.5%;
        }
        .col-md-1-75 {
            flex: 0 0 11.25%;
            max-width: 11.25%;
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

        .image1 {
            height: 45px;
            width: 45px;
            background-size: cover;
            border-radius:50%;
            margin-left:30px;
            margin-top: -20px;
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
                <p class="navbar-brand mb-0">CIMS - Home</p>
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
<br>

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


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-BDeGvOvxVH2M9fHAhrVZ3sCVyZESeIsfHfIYBEGi/lwDlKwF/9pFgpKcuX+kIqBk" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>