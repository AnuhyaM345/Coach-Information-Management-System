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
    <title>Holdings</title>
    <link rel="icon" href="../../SCR_Logo.png" type="image/jpg">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<style>
        .container {
            margin: 20px auto;
            max-width: 1200px;
            box-sizing: border-box;
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
       
        .image {
            height: 45px;
            width: 45px;
            background-size: cover;
            border-radius:50%;
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

        body {
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }


        html {
            height: 100%;
        }

        .title {
            background-color: #f1f2f6;
            padding: 10px 10px;
            color: black;
            font-size: 25px;
            text-align: left;
            margin: 0;
            font-weight: 500;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 1rem;
            z-index: 1;
        }

        .table-container {
            overflow-x: auto;
            flex-grow: 1;
            padding: 0;
            position: relative;
        }

        .table-container::-webkit-scrollbar {
            width: 6px; /* Vertical scrollbar width */
            height: 6px; /* Horizontal scrollbar height */
        }

        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1; /* Background of the scrollbar track */
        }

        .table-container::-webkit-scrollbar-thumb {
            background: #888; /* Color of the scrollbar thumb */
            border-radius: 10px; /* Roundness of the scrollbar thumb */
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: #555; /* Color of the scrollbar thumb on hover */
        }   

        table {
            border-collapse: collapse;
            width: 100%;
            box-sizing: border-box;
            margin: 0;
            table-layout: fixed;
            width: 300%;
        }

        thead{
            height:64px;
        }

        th, td {
            padding: 8px;
            text-align: left;
            white-space: nowrap;
        }

        th {
            background-color: white;
            color: red;
            font-weight: 500;
            position: sticky;
            top: 0;
            z-index: 1;
        }      
        
        tr:nth-child(even) {
            background-color: #e6e6e6;
        }

        tr:hover {
            background-color: #bfbfbf;
        }

        .search-row input {
            width: 100%;
            padding: 5px;
            border-radius: 3px;
            box-sizing: border-box;
            transition: background-color 0.3s, transform 0.3s;
            border-radius: 10px;
        }

        .search-row {
            color: white;
            font-weight: 600;
            margin: 0;
            padding: 0;
            position: relative;
            top: 0;
            z-index: 1;
        }

        tr.search-row {
            margin-bottom: 0;
            padding-bottom: 0;
        }

        thead tr:nth-child(1) th {
            padding-bottom: 0;
        }

        thead tr:nth-child(2) td {
            padding-top: 0;
        }

        .floating-label-form-group {
            position: relative;
            margin-bottom: 0.5rem;
        }

        .floating-label-form-group label {
            background-color: white;
            padding: 0px 3px;
            position: absolute;
            transition: top 0.3s, left 0.3s, font-size 0.3s, color 0.3s;
            top: 0.25rem;
            left: 0.5rem;
            font-size: 14px;
            color: #808080;
            pointer-events: none;
            font-weight: bold;
        }

        .filterInput {
            width:155px;
            height: 33px;
            border-radius:8px;
            border: 1px solid #0000ff;
            background-color: white;
        }

        .filterInput:focus{
            outline:none;
        }

        .filterInput:focus + label,
        .filterInput:not(:placeholder-shown) + label {
            top: -0.7rem;
            left: 0.75rem;
            font-size: 13px;
            color: #808080;
            font-weight: bold;
            border: none;
            outline: none;
        }

        .middle-image {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
            width: 50%;
            opacity: 0.8;
        }
        .clrbutton {
            font-family:'Montserrat', sans-serif;
            color: white;
            background-color: #00008b;
            font-weight: 500;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            line-height: 2rem;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
            padding-top: 0.2rem;
            padding-bottom: 0.2rem;
            cursor: pointer;
            text-align: center;
            margin-right: 0.5rem;
            display: inline-flex;
            align-items: center;
            border: none;
        }

        .clrbutton:hover {
            background-color: #00008b;
        }

        .clrbutton svg {
            display: inline;
            color: white;
            margin-right: 0.4rem;
        }

        .clrbutton:focus svg {
            animation: spin_357 0.5s linear;
        }

        @keyframes spin_357 {
            from {
                transform: rotate(0deg);
                }

            to {
                transform: rotate(360deg);
                }
        }

        .clrbutton:focus {
            outline: none;
            border: none;
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
                <p class="navbar-brand mb-0">CIMS</p>
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
    <div class="title">Holdings 
        <button type="button" class="clrbutton" onclick="clearFilters()">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="16"
            height="16"
            fill="currentColor"
            class="bi bi-arrow-repeat"
            viewBox="0 0 16 16">
            <path
            d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"
            ></path>
            <path
            fill-rule="evenodd"
            d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"
            ></path>
        </svg>
        Clear All
        </button>
    </div>
    
    <div class="table-container">
        <table id="dataTable">
            <thead>
                <tr>
                    <th>
                        <div class="floating-label-form-group">
                            <input type="text" class="filterInput" placeholder="" onkeyup="filterColumn(0)">
                            <label for="S NO">S.No</label>
                        </div>
                    </th>
                    <th>
                        <div class="floating-label-form-group">
                            <input type="text" class="filterInput" placeholder="" onkeyup="filterColumn(1)">
                            <label for="Railway">Railway</label>
                        </div>
                    </th>
                    <th>
                        <div class="floating-label-form-group">
                            <input type="text" class="filterInput" placeholder="" onkeyup="filterColumn(2)">
                            <label for="Coach No">Coach No</label>
                        </div>
                    </th>
                    <th>
                        <div class="floating-label-form-group">
                            <input type="text" class="filterInput" placeholder="" onkeyup="filterColumn(3)">
                            <label for="Code">Code</label>
                        </div>
                    </th>
                    <th>
                        <div class="floating-label-form-group">
                            <input type="text" class="filterInput" placeholder="" onkeyup="filterColumn(4)">
                            <label for="Type">Type</label>
                        </div>
                    </th>
                    <th>
                        <div class="floating-label-form-group">
                            <input type="text" class="filterInput" placeholder="" onkeyup="filterColumn(5)">
                            <label for="Category">Category</label>
                        </div>
                    </th>
                    <th>
                        <div class="floating-label-form-group">
                            <input type="text" class="filterInput" placeholder="" onkeyup="filterColumn(6)">
                            <label for="Corr M Hrs">Corr M Hrs</label>
                        </div>
                
                        </th>
                    <th>
                        <div class="floating-label-form-group">
                            <input type="text" class="filterInput" placeholder="" onkeyup="filterColumn(7)">
                            <label for="Division">Division</label>
                        </div>
                    </th>
                    <th>
                        <div class="floating-label-form-group">
                            <input type="text" class="filterInput" placeholder="" onkeyup="filterColumn(8)">
                            <label for="Depot">Depot</label>
                        </div>
                    </th>
                    <th>
                        <div class="floating-label-form-group">
                            <input type="text" class="filterInput" placeholder="" onkeyup="filterColumn(9)">
                            <label for="AC Flag">AC Flag</label>
                        </div>
                    </th>
                    <th>
                        <div class="floating-label-form-group">
                            <input type="text" class="filterInput" placeholder="" onkeyup="filterColumn(10)">
                            <label for="Coupling">Coupling</label>
                        </div>
                    </th>
                    <th>
                        <div class="floating-label-form-group">
                            <input type="text" class="filterInput" placeholder="" onkeyup="filterColumn(11)">
                            <label for="L POH Date">L POH Date</label>
                        </div>
                    </th>
                    <th>
                        <div class="floating-label-form-group">
                            <input type="text" class="filterInput" placeholder="" onkeyup="filterColumn(12)">
                            <label for="R Date">R Date</label>
                        </div>
                    </th>
                    <th>
                        <div class="floating-label-form-group">
                            <input type="text" class="filterInput" placeholder="" onkeyup="filterColumn(13)">
                            <label for="Repair Type">Repair Type</label>
                        </div>
                    </th>
                    <th>
                        <div class="floating-label-form-group">
                            <input type="text" class="filterInput" placeholder="" onkeyup="filterColumn(14)">
                            <label for="Age Group">Age Group</label>
                        </div>
                    </th>
                    <th>
                        <div class="floating-label-form-group">
                            <input type="text" class="filterInput" placeholder="" onkeyup="filterColumn(15)">
                            <label for="CD">CD</label>
                        </div>
                    </th>
                    <th>
                        <div class="floating-label-form-group">
                            <input type="text" class="form-control filterInput" placeholder="" onkeyup="filterColumn(16)">
                            <label for="WD">WD</label>
                        </div>
                    </th>
                    <th>
                        <div class="floating-label-form-group">
                            <input type="text" class="form-control filterInput" placeholder="" onkeyup="filterColumn(17)">
                            <label for="WS IN Date">WS IN Date</label>
                        </div>
                    </th>
                    <th>
                        <div class="floating-label-form-group">
                            <input type="text" class="form-control filterInput" placeholder="" onkeyup="filterColumn(18)">
                            <label for="NC Offered">NC Offered</label>
                        </div>
                    </th>
                    <th>
                        <div class="floating-label-form-group">
                            <input type="text" class="form-control filterInput" placeholder="" onkeyup="filterColumn(19)">
                            <label for="Placement">Placement</label>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody id="dataBody">
            <?php include 'fetch_holdings.php'; ?>
            </tbody>
            </table>
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

    function filterColumn(colIndex) {
            var input, filter, table, tr, td, i, txtValue;
            table = document.getElementById("dataTable");
            input = document.getElementsByTagName("input")[colIndex];
            filter = input.value.toUpperCase();
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) { // Start from the third row
                td = tr[i].getElementsByTagName("td")[colIndex];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().startsWith(filter)) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }       
            }
        }

        function clearFilters() {
            var inputs = document.getElementsByClassName('filterInput');
            for (var i = 0; i < inputs.length; i++) {
                inputs[i].value = '';
            }
            filterColumn(0); // Reset the table after clearing filters
        }
</script>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-BDeGvOvxVH2M9fHAhrVZ3sCVyZESeIsfHfIYBEGi/lwDlKwF/9pFgpKcuX+kIqBk" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>