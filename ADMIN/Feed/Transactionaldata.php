<!-- Transactionaldata.php -->
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
    <title>Feed</title>
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
            margin: 0;
            padding: 0;
            background-color: #f0f4f8;
        }
        
        .input_box {
            position: relative;
            font-weight: bold;
            
        }
        .input_box select,
        .input_box input[type="text"],
        .input_box input[type="date"],
        .input_box input[type="datetime-local"]{
            width: 100%;
            padding: 10px;
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

        .input_box select:disabled,
        .input_box input[type="text"]:disabled,
        .input_box input[type="date"]:disabled,
        .input_box input[type="datetime-local"]:disabled {
            color: black;
            background-color: transparent;
            cursor: not-allowed;
        }
        .col-md-1-25 {
            flex: 0 0 10%;
            max-width: 10%;
        }

        .col-md-1-5 {
            flex: 0 0 12.5%;
            max-width: 12.5%;
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
            width: 30%;
            transition-duration: 0.3s;
            padding-left: 20px;
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
        

        .searchcard {
            height: 50px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            padding-top: 24px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
            margin-top: 8px;
            margin-bottom: 8px;
        }
        .datacard {
            height:auto;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 5px;
            padding-top:12px;
            padding-bottom:0px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
            margin-bottom: 8px; 
        }
        .tablecard {
            height: 258px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            padding-top:13px;
            padding-bottom:13px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
            margin-bottom: 8px; 
        }
        .table-container {
            width: 100%;
            max-width: 100%;
            overflow: auto;
            position: relative;
            height: auto; /* Adjust as needed */
        }

        .table-container::-webkit-scrollbar {
            width: 8px; /* Vertical scrollbar width */
            height: 8px; /* Horizontal scrollbar height */
        }

        .table-container::-webkit-scrollbar-track {
            background:#e0e0e0; /* Background of the scrollbar track */
        }

        .table-container::-webkit-scrollbar-thumb {
            background: #888; /* Color of the scrollbar thumb */
            border-radius: 10px; /* Roundness of the scrollbar thumb */
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: #555; /* Color of the scrollbar thumb on hover */
        }

        .table-container::-webkit-scrollbar-button {
            display: none; /* Hide all buttons by default */
        }

        .table-container::-webkit-scrollbar-button:single-button:vertical:decrement,
        .table-container::-webkit-scrollbar-button:single-button:vertical:increment,
        .table-container::-webkit-scrollbar-button:single-button:horizontal:decrement,
        .table-container::-webkit-scrollbar-button:single-button:horizontal:increment {
            display: block; /* Display only the arrow buttons */
            width: 15px; /* Ensure the buttons fit within the scrollbar */
            height: 15px;
            background-size: 100% 100%; /* Ensure the background image fits the button */
        }

        .table-container::-webkit-scrollbar-button:single-button:vertical:decrement {
            background: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24"><path fill="%23555555" d="M8 14l4-4 4 4z"/></svg>') center no-repeat;
        }

        .table-container::-webkit-scrollbar-button:single-button:vertical:increment {
            background: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24"><path fill="%23555555" d="M8 10l4 4 4-4z"/></svg>') center no-repeat;
        }

        .table-container::-webkit-scrollbar-button:single-button:horizontal:decrement {
            background: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24"><path fill="%23555555" d="M14 8l-4 4 4 4z"/></svg>') center no-repeat;
        }

        .table-container::-webkit-scrollbar-button:single-button:horizontal:increment {
            background: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24"><path fill="%23555555" d="M10 8l4 4-4 4z"/></svg>') center no-repeat;
        }


        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
            font-size:13px;       
        }

        th, td {
            border: 1px solid black;
            padding: 4px 8px;
            background-color: #fff;
        }

        th {
            background-color: #f1f1f1;
        }

        .table-container th, 
        .table-container td {
            width: 150px; /* Adjust as needed */
        }

        .table-container thead th {
            position: sticky;
            top: 0;
            z-index: 3; /* Ensure header is above the rest of the table */
            color: white;
            background-color: #00008B;
        }

        .table-container tbody th {
            position: sticky;
            left: 0;
            z-index: 2; /* Ensure first column is above the rest of the table */
            background-color: #f1f1f1;
        }

        .table-container thead th:first-child {
            left: 0;
            z-index: 4; /* Ensure the intersection of header and first column is above the rest */
        }

        .card {
        height: 80px;
        /* width: 980px; */
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 4px;
        padding-top: 10px;
        padding-bottom: 0px;
        background-color: #f9f9f9;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
        margin-bottom: 4px;
        }

        .scrollable-section {
        height: 60px;
        width: auto;
        overflow-x: auto; /* Allow horizontal scrolling */
        white-space: nowrap; /* Prevent wrapping */
        padding-right: 20px; /* Add padding to the right */
        padding-top: 8px;
        }

        .scrollable-section::-webkit-scrollbar {
            height: 8px; /* Adjust scrollbar height */
        }

        .scrollable-section::-webkit-scrollbar-track {
            background: #e0e0e0; /* Background of the scrollbar track */
        }

        .scrollable-section::-webkit-scrollbar-thumb {
            background: #888; /* Color of the scrollbar thumb */
            border-radius: 10px; /* Roundness of the scrollbar thumb */
        }

        .scrollable-section::-webkit-scrollbar-thumb:hover {
            background: #555; /* Color of the scrollbar thumb on hover */
        }

        .scrollable-section::-webkit-scrollbar-button {
            display: none; /* Hide all buttons by default */
        }

        .scrollable-section::-webkit-scrollbar-button:single-button:horizontal:decrement,
        .scrollable-section::-webkit-scrollbar-button:single-button:horizontal:increment {
            display: block; /* Display only the arrow buttons */
            width: 17px; /* Ensure the buttons fit within the scrollbar */
            height: 17px;
            background-size: 100% 100%; /* Ensure the background image fits the button */
        }

        .scrollable-section::-webkit-scrollbar-button:single-button:horizontal:decrement {
            background: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24"><path fill="%23555555" d="M14 8l-4 4 4 4z"/></svg>') center no-repeat;
        }

        .scrollable-section::-webkit-scrollbar-button:single-button:horizontal:increment {
            background: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24"><path fill="%23555555" d="M10 8l4 4-4 4z"/></svg>') center no-repeat;
        }

        .addbutton {
            background: #00008B;
            position: relative;
            height: 36px;
            width: 36px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            outline: none;
            border-radius: 10px;
            
            margin-top: 8px;
            margin-left: 10px;
            
        }

        .addbutton__icon {
            height: 36px;
            width: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease;
        }

        .addbutton .svg {
            width: 30px;
            stroke: #fff;       
        }

        .addbutton:hover .addbutton__icon {
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

        .addbutton:focus {
            outline: none;
        }


        .deletecard {
        height: 80px;
        width: 340px;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 5px;
        padding-top: 10px;
        background-color: #f9f9f9;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
        margin-left: 8px;
        margin-bottom: 4px;
        }

        .bin-button {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background-color: red;
        cursor: pointer;
        border: none;
        transition-duration: 0.3s;
        margin-left:12px;
        margin-right:12px;
        margin-top: 7px;
        }
        .bin-bottom {
        width: 10px;  /* Reduced width */
        }
        .bin-top {
        width: 12px;  /* Reduced width */
        transform-origin: right;
        transition-duration: 0.3s;
        }
        .bin-button:hover .bin-top {
        transform: rotate(45deg);
        }
        .bin-button:hover {
        background-color: red;
        }
        .bin-button:active {
        transform: scale(0.9);
        }
        .garbage {
        background-color: black;
        position: absolute;
        width: 9px;
        height: auto;
        z-index: 1;
        opacity: 0;
        transition: all 0.3s;
        }
        .bin-button:hover .garbage {
        animation: throw 0.3s linear;
        }
        @keyframes throw {
        from {
            transform: translate(-400%, -700%);
            opacity: 0;
        }
        to {
            transform: translate(0%, 0%);
            opacity: 1;
        }
        }
        .bin-button:focus{
            outline:none;
        }


        .input_box1 {
            position: relative;
            font-weight: bold;
        }

        .input_box1 select,
        .input_box1 input[type="text"],
        .input_box1 input[type="date"]{
            width: 100%;
            padding: 10px;
            border: 1.5px solid black;
            border-radius: 10px;
            font-size: 12px;
            outline: none;
            height: 36px;
            color:black;
            font-weight: 500;
        }
        .input_box1 input[type="datetime-local"]{
            width: 100%;
            padding: 10px;
            border: 1.5px solid black;
            border-radius: 10px;
            font-size: 12px;
            outline: none;
            height: 36px;
            color:black;
            font-weight: 500;
        }

        .input_box1 label {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: white;
            padding: 0 5px;
            color: #3A7DD9;
            font-size: 16px;
            pointer-events: none;
        }

        .input_box1 input[type="text"]:focus + label,
        .input_box1 input[type="text"]:not(:placeholder-shown) + label,
        .input_box1 input[type="date"]:focus + label,
        .input_box1 input[type="date"]:not(:placeholder-shown) + label,
        .input_box1 select:focus + label,
        .input_box1 select:not(:placeholder-shown) + label {
            top: -10px;
            left: 10px;
            font-size: 12px;
            color: #3A7DD9;
        }

        .input_box1 input[type="datetime-local"]:not(:placeholder-shown) + label{
            top: -10px;
            left: 10px;
            font-size: 12px;
            color: #3A7DD9;
        }
        /* ----- */
        .input_box1 input {
            width: 100%;
            padding: 10px;
            border: 1.5px solid black;
            border-radius: 10px;
            font-size: 12px;
            outline: none;
            height: 36px;
            color:black;
            font-weight: 500;
        }

        .input_box1 label1 {
            position: absolute;
            top: -10px;
            left: 10px;
            background-color: white;
            padding: 0 5px;
            color: #3A7DD9;
            font-size: 12px;
            pointer-events: none;
        }

        .input_box1 input:focus + label1,
        .input_box1 input:not(:placeholder-shown) + label1 {
            top: -10px;
            left: 10px;
            font-size: 12px;
            color: #3A7DD9;
        }

        .editBtn {
        width: 62px;
        height: 36px;
        border-radius: 10px;
        border: none;
        background-color: #00008b;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: all 0.3s;
        margin-top: 8px;
        margin-right: 10px;
        margin-left: 10px;
        
        }

        .editBtn::before {
        content: "";
        width: 200%;
        height: 200%;
        background-color: #00008b;
        position: absolute;
        z-index: 1;
        transform: scale(0);
        transition: all 0.3s;
        border-radius: 50%;
        filter: blur(10px);
        }
        .editBtn:hover::before {
        transform: scale(1);
        }

        .editBtn svg {
        height: 12px; /* Adjusted height */
        fill: white;
        z-index: 3;
        transition: all 0.2s;
        transform-origin: bottom;
        }
        .editBtn:hover svg {
        transform: rotate(-15deg) translateX(3px); /* Adjusted translation */
        }
        .editBtn::after {
        content: "";
        width: 16px; /* Adjusted width */
        height: 1.5px;
        position: absolute;
        bottom: 12px; /* Adjusted position */
        left: -3px; /* Adjusted position */
        background-color: white;
        border-radius: 2px;
        z-index: 2;
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.5s ease-out;
        }
        .editBtn:hover::after {
        transform: scaleX(1);
        left: 0px;
        transform-origin: right;
        }

        .editBtn:focus {
            outline: none; /* Remove the focus outline */
        }

        .btnCloud {
            background-color: #00008b;
            position: relative;
            fill: white;
            border: none;
            display: flex;
            height: 36px;
            width: 36px;
            border-radius: 10px;
            margin-top: 8px;
            margin-right: 10px;
            cursor: pointer;
            outline: none; /* Remove the outline when the button is clicked */
        }

        .icon {
            margin-top: 2px;
            transform: scale(0.95);
            transition: transform 200ms linear;
        }

        .btnCloud:hover .icon {
            transform: scale(1.15);
        }

        .btnCloud:hover .icon path {
            fill: white;
        }

        .btnCloud::after {
            content: ''; /* or some content if necessary */
            visibility: hidden;
            opacity: 0;
            position: absolute;
            transition: visibility 0s, opacity 0.5s linear, top 0.5s linear;
        }

        .btnCloud:hover::after {
            visibility: visible;
            opacity: 1;
            top: -100%;
        }

        .btnCloud:focus {
            outline: none; /* Remove the focus outline */
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
                <p class="navbar-brand mb-0">CIMS - Feed</p>
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

<div class="container-fluid mt-1">
    <div class="searchcard d-flex justify-content-between">
        <form class="mb-3 d-flex align-items-center" id="searchForm">
            <input type="text" name="search" class="form-control" placeholder="Search Coach No" style="width: 200px; font-size: 14px;">
            <button class="searchbutton" id="searchButton">
            <svg class="svgIcon" viewBox="0 0 512 512" height="1em" xmlns="http://www.w3.org/2000/svg">
            <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm50.7-186.9L162.4 380.6c-19.4 7.5-38.5-11.6-31-31l55.5-144.3c3.3-8.5 9.9-15.1 18.4-18.4l144.3-55.5c19.4-7.5 38.5 11.6 31 31L325.1 306.7c-3.2 8.5-9.9 15.1-18.4 18.4zM288 256a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z" fill="white"></path>
            </svg>
            Search
            </button>
        </form>
    </div>
</div>
 
    
<div class="container-fluid mt-1">
    <div class="datacard d-flex justify-content-between">
    <div class="container-fluid">    
        <form id="dataform" method="post">

            <div class="form-row">
                <div class="input_box col-md-1">
                    <input type="text" class="form-control" name="coachID" id="coachID" size="10" maxlength="10" value="<?= isset($_GET['coachID']) ? htmlspecialchars($_GET['coachID']) : '' ?>" disabled>

                    <label for="coachID" class="label">Coach ID</label>
                </div>

                <div class="input_box col-md-1">
                    <input type="text" class="form-control" name="coachNo" id="coachNo" size="10" maxlength="10" value="<?= isset($_GET['coachNo']) ? htmlspecialchars($_GET['coachNo']) : '' ?>" disabled>

                    <label for="coachNo" class="label">Coach No</label>
                </div>

                <div class="input_box col-md-1-5">
                    <input type="text" class="form-control" name="oldCoachNo" id="oldCoachNo" size="10" maxlength="10" value="<?= isset($_GET['oldCoachNo']) ? htmlspecialchars($_GET['oldCoachNo']) : '' ?>" disabled>

                    <label for="oldCoachNo" class="label">Old Coach No</label>
                </div>

                <div class="input_box col-md-1">
                    <input type="text" class="form-control" name="code" id="code" size="10" maxlength="10" value="<?= isset($_GET['code']) ? htmlspecialchars($_GET['code']) : '' ?>" disabled>    
                    <label for="code" class="label">Code</label>
                </div>
                
                <div class="input_box col-md-1">
                    <input type="text" class="form-control" name="type" id="type" size="10" maxlength="10" value="<?= isset($_GET['type']) ? htmlspecialchars($_GET['type']) : '' ?>" disabled>    
                    <label for="type" class="label">Type</label>
                </div>

                <div class="input_box col-md-1">
                    <input type="text" class="form-control" name="railway" id="railway" size="10" maxlength="10" value="<?= isset($_GET['railway']) ? htmlspecialchars($_GET['railway']) : '' ?>" disabled>    
                    <label for="railway" class="label">Railway</label>
                </div>
                
                <div class="input_box col-md-1">
                    
                    <input type="text" class="form-control" name="vehicleType" id="vehicleType" size="10" maxlength="10" value="<?= isset($_GET['vehicleType']) ? htmlspecialchars($_GET['vehicleType']) : '' ?>" disabled>    
                    <label for="vehicleType" class="label">Vehicle Type</label>
                </div>

                <div class="input_box col-md-1">
                    <input type="text" class="form-control" name="category" id="category" size="10" maxlength="10" value="<?= isset($_GET['category']) ? htmlspecialchars($_GET['category']) : '' ?>" disabled>    
                    <label for="category" class="label">Category</label>
                </div>

                <div class="input_box col-md-1">
                    <input type="text" class="form-control" name="AC_Flag" id="AC_Flag" size="10" maxlength="10" value="<?= isset($_GET['AC_Flag']) ? htmlspecialchars($_GET['AC_Flag']) : '' ?>" disabled>    
                    <label for="AC_Flag" class="label">AC Flag</label>
                </div>

                <div class="input_box col-md-1">
                    <input type="text" class="form-control" name="brakesystem" id="brakesystem" size="10" maxlength="10" value="<?= isset($_GET['brakesystem']) ? htmlspecialchars($_GET['brakesystem']) : '' ?>" disabled>    
                    <label for="brakesystem" class="label">Brake System</label>
                </div>

                <div class="input_box col-md-1-5">
                    <input type="datetime-local" class="form-control" name="builtDate" id="builtDate" disabled/>
                    <label for="builtDate" class="label">Built Date</label> 
                </div>

            </div>

            <div class="form-row">

                <div class="input_box col-md-1">
                    <input type="text" class="form-control" name="built" id="built" value="<?= isset($_GET['built']) ? htmlspecialchars($_GET['built']) : '' ?>" disabled>
                    <label for="built" class="label">Built</label> 
                </div>

                <div class="input_box col-md-1-5">
                    <input type="datetime-local" class="form-control" name="inductionDate" id="inductionDate"  disabled/>
                    <label for="inductionDate" class="label">Induction Date</label> 
                </div>

                <div class="input_box col-md-1">
                    <input type="text" class="form-control" name="periodicity" id="periodicity" size="10" maxlength="10" value="<?= isset($_GET['periodicity']) ? htmlspecialchars($_GET['periodicity']) : '' ?>" disabled>    
                    <label for="periodicity" class="label">Periodicity</label>
                </div>
           
           
                <div class="input_box col-md-1-5">
                    <input type="text" class="form-control" name="owningDivision" id="owningDivision" size="10" maxlength="10" value="<?= isset($_GET['owningDivision']) ? htmlspecialchars($_GET['owningDivision']) : '' ?>" disabled>    
                    <label for="owningDivision" class="label">Owning Division</label>
                </div>

                <div class="input_box col-md-1">
                    <input type="text" class="form-control" name="baseDepot" id="baseDepot" size="10" maxlength="10" value="<?= isset($_GET['baseDepot']) ? htmlspecialchars($_GET['baseDepot']) : '' ?>" disabled>    
                    <label for="baseDepot" class="label">Base Depot</label>
                </div>

                <div class="input_box col-md-1-5">
                    <input type="text" class="form-control" name="workshop" id="workshop" size="10" maxlength="10" value="<?= isset($_GET['workshop']) ? htmlspecialchars($_GET['workshop']) : '' ?>" disabled>    
                    <label for="workshop" class="label">Workshop</label>
                </div>

                <div class="input_box col-md-1-5">
                <input type="text" class="form-control" name="codalLife" id="codalLife" size="10" maxlength="10" value="<?= isset($_GET['codalLife']) ? htmlspecialchars($_GET['codalLife']) : '' ?>" disabled>    
                    <label for="codalLife" class="label">Codal Life</label>
                </div>
                
                <div class="input_box col-md-1-5">
                    <input type="text" class="form-control" name="powerGenerationType" id="powerGenerationType" size="10" maxlength="10" value="<?= isset($_GET['powerGenerationType']) ? htmlspecialchars($_GET['powerGenerationType']) : '' ?>" disabled>    
                    <label for="powerGenerationType" class="label">Generation Type</label>
                </div>

                <div class="input_box col-md-1-5">
                    <input type="text" class="form-control" name="couplingType" id="couplingType" size="10" maxlength="10" value="<?= isset($_GET['couplingType']) ? htmlspecialchars($_GET['couplingType']) : '' ?>" disabled>   
                    <label for="couplingType" class="label">Coupling Type</label>
                </div>
            </div>

        </form>
    </div>
    </div>
</div>


<div class="container-fluid mt-1">
    <div class="d-flex flex-nowrap">
        <div class="card d-flex justify-content-between">
            <div class="form-row ml-3 d-flex flex-nowrap">
                <div class="scrollable-section">
                    <form id="coachForm" class="d-flex">
                        <!-- Form fields -->
                        <div class="input_box1"><input type="text" id="coachID2" name="coachID" style="width: 90px; font-size: 12px; margin-right:10px;">
                        <label for="coachID2" class="label">CoachID</label>
                        </div>
                        
                        <div class="input_box1"><input type="datetime-local" id="yardIN" name="yardIN" style="width: 145px; font-size: 12px; margin-right:10px;">
                        <label for="yardIN" class="label">Yard IN</label>
                        </div>

                        <div class="input_box1">
                            <select id="condition" name="condition" style="width: 140px; font-size: 12px; margin-right:10px;">
                                <option value="" disabled selected hidden></option>
                                <?php
                                include '../../db.php';
                                // Assuming $conn is your database connection
                                if ($conn) {
                                    // Query to retrieve data from the database
                                    $query = "SELECT Condition FROM dbo.tbl_ConditionMaster";
                                    $result = sqlsrv_query($conn, $query);

                                    if ($result !== false) {
                                        // Output data of each row
                                        echo '<option value="" selected hidden></option>';
                                        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                            echo '<option value="' . $row['Condition'] . '" >' . $row['Condition'] .'</option>';  
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
                            <label for="condition" class="label">Condition</label>
                        </div>

                        <div class="input_box1">
                            <select id="repairType" name="repairType" style="width: 145px; font-size: 12px; margin-right:10px;">
                                <option value="" disabled selected hidden></option>
                                <?php
                                include '../../db.php';
                                // Assuming $conn is your database connection
                                if ($conn) {
                                    // Query to retrieve data from the database
                                    $query = "SELECT RepairTypes FROM dbo.tbl_CMS_RepairTypeMaster";
                                    $result = sqlsrv_query($conn, $query);

                                    if ($result !== false) {
                                        // Output data of each row
                                        echo '<option value="" selected hidden></option>';
                                        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                            echo '<option value="' . $row['RepairTypes'] . '" >' . $row['RepairTypes'] .'</option>';  
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
                            <label for="repairType" class="label">Repair type</label>
                        </div>

                        <div class="input_box1"><input type="datetime-local" id="workshopIN" name="workshopIN" style="width: 145px; font-size: 12px; margin-right:10px;">
                        <label for="workshopIN" class="label">Workshop IN</label>
                        </div>

                        <?php
                        // Include your database connection file
                        include '../../db.php';

                        // Function to fetch all possible coach locations
                        function getCoachLocations($conn) {
                            $query = "SELECT DISTINCT s.Shop_Code, s.Shop_Name, s.Line, s.Count 
                                    FROM CoachMaster_V_2018.dbo.tbl_CMS_ShopInformation s";
                            $result = sqlsrv_query($conn, $query);
                            $locations = array();
                            if ($result !== false) {
                                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                    for ($i = 1; $i <= $row['Count']; $i++) {
                                        $locations[] = [
                                            'shopName' => $row['Shop_Name'],
                                            'shop' => $row['Shop_Code'],
                                            'lineNumber' => $row['Line'],
                                            'newLocation' => $i
                                        ];
                                    }
                                }
                            }
                            return $locations;
                        }

                        // Fetch all possible coach locations
                        $coachLocations = getCoachLocations($conn);

                        // Function to fetch activities for a given shop
                        function getActivities($conn, $shopCode) {
                            $query = "SELECT [activity] FROM [CoachMaster_V_2018].[dbo].[tbl_CMS_ActivityList] WHERE Shop = ?";
                            $params = array($shopCode);
                            $result = sqlsrv_query($conn, $query, $params);
                            $activities = array();
                            if ($result !== false) {
                                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                    $activities[] = $row['activity'];
                                }
                            }
                            return $activities;
                        }

                        // Get the selected coach location (assuming it's passed via POST or GET)
                        $selectedLocation = isset($_POST['coach_location']) ? $_POST['coach_location'] : (isset($_GET['coach_location']) ? $_GET['coach_location'] : '');

                        // Extract the shop code (only letters)
                        $shopCode = preg_replace('/[^a-zA-Z]/', '', $selectedLocation);

                        // Fetch activities for the selected shop
                        $activities = getActivities($conn, $shopCode);
                        ?>

                        <div class="input_box1">
                            <input list="coach_location_list" id="coach_location_input" name="coach_location" placeholder="Type to search" style="width: 121px;font-size: 12px; margin-right:10px; padding:10px;">
                            <datalist id="coach_location_list">
                                <?php foreach ($coachLocations as $location): ?>
                                    <?php
                                    $optionText = $location['shopName'] . " Line " . $location['lineNumber'] . " Loc " . $location['newLocation'];
                                    $optionValue = $location['shop'] . $location['lineNumber'] . "_" . $location['newLocation'];
                                    ?>
                                    <option value="<?php echo htmlspecialchars($optionValue); ?>">
                                        <?php echo htmlspecialchars($optionText); ?>
                                    </option>
                                <?php endforeach; ?>
                            </datalist>
                            <label1 for="coach_location" class="label1">Coach Location</label>
                        </div>

                        <div class="input_box1">
                            <input list="activity_list" id="activity_input" name="activity" placeholder="Type activity" style="width: 121px;font-size: 12px; margin-right:10px; padding:10px;">
                            <datalist id="activity_list">
                                <?php foreach ($activities as $activity): ?>
                                    <option value="<?php echo htmlspecialchars($activity); ?>">
                                        <?php echo htmlspecialchars($activity); ?>
                                    </option>
                                <?php endforeach; ?>
                            </datalist>
                            <label1 for="activity" class="label">Activity</label>
                        </div>

                        <script>
                        // JavaScript to update activities when coach location changes
                        document.getElementById('coach_location_input').addEventListener('change', function() {
                            var selectedLocation = this.value;
                            var shopCode = selectedLocation.replace(/[^a-zA-Z]/g, ''); // Extract only letters
                            
                            // AJAX request to get activities
                            var xhr = new XMLHttpRequest();
                            xhr.onreadystatechange = function() {
                                if (this.readyState == 4 && this.status == 200) {
                                    var activities = JSON.parse(this.responseText);
                                    var activityList = document.getElementById('activity_list');
                                    activityList.innerHTML = '';
                                    activities.forEach(function(activity) {
                                        var option = document.createElement('option');
                                        option.value = activity;
                                        option.text = activity;
                                        activityList.appendChild(option);
                                    });
                                }
                            };
                            xhr.open("GET", "get_activities_feed.php?shop=" + shopCode, true);
                            xhr.send();
                        });
                        </script>


                        <div class="input_box1">
                            <select id="corrosion_Hrs" name="corrosion_Hrs" style="width: 121px;font-size: 12px; margin-right:10px; padding:4px;">
                            <?php
                                include '../../db.php';
                                // Assuming $conn is your database connection
                                if ($conn) {
                                    // Query to retrieve data from the corrosion table
                                    $query = "SELECT Corr_Hrs FROM dbo.tbl_CMS_Corrosion_Hrs";
                                    $result = sqlsrv_query($conn, $query);

                                    if ($result !== false) {
                                        // Output data of each row
                                        echo '<option value="" selected hidden></option>';
                                        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                            echo '<option value="'.$row['Corr_Hrs'].'">' . $row['Corr_Hrs'] .'</option>';
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
                            <label for="corrosion_Hrs" class="label">Corr Hrs</label>
                        </div>

                        <div class="input_box1">
                            <select id="pohShop" name="pohShop" style="width: 90px;font-size: 12px; margin-right:10px;">
                                <option value="" disabled selected hidden></option>
                                <?php
                                include '../../db.php';
                                // Assuming $conn is your database connection
                                if ($conn) {
                                    // Query to retrieve data from the database
                                    $query = "SELECT Workshop FROM dbo.tbl_CMS_POHWorkshopMaster";
                                    $result = sqlsrv_query($conn, $query);

                                    if ($result !== false) {
                                        // Output data of each row
                                        echo '<option value="" selected hidden></option>';
                                        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                            echo '<option value="' . $row['Workshop'] . '" >' . $row['Workshop'] .'</option>';  
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
                            <label for="pohShop" class="label">POH Shop</label>
                        </div>

                        <div class="input_box1"><input type="text" id="workorderNumber" name="workorderNumber" style="width: 110px;font-size: 12px; margin-right:10px;">
                        <label for="workorderNumber" class="label">Workorder No</label>
                        </div>

                        <div class="input_box1"><input type="datetime-local" id="ncOffered" name="ncOffered" style="width: 145px;font-size: 12px; margin-right:10px;">
                        <label for="ncOffered" class="label">NC Offered</label>
                        </div>
                        <div class="input_box1"><input type="datetime-local" id="ncFit" name="ncFit" style="width: 145px; font-size: 12px; margin-right:10px;">
                        <label for="ncFit" class="label">NC fit</label>
                        </div>
                        <div class="input_box1"><input type="datetime-local" id="despatchedDate" name="despatchedDate" style="width: 145px; font-size: 12px; margin-right:10px;">
                        <label for="despatchedDate" class="label">Despatched date</label>
                        </div>

                        <div class="input_box1"><input type="datetime-local" id="returnDate" name="returnDate" style="width: 145px;font-size: 12px; margin-right:10px;">
                        <label for="returnDate" class="label">Return date</label>
                        </div>

                    </form>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="addbutton" id="addbutton" onclick="addForm()">
                    <span class="addbutton__icon" title="Add">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" viewBox="2 0 30 26" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" stroke="currentColor" fill="none" class="svg">
                        <line y2="19" y1="5" x2="12" x1="12"></line>
                        <line y2="12" y1="12" x2="19" x1="5"></line>
                        </svg>
                    </span>
                    </button>
                </div>
                <button type="button" class="editBtn" id="editButton" title="Edit">
                <svg height="1em" viewBox="0 0 512 512">
                    <path
                    d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"
                    ></path>
                </svg>
                </button>

                <button type="button" class="btnCloud" id="saveButton" title="Save">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="1 0 26 24" width="30" height="30" class="icon"><path d="M22,15.04C22,17.23 20.24,19 18.07,19H5.93C3.76,19 2,17.23 2,15.04C2,13.07 3.43,11.44 5.31,11.14C5.28,11 5.27,10.86 5.27,10.71C5.27,9.33 6.38,8.2 7.76,8.2C8.37,8.2 8.94,8.43 9.37,8.8C10.14,7.05 11.13,5.44 13.91,5.44C17.28,5.44 18.87,8.06 18.87,10.83C18.87,10.94 18.87,11.06 18.86,11.17C20.65,11.54 22,13.13 22,15.04Z"></path></svg>
                </button>


            </div>
        </div>
        
<script>
    document.getElementById('saveButton').addEventListener('click', function() {
        // Collect form data
        var data = {
            coachID: document.getElementById('coachID').value,

            yardIN: document.getElementById('yardIN').value,
            workshopIN: document.getElementById('workshopIN').value,
            ncOffered: document.getElementById('ncOffered').value,
            ncFit: document.getElementById('ncFit').value,
            despatchedDate: document.getElementById('despatchedDate').value,
            corrosionHrs: document.getElementById('corrosion_Hrs').value,
            pohShop: document.getElementById('pohShop').value,
            repairType: document.getElementById('repairType').value,
            workorderNumber: document.getElementById('workorderNumber').value,
            returnDate: document.getElementById('returnDate').value,
            condition: document.getElementById('condition').value,
            coachLocation: document.getElementById('coach_location_input').value,
            shopActivity: document.getElementById('activity_input').value
        };

        // Send data to the server
        fetch('update_coach_data.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            alert('Data updated successfully');
            showAddMode();
            document.getElementById('searchButton').click();
            clearFormFields();
        } else if (response.error) {
            alert('Error updating data: ' + response.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An unexpected error occurred');
    });


    });
</script>

        
<script>

function showEditMode() {
    document.getElementById('saveButton').style.display = 'inline-block';
    document.getElementById('addbutton').style.display = 'none';
}

function showAddMode() {
    document.getElementById('saveButton').style.display = 'none';
    document.getElementById('addbutton').style.display = 'inline-block';
}

document.getElementById('editButton').addEventListener('click', function() {
    var coachID = document.getElementById('coachID').value;
    if (coachID) {
        fetch('get_coach_data.php?coachID=' + coachID)
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                const data = response.data;
                showEditMode();

                // Function to format date to "YYYY-MM-DDTHH:MM"
                function formatDateToDateTimeLocal(date) {
                    if (!date) return null;
                    const pad = num => String(num).padStart(2, '0');
                    return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
                }

                // Populate the form fields with data
                document.getElementById('coachID2').value = data.CoachID;
                document.getElementById('yardIN').value = formatDateToDateTimeLocal(new Date(data.yard_IN?.date));
                document.getElementById('condition').value = data.condition;
                document.getElementById('workshopIN').value = formatDateToDateTimeLocal(new Date(data.workshop_IN?.date));
                document.getElementById('ncOffered').value = formatDateToDateTimeLocal(new Date(data.NC_offered?.date));
                document.getElementById('ncFit').value = formatDateToDateTimeLocal(new Date(data.NC_fit?.date));
                document.getElementById('despatchedDate').value = formatDateToDateTimeLocal(new Date(data.despatched_date?.date));
                document.getElementById('corrosion_Hrs').value = data.Corr_M_Hrs;
                document.getElementById('pohShop').value = data.POH_shop;
                document.getElementById('repairType').value = data.repair_type;
                document.getElementById('workorderNumber').value = data.workorder_no;
                document.getElementById('returnDate').value = formatDateToDateTimeLocal(new Date(data.return_date?.date));
                document.getElementById('coach_location_input').value = data.CoachLocation;
                document.getElementById('activity_input').value = data.Shop_Activity;

            } else {
                alert('Error fetching data: ' + response.message);
            }
        });
    } else {
        alert('Please enter a CoachID');
    }
});
</script>

        </script>
        <div class="deletecard d-flex justify-content-between">
            <div class="form-row ml-2 d-flex flex-nowrap">
                <form id="deleteform" class="d-flex">
                    <div class="input_box1 mt-2 ml-3"><input type="text" name="maintenanceID" class="form-control" id="maintenanceID" style="width: 120px; font-size: 14px;">
                    <label for="maintenanceID" class="label">Maintenance ID</label>
                    </div>
                </form>
                <button type="button" class="bin-button" id="deletebutton" onclick="deleteCoach()" title="Delete">
                <script>
        document.getElementById('maintenanceID').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevent form submission
                deleteCoach();
            }
        });
    </script>
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 39 7"
                    class="bin-top"
                >
                    <line stroke-width="4" stroke="white" y2="5" x2="39" y1="5"></line>
                    <line
                    stroke-width="3"
                    stroke="white"
                    y2="1.5"
                    x2="26.0357"
                    y1="1.5"
                    x1="12"
                    ></line>
                </svg>
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 33 39"
                    class="bin-bottom"
                >
                    <mask fill="white" id="path-1-inside-1_8_19">
                    <path
                        d="M0 0H33V35C33 37.2091 31.2091 39 29 39H4C1.79086 39 0 37.2091 0 35V0Z"
                    ></path>
                    </mask>
                    <path
                    mask="url(#path-1-inside-1_8_19)"
                    fill="white"
                    d="M0 0H33H0ZM37 35C37 39.4183 33.4183 43 29 43H4C-0.418278 43 -4 39.4183 -4 35H4H29H37ZM4 43C-0.418278 43 -4 39.4183 -4 35V0H4V35V43ZM37 0V35C37 39.4183 33.4183 43 29 43V35V0H37Z"
                    ></path>
                    <path stroke-width="4" stroke="white" d="M12 6L12 29"></path>
                    <path stroke-width="4" stroke="white" d="M21 6V29"></path>
                </svg>
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 89 80"
                    class="garbage"
                >
                    <path
                    fill="white"
                    d="M20.5 10.5L37.5 15.5L42.5 11.5L51.5 12.5L68.75 0L72 11.5L79.5 12.5H88.5L87 22L68.75 31.5L75.5066 25L86 26L87 35.5L77.5 48L70.5 49.5L80 50L77.5 71.5L63.5 58.5L53.5 68.5L65.5 70.5L45.5 73L35.5 79.5L28 67L16 63L12 51.5L0 48L16 25L22.5 17L20.5 10.5Z"
                    ></path>
                </svg>
                </button>
            </div>
        </div>
    </div>
</div>

    <div class="container-fluid mt-1">
        <div class="tablecard d-flex justify-content-between">
            <div class="table-container">
                <table id="dynamic-table">
                    <thead>
                        <tr>
                            <!-- Table headers -->
                            <th style="width: 120px;text-align:center;">Maintenance ID</th>
                            <th style="width: 130px;text-align:center;">Last POH Date</th>
                            <th style="width: 110px;text-align:center;">Previous RD</th>
                            <th style="width: 100px;text-align:center;">Yard IN</th>
                            <th style="width: 130px;text-align:center;">Condition</th>
                            <th style="width: 120px;text-align:center;">Workshop IN</th>
                            <th style="width: 110px;text-align:center;">NC Offered</th>
                            <th style="width: 110px;text-align:center;">NC fit</th>
                            <th style="width: 145px;text-align:center;">Despatched date</th>
                            <th style="width: 95px;text-align:center;">POH Shop</th>
                            <th style="width: 145px;text-align:center;">Repair type</th>
                            <th style="width: 130px;text-align:center;">Workorder No</th>
                            <th style="width: 120px;text-align:center;">Return date</th>
                            <th style="width: 400px; px;text-align:center;">PDF Upload</th>
                            
                        </tr>
                    </thead>
                    <tbody style="text-align:center;">
                        <!-- Dynamic rows will be appended here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add form submit event listener
    document.querySelector('form.mb-3').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent form submission
        let searchInput = document.querySelector('input[name="search"]').value;
        if (searchInput.trim() !== '') {
            fetchCoachDetails(searchInput);
        }
    });

    // Add event listeners for date fields to enforce 4-digit year
    const dateFields = ['yardIN', 'workshopIN', 'ncOffered', 'ncFit', 'despatchedDate', 'returnDate'];
    dateFields.forEach(id => {
        document.getElementById(id).addEventListener('input', function(event) {
            validateDateInput(event.target);
        });
    });

    // Fetch data and populate table on page load
    fetchData();
    showAddMode();
});

function validateDateInput(input) {
    const value = input.value;
    const parts = value.split('-');

    if (parts.length === 3 && parts[0].length > 4) {
        parts[0] = parts[0].slice(0, 4);
        input.value = parts.join('-');
    }
}



function updateFormFields(data) {
    
    document.getElementById('coachID').value = data.coachDetails.CoachID;
    document.getElementById('coachNo').value = data.coachDetails.CoachNo;
    document.getElementById('oldCoachNo').value = data.coachDetails.OldCoachNo;
    document.getElementById('code').value = data.coachDetails.Code;
    document.getElementById('type').value = data.coachDetails.Type;
    document.getElementById('railway').value = data.coachDetails.Railway;
    document.getElementById('vehicleType').value = data.coachDetails.VehicleType;
    document.getElementById('category').value = data.coachDetails.Category;
    document.getElementById('AC_Flag').value = data.coachDetails.AC_Flag;
    document.getElementById('brakesystem').value = data.coachDetails.BrakeSystem;

    let builtDate = data.coachDetails.BuiltDate?.date ? new Date(data.coachDetails.BuiltDate.date) : null;
    let inductionDate = data.coachDetails.InductionDate?.date ? new Date(data.coachDetails.InductionDate.date) : null;
    
    // Function to format date to "YYYY-MM-DDTHH:MM"
    function formatDateToDateTimeLocal(date) {
        if (!date) return null;
        const pad = num => String(num).padStart(2, '0');
        return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
    }
    
    document.getElementById('builtDate').value = formatDateToDateTimeLocal(builtDate);
    document.getElementById('inductionDate').value = formatDateToDateTimeLocal(inductionDate);

    document.getElementById('built').value = data.coachDetails.Built;
    document.getElementById('periodicity').value = data.coachDetails.Periodicity;
    document.getElementById('owningDivision').value = data.coachDetails.OwningDivision;
    document.getElementById('baseDepot').value = data.coachDetails.BaseDepot;
    document.getElementById('workshop').value = data.coachDetails.Workshop;
    document.getElementById('codalLife').value = data.coachDetails.CodalLife;
    document.getElementById('powerGenerationType').value = data.coachDetails.PowerGenerationType;
    document.getElementById('couplingType').value = data.coachDetails.CouplingType;
    document.getElementById('coachID2').value = data.coachDetails.CoachID;
}

function fetchCoachDetails(coachNumber) {
    let formData = new FormData();
    formData.append('coachNumber', coachNumber);

    fetch('get_coach_feed.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            clearFormFields();
            updateFormFields(data);
            fetchData(data.coachDetails.CoachID);
        } else {
            cleardataFields()
            clearTable()
            clearFormFields()
            cleardeleteFields()
            alert(data.message);
        }
    })
    .catch(error => console.error('Error fetching coach details:', error));
}

function cleardataFields() {
    const form = document.getElementById('dataform');
    const inputs = form.querySelectorAll('input');
    inputs.forEach(input => {
            input.value = ''
            ;
    });
}

function fetchData(coachID) {
    let url = coachID ? 'fetch_table_data.php?coachID=' + coachID : 'fetch_table_data.php';
    fetch(url)
        .then(response => {
            if (!response.ok) {
                // If the response status is not OK, throw an error
                return response.text().then(text => {
                    throw new Error('Error: ' + response.status + ' ' + response.statusText + '\n' + text);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                populateTable(data.transactionalData);
            } else {
                clearTable();
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
}



function populateTable(data) {
    const tbody = document.querySelector('#dynamic-table tbody');
    tbody.innerHTML = ''; // Clear existing rows

    data.forEach(row => {
        const tr = document.createElement('tr');

        Object.keys(row).forEach((key, colIndex) => {
            const cell = colIndex === 0 ? document.createElement('th') : document.createElement('td');
            cell.textContent = row[key];
            tr.appendChild(cell);
        });

        // Add PDF buttons to the last cell
        const pdfCell = document.createElement('td');
        pdfCell.style.textAlign = 'center';

        // Create a container for the buttons
        const buttonContainer = document.createElement('div');
        buttonContainer.style.display = 'flex';
        buttonContainer.style.alignItems = 'center';
        buttonContainer.style.justifyContent = 'center';
        buttonContainer.style.gap = '10px';

        // Create status indicator using SVG
        const statusIndicator = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        statusIndicator.setAttribute('width', '24');
        statusIndicator.setAttribute('height', '24');
        statusIndicator.setAttribute('viewBox', '0 0 24 24');
        statusIndicator.style.marginRight = '5px';

        // Create file name or upload button container
        const fileContainer = document.createElement('div');
        fileContainer.style.minWidth = '150px'; // Adjust as needed

        // Create preview button with provided inline styling
        const previewButton = document.createElement('button');
        previewButton.className = 'PreviewBtn';
        previewButton.innerHTML = `
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 95 44" enable-background="new 0 0 100 25" xml:space="preserve" width="36" height="36">
                    <g transform="translate(10, -22) scale(0.45)">
                        <path id="eye" fill="white" stroke="white" stroke-width="3" d="M4.6,99.2l-1.4,1.8l1.4,1.8c31.6,41.4,74.3,43.5,82.5,43.5c0.5,0,0.9,0,0.9,0
                          c0.1,0,0.5,0,1,0c8.2,0,50.9-2.1,82.5-43.5l1.4-1.8l-1.4-1.8c-31.4-41-73.9-43.4-82.1-43.5c-1-0.1-1.9-0.1-2.7-0.1
                          c-1.8,0-3.7,0.1-5.6,0.4C67.2,57.1,31.7,63.7,4.6,99.2z M115.3,135.9c10.5-8.6,16.7-21.3,16.7-35c0-13.6-6-26.2-16.4-34.8
                          c14.7,4.7,33.7,14.5,49.9,34.9C149.2,121.4,130.1,131.2,115.3,135.9z M41.5,100.8c0,12.9,5.5,25,15,33.6c-13.5-4.9-30.9-14.6-45.9-33.5
                          c14.9-18.8,32.2-28.4,45.6-33.4C46.9,76,41.5,88.1,41.5,100.8z M47.3,100.8c0-19.8,14.8-36.6,34.4-39.1c2.4-0.2,4.2-0.2,5.3-0.2
                          c0.5,0,0.8,0,0.9,0l0.8,0l0.5,0l0,0c20.7,1.3,36.9,18.6,36.9,39.3c0,21.7-17.7,39.4-39.4,39.4S47.3,122.6,47.3,100.8z"/>
                        <path id="pupil" fill="white" stroke="white" stroke-width="3" d="M86.7,116.9c8.8,0,16-7.2,16-16c0-8.8-7.2-16-16-16s-16,7.2-16,16C70.7,109.7,77.9,116.9,86.7,116.9z
                          M86.7,90.6c5.6,0,10.2,4.6,10.2,10.2c0,5.6-4.6,10.2-10.2,10.2s-10.2-4.6-10.2-10.2C76.5,95.2,81.1,90.6,86.7,90.6z"/>
                    </g>
                </svg>`;
        previewButton.style.display = 'flex';
        previewButton.style.alignItems = 'center';
        previewButton.style.justifyContent = 'flex-start';
        previewButton.style.width = '36px';
        previewButton.style.height = '36px';
        previewButton.style.border = 'none';
        previewButton.style.borderRadius = '10px';
        previewButton.style.cursor = 'pointer';
        previewButton.style.position = 'relative';
        previewButton.style.overflow = 'hidden';
        previewButton.style.backgroundColor = '#00008b';
        previewButton.addEventListener('click', () => previewFile(row['MaintenanceID']));

        // Add animation and styling for hover effect
        const style = document.createElement('style');
        style.textContent = `
            .PreviewBtn:focus {
            outline: none;
            }
            .PreviewBtn svg {
              display: flex;
              justify-content: center;
            }
            .PreviewBtn svg #eye, .PreviewBtn svg #pupil {
                transition: all 0.2s ease;
            }
            .PreviewBtn:hover svg #eye, .PreviewBtn:hover svg #pupil {
                animation: squeeze 2s infinite;
                fill: white;
            }
            @keyframes squeeze {
                10% {
                    transform: none;
                    animation-timing-function: ease-in;
                }
                13% {
                    transform: translateY(69px) scaleY(.3);
                }
                20% {
                    animation-timing-function: ease-out;
                }
                21% {
                    transform: none;
                    animation-timing-function: ease-in;
                }
            }
        `;
        document.head.appendChild(style);

        // Create delete button
        const deleteButton = document.createElement('button');
deleteButton.textContent = 'Delete';

deleteButton.style.backgroundColor = 'red';
deleteButton.style.color = 'white';
deleteButton.style.border = 'none';
deleteButton.style.borderRadius = '10px';
deleteButton.style.fontSize = '14px';
deleteButton.style.cursor = 'pointer';
deleteButton.style.marginLeft = '0px';
deleteButton.style.marginRight = '0px';
deleteButton.style.marginTop = '0px';
deleteButton.addEventListener('click', () => deleteFile(row['MaintenanceID']));

// Implementing custom styles and SVGs for the delete button
deleteButton.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 39 7" class="bin-top">
            <line stroke-width="4" stroke="white" y2="5" x2="39" y1="5"></line>
            <line stroke-width="3" stroke="white" y2="1.5" x2="26.0357" y1="1.5" x1="12"></line>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 33 39" class="bin-bottom">
            <mask fill="white" id="path-1-inside-1_8_19">
                <path d="M0 0H33V35C33 37.2091 31.2091 39 29 39H4C1.79086 39 0 37.2091 0 35V0Z"></path>
            </mask>
            <path mask="url(#path-1-inside-1_8_19)" fill="white" d="M0 0H33H0ZM37 35C37 39.4183 33.4183 43 29 43H4C-0.418278 43 -4 39.4183 -4 35H4H29H37ZM4 43C-0.418278 43 -4 39.4183 -4 35V0H4V35V43ZM37 0V35C37 39.4183 33.4183 43 29 43V35V0H37Z"></path>
            <path stroke-width="4" stroke="white" d="M12 6L12 29"></path>
            <path stroke-width="4" stroke="white" d="M21 6V29"></path>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 89 80" class="garbage">
            <path fill="white" d="M20.5 10.5L37.5 15.5L42.5 11.5L51.5 12.5L68.75 0L72 11.5L79.5 12.5H88.5L87 22L68.75 31.5L75.5066 25L86 26L87 35.5L77.5 48L70.5 49.5L80 50L77.5 71.5L63.5 58.5L53.5 68.5L65.5 70.5L45.5 73L35.5 79.5L28 67L16 63L12 51.5L0 48L16 25L22.5 17L20.5 10.5Z"></path>
        </svg>
    `;

// Adding custom class for styling
deleteButton.classList.add('bin-button');

// Append the button to the DOM or wherever appropriate in your code
document.body.appendChild(deleteButton);


        // Check PDF status
        fetch(`check_pdf_status.php?maintenanceID=${row['MaintenanceID']}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.has_pdf) {
                    statusIndicator.innerHTML = `
                        <circle cx="12" cy="12" r="10" fill="none" stroke="green" stroke-width="2"/>
                        <path d="M9 12l2 2 4-4" fill="none" stroke="green" stroke-width="2"/>
                    `;
                    // Display file name
                    fileContainer.textContent = data.pdf_name;
                    fileContainer.style.color = '#007bff';
                    fileContainer.style.fontStyle = 'montserrat';
                    
                    // Show preview and delete buttons
                    buttonContainer.appendChild(previewButton);
                    buttonContainer.appendChild(deleteButton);
                } else {
                    statusIndicator.innerHTML = `
                        <circle cx="12" cy="12" r="10" fill="none" stroke="red" stroke-width="2"/>
                        <path d="M8 8l8 8M16 8l-8 8" stroke="red" stroke-width="2"/>
                    `;
                    // Show the "Choose File" button
                    const uploadLabel = document.createElement('label');
                    uploadLabel.style.display = 'inline-block';
                    uploadLabel.style.padding = '6px 12px';
                    uploadLabel.style.cursor = 'pointer';
                    uploadLabel.style.backgroundColor = '#00008b';
                    uploadLabel.style.color = 'white';
                    uploadLabel.style.borderRadius = '4px';
                    uploadLabel.style.fontSize = '14px';
                    uploadLabel.style.marginTop = '2px'; // Add margin-top
                    uploadLabel.style.marginBottom = '2px'; // Add margin-bottom
                    uploadLabel.textContent = 'Choose File';

                    const uploadInput = document.createElement('input');
                    uploadInput.type = 'file';
                    uploadInput.style.display = 'none';
                    uploadInput.addEventListener('change', (e) => handleFileUpload(e, row['MaintenanceID']));

                    uploadLabel.appendChild(uploadInput);
                    fileContainer.appendChild(uploadLabel);
                    
                    // Hide preview and delete buttons
                    previewButton.style.display = 'none';
                    deleteButton.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error checking PDF status:', error);
                statusIndicator.innerHTML = `
                    <circle cx="12" cy="12" r="10" fill="none" stroke="gray" stroke-width="2"/>
                    <text x="12" y="16" text-anchor="middle" fill="gray" font-size="14">?</text>
                `;
                // Show error message in file container
                fileContainer.textContent = 'Error checking status';
                fileContainer.style.color = 'red';
                
                // Hide preview and delete buttons
                previewButton.style.display = 'none';
                deleteButton.style.display = 'none';
            });

        buttonContainer.appendChild(statusIndicator);
        buttonContainer.appendChild(fileContainer);
        buttonContainer.appendChild(previewButton);
        buttonContainer.appendChild(deleteButton);

        pdfCell.appendChild(buttonContainer);
        tr.appendChild(pdfCell);
        tbody.appendChild(tr);
    });
}

function handleFileUpload(event, maintenanceID) {
    const file = event.target.files[0];
    if (file) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('mid', maintenanceID);

        fetch('upload_file_feed.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('File uploaded successfully!');
                // Refresh the table or update just this row
                // You might want to call a function here to refresh the data
                document.getElementById('searchButton').click();
            } else {
                alert('File upload failed: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error uploading file:', error);
            alert('Error uploading file: ' + error.message);
        });
    }
}

function uploadFile(file, mid) {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('mid', mid); // Append MID to formData

    fetch('upload_file_feed.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) // Change to text to see the raw response
    .then(data => {
        console.log('Response:', data); // Log the raw response
        try {
            const jsonData = JSON.parse(data);
            if (jsonData.success) {
                alert('File uploaded successfully!');
                
            } else {
                alert('File upload failed: ' + jsonData.error);
            }
        } catch (error) {
            console.error('Error parsing JSON:', error);
            alert('Error parsing JSON: ' + error.message);
        }
    })
    .catch(error => {
        console.error('Error uploading file:', error);
        alert('Error uploading file: ' + error.message);
    });
}

function previewFile(mid) {
    window.open(`preview_file.php?mid=${mid}`, '_blank');
}

function deleteFile(mid) {
    if (confirm('Are you sure you want to delete this file?')) {
        fetch(`delete_file.php?mid=${mid}`, {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('File deleted successfully!');
                document.getElementById('searchButton').click();
            } else {
                alert('File deletion failed: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error deleting file:', error);
            alert('Error deleting file: ' + error.message);
        });
    }
}

function clearTable() {
    const tbody = document.querySelector('#dynamic-table tbody');
    tbody.innerHTML = ''; // Clear existing rows
}

// Function to add a new row to the table (example implementation)
function addRow(coachData) {
    console.log('Adding row with data:', coachData);
    const tableBody = document.querySelector('#dynamic-table tbody');
    const newRow = document.createElement('tr');
    
    // Example: Inserting data into table cells (adjust according to your table structure)
    newRow.innerHTML = 
    '<td>' + coachData.maintenanceID + '</td>' +
    // '<td>' + coachData.coachID + '</td>' +
    '<td>' + coachData.lastPOHDate + '</td>' +
    '<td>' + coachData.previousRD + '</td>' +
    '<td>' + coachData.yardIN + '</td>' +
    '<td>' + coachData.condition + '</td>' +
    '<td>' + coachData.workshopIN + '</td>' +
    '<td>' + coachData.ncOffered + '</td>' +
    '<td>' + coachData.ncFit + '</td>' +
    '<td>' + coachData.despatchedDate + '</td>' +
    '<td>' + coachData.pohShop + '</td>' +
    '<td>' + coachData.repairType + '</td>' +
    '<td>' + coachData.workorderNumber + '</td>' +
    '<td>' + coachData.returnDate + '</td>';
    
    
    tableBody.appendChild(newRow);

    document.getElementById('searchButton').click();

}

function clearFormFields() {
    const form = document.getElementById('coachForm');
    const inputs = form.querySelectorAll('input');
    const dropdowns = form.querySelectorAll('select');
    
    // Clear all input fields
    inputs.forEach(input => {
        input.value = '';
    });
    
    // Clear all dropdowns
    dropdowns.forEach(select => {
        select.selectedIndex = 0; // Reset to the first option
    });
}


// Function to add form data
function addForm() {
    const coachIDInput = document.getElementById('coachID2');
    const coachIDValue = coachIDInput.value.trim(); // Get trimmed value of coachID input
    
    if (coachIDValue === '') {
        alert('Please fetch Coach No before adding.'); // Display an alert if coachID is empty
        return; // Exit function if coachID is empty
    }

    const formData = new FormData(document.getElementById('coachForm'));

    fetch('add_feed.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); // Log response to debug
        if (data.success) {
            addRow(data.coach); // Call addRow function with the data received
            clearFormFields();
            alert('Record added successfully!');
            
            // Check for additional alert message
            if (data.alert) {
                alert(data.alert);
            }
        } else {
            console.error('Failed to add coach data:', data.error); // Log error details
            alert('Failed to add coach data: ' + JSON.stringify(data.error)); // Show error to user
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error); // Log fetch error
        alert('Failed to add coach data: ' + error.message); // Show fetch error to user
    });
}

function deleteCoach() {
    const maintenanceID = document.getElementById('maintenanceID').value;
    const coachID = document.getElementById('coachID').value;

    if (maintenanceID === '') {
        alert('Please enter a Maintenance ID.');
        return;
    }

    if (confirm('Are you sure you want to delete this transaction?')) {
        // First, check and delete the PDF if it exists
        fetch(`check_and_delete_pdf.php?mid=${encodeURIComponent(maintenanceID)}`, {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('PDF deleted successfully or no PDF found.');
            } else {
                console.error('Error checking/deleting PDF:', data.error);
            }
            
            // Proceed with deleting the coach details
            return fetch('delete_feed.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'maintenanceID=' + encodeURIComponent(maintenanceID) + '&coachID=' + encodeURIComponent(coachID),
            });
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                cleardeleteFields()
                fetchData(); // Fetch updated data after deletion
                document.getElementById('searchButton').click();
                alert('Deleted transaction with Maintenance ID: ' + maintenanceID);
            } else {
                alert('Error deleting coach details: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error in deletion process:', error);
            alert('Failed to delete coach details.');
        });
    }
}

function cleardeleteFields() {
    const form = document.getElementById('deleteform');
    const inputs = form.querySelectorAll('input');
    inputs.forEach(input => {
            input.value = ''
            ;
    });
}

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