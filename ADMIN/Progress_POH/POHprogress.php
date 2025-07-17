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
    <title>POH Progress</title>
    <link rel="icon" href="../../SCR_Logo.png" type="image/png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<script src="progress.js"></script>
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
            background-color: #00008B;
            color: white;
            border-radius: 7px;
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
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 10000;
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
        .card-container {
            margin-top: 20px;
            margin-left:20px;
            height:80px;
            width:500px;
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
            
            display: flex;
            align-items: center;
            border: none;
            border-radius: 25px;
            overflow: hidden;
            transition: all 0.2s;
            cursor: pointer;
            font-weight: 550;
            height: 36px;
            width: 100px;
            background-size: cover;
            margin-left: auto;
            margin-right: auto;
            outline: none;
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
            outline: none;
            border: none;
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
            border: none;
        }


        .searchcard1 {
            height: 50px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            padding-top: 24px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
            margin-top: 68px;
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
            height: 90px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            padding-top:13px;
            padding-bottom:13px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
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

        .input_box1 {
            position: relative;
            font-weight: bold;
        }

        .input_box1 select,
        .input_box1 input[type="text"],
        .input_box1 input[type="date"] {
            width: 100%;
            padding: 11px;
            border: 1.5px solid white;
            border-radius: 10px;
            font-size: 12.5px;
            outline: none;
            height: 36px;
            background-color: transparent; /* Ensure this line is correct */
            color: white;
            font-weight: bold;
        }

        .input_box1 label {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #00008B;
            padding: 0 5px;
            color: white;
            font-size: 16px;
            transition: all 0.3s;
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
            color: white;
        }

        /* ----- */
        input.highlight-red {
            background-color: #ffcccc !important;
            border: 1.5px solid red !important;
        }


        body {
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            background-color: #f0f4f8;
        }

        .input_box2 {
            position: relative;
            font-weight: bold;
        }

        .input_box2 select,
        .input_box2 input[type="text"],
        .input_box2 input[type="date"],
        .input_box2 input[type="datetime-local"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #00008B;
            border-radius: 10px;
            font-size: 12px;
            outline: none;
            height: 32px;
            color: black;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .input_box2 label {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: white;
            color: #00008B;
            font-size: 12px;
            pointer-events: none;
            padding: 0 4px;
            height: 15px;
            border-radius: 3px;
            
        }

        #inspection-input-2 {
            border: 2px solid #ccc;  /* Adjust border color */
            border-radius: 25px;     /* Make the input look like a capsule */
            padding: 2px 6px;      /* Adjust padding for a capsule look */
            font-size: 12px;         /* Adjust font size as needed */
            color: white;
            width: 70px;            /* Adjust width as needed */
            background-color: #f9f9f9; /* Adjust background color */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optional: add a shadow */
            font-weight: 550;
            margin-left: auto;
        }
        #lift-lower-input-2{
            border: 2px solid #ccc;  /* Adjust border color */
            border-radius: 25px;     /* Make the input look like a capsule */
            padding: 2px 6px;       /* Adjust padding for a capsule look */
            font-size: 12px;         /* Adjust font size as needed */
            color: white;
            width: 70px;            /* Adjust width as needed */
            background-color: #f9f9f9; /* Adjust background color */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optional: add a shadow */
            font-weight: 550;
            margin-left: auto;
        }
        #corrosion-input-2{
            border: 2px solid #ccc;  /* Adjust border color */
            border-radius: 25px;     /* Make the input look like a capsule */
            padding: 2px 6px;       /* Adjust padding for a capsule look */
            font-size: 12px;         /* Adjust font size as needed */
            color: white;
            width: 70px;            /* Adjust width as needed */
            background-color: #f9f9f9; /* Adjust background color */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optional: add a shadow */
            font-weight: 550;
            margin-left: auto;
        }
        #paint-input-2{
            border: 2px solid #ccc;  /* Adjust border color */
            border-radius: 25px;     /* Make the input look like a capsule */
            padding: 2px 6px;       /* Adjust padding for a capsule look */
            font-size: 12px;         /* Adjust font size as needed */
            color: white;
            width: 70px;            /* Adjust width as needed */
            background-color: #f9f9f9; /* Adjust background color */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optional: add a shadow */
            font-weight: 550;
            margin-left: auto;
        }
        #trimming-input-2{
            border: 2px solid #ccc;  /* Adjust border color */
            border-radius: 25px;     /* Make the input look like a capsule */
            padding: 2px 6px;       /* Adjust padding for a capsule look */
            font-size: 12px;         /* Adjust font size as needed */
            color: white;
            width: 70px;            /* Adjust width as needed */
            background-color: #f9f9f9; /* Adjust background color */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optional: add a shadow */
            font-weight: 550;
            margin-left: auto;
        }
        #water-tank-input-2{
            border: 2px solid #ccc;  /* Adjust border color */
            border-radius: 25px;     /* Make the input look like a capsule */
            padding: 2px 6px;       /* Adjust padding for a capsule look */
            font-size: 12px;         /* Adjust font size as needed */
            color: white;
            width: 70px;            /* Adjust width as needed */
            background-color: #f9f9f9; /* Adjust background color */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optional: add a shadow */
            font-weight: 550;
            margin-left: auto;
        }
        #carriage-input-2{
            border: 2px solid #ccc;  /* Adjust border color */
            border-radius: 25px;     /* Make the input look like a capsule */
            padding: 2px 6px;      /* Adjust padding for a capsule look */
            font-size: 12px;         /* Adjust font size as needed */
            color: white;
            width: 70px;            /* Adjust width as needed */
            background-color: #f9f9f9; /* Adjust background color */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optional: add a shadow */
            font-weight: 550;
            margin-left: auto;
        }
        #etl-ac-input-2{
            border: 2px solid #ccc;  /* Adjust border color */
            border-radius: 25px;     /* Make the input look like a capsule */
            padding: 2px 6px;      /* Adjust padding for a capsule look */
            font-size: 12px;         /* Adjust font size as needed */
            color: white;
            width: 70px;            /* Adjust width as needed */
            background-color: #f9f9f9; /* Adjust background color */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optional: add a shadow */
            font-weight: 550;
            margin-left: auto;
        }
        #air-brake-input-2{
            border: 2px solid #ccc;  /* Adjust border color */
            border-radius: 25px;     /* Make the input look like a capsule */
            padding: 2px 6px;       /* Adjust padding for a capsule look */
            font-size: 12px;         /* Adjust font size as needed */
            color: white;
            width: 70px;            /* Adjust width as needed */
            background-color: #f9f9f9; /* Adjust background color */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optional: add a shadow */
            font-weight: 550;
            margin-left: auto;
        }
        #ntxr-input-2{
            border: 2px solid #ccc;  /* Adjust border color */
            border-radius: 25px;     /* Make the input look like a capsule */
            padding: 2px 6px;       /* Adjust padding for a capsule look */
            font-size: 12px;         /* Adjust font size as needed */
            color: white;
            width: 70px;            /* Adjust width as needed */
            background-color: #f9f9f9; /* Adjust background color */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optional: add a shadow */
            font-weight: 550;
            margin-left: auto;
        }


        .input_box2 input[type="text"]:focus+label,
        .input_box2 input[type="text"]:not(:placeholder-shown)+label,
        .input_box2 input[type="date"]:focus+label,
        .input_box2 input[type="date"]:not(:placeholder-shown)+label,
        .input_box2 input[type="datetime-local"]:not(:placeholder-shown)+label,
        .input_box2 select:focus+label,
        .input_box2 select:not([value=""]):not([value="undefined"])+label {
            top: -10px;
            left: 20px;
            font-size: 12px;
            color: #00008B;
        }

        .navbar {
            height: 60px;
            background-color: #00008B;
            color: white;
        }

        .navbar-brand,
        .navbar-nav .nav-link {
            color: white;
        }

        .card-header {
            color: white;
            background-color: #00008B;
            padding: 9px;
            border-bottom: 1px solid #dee2e6;
            font-size:12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .card-header input[type="text"] {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 2px solid #ccc;
            text-align: center;
            font-size: 1rem;
            margin-left: auto;
        }

        .pohcard{
            height:auto;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 21px;
            padding-top:12px;
            padding-bottom:0px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
            margin-bottom: 8px;
        }

        input[type="datetime-local"][readonly] {
            background-color: none;
            cursor: not-allowed;
        }
        input[type="date"][readonly] {
            cursor: not-allowed;
        }

        .form-control:focus {
            outline: none;
        }

        .form-control:active {
            outline: none;
        }

        .card-body1 {
            margin-top: 15px;
            margin-bottom: 15px;
            margin-left: 10px;
            margin-right: 10px;
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

        .print-button {
            position: fixed;
            padding: 0;
            width: 100px;
            height: 36px;
            border-radius: 25px;
            background: #00008b;
            color: white;
            font-size: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Montserrat', sans-serif;
            font-weight: 550;
            margin-top: 14px;
            margin-bottom: 14px;
            border: none;
            outline: none;
            left: 90%;
        }

        span.print-icon, span.print-icon::before, span.print-icon::after, button.print-button:hover .print-icon::after {
            border: solid 1px black;
        }
        span.print-icon::after {
            border-width: 1.8px;
        }

        span.print-icon, span.print-icon::before, span.print-icon::after, .print-button:hover .print-icon::after {
            box-sizing: border-box;
            background-color: white;
        }

        span.print-icon {
            position: relative;
            display: inline-block;  
            padding: 0;
            margin-top: 13%;
            right: 5%;
            width: 26%;
            height: 35%;
            background: #fff;
            border-radius: 20% 20% 0 0;
        }

        span.print-icon::before {
            content: "";
            position: absolute;
            bottom: 100%;
            left: 12%;
            right: 12%;
            height: 110%;
            transition: height .2s .15s;
        }

        span.print-icon::after {
            content: "";
            position: absolute;
            top: 55%;
            left: 12%;
            right: 12%;
            height: 0%;
            background: #fff;
            background-repeat: no-repeat;
            background-size: 70% 90%;
            background-position: center;
            background-image: linear-gradient(
                to top,
                #fff 0, #fff 14%,
                #333 14%, #333 28%,
                #fff 24%, #fff 38%,
                #333 42%, #333 56%,
                #fff 56%, #fff 70%,
                #333 70%, #333 84%,
                #fff 84%, #fff 100%
            );
            transition: height .2s, border-width 0s .2s, width 0s .2s;
        }

        .print-button:hover {
            cursor: pointer;
        }

        .print-button:hover .print-icon::before {
            height:0px;
            transition: height .2s;
        }
        .print-button:hover .print-icon::after {
            height: 120%;
            transition: height .2s .15s, border-width 0s .16s;
        }
        .print-button:active {
            border: none;
            outline: none;
        }
        .print-button:focus {
            border: none;
            outline: none;
        }

</style>

<script src="progress.js"></script>
    
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div class="d-flex align-items-center">
            <button class="openbtn" onclick="openNav()">☰</button>
                <img src="../../SCR_Logo.jpg" class="image mr-2" alt="Logo">
                <p class="navbar-brand mb-0">CIMS - POH Progress</p>
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
    <div class="searchcard1 d-flex justify-content-between">
    <form class="mb-3 d-flex align-items-center" id="searchForm">
        <input type="text" name="search" class="form-control" placeholder="Search Coach No" style="width: 200px; font-size: 14px;" >
        <button class="searchbutton" id="searchButton">
        <svg class="svgIcon" viewBox="0 0 512 512" height="1em" xmlns="http://www.w3.org/2000/svg">
        <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm50.7-186.9L162.4 380.6c-19.4 7.5-38.5-11.6-31-31l55.5-144.3c3.3-8.5 9.9-15.1 18.4-18.4l144.3-55.5c19.4-7.5 38.5 11.6 31 31L325.1 306.7c-3.2 8.5-9.9 15.1-18.4 18.4zM288 256a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z" fill="white"></path>
        </svg>
        Search
        </button>
        <button class="print-button" onclick="openPHPFile()"><span class="print-icon"></span>Print</button>
        <script>
            function openPHPFile() {
                window.open('POH_pdf_gen.php', '_blank');
            }
        </script>
    </form>
    </div>
    </div>
    


    
<div class="container-fluid mt-1">
    <div class="datacard d-flex justify-content-between">
    <div class="container-fluid" style="">
        <form id="dataform" method="post" action="search.php">

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

            <div class="form-row" style="margin-top:5px;">

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
        <div class="tablecard d-flex justify-content-between">
            <div class="table-container">
                <table id="dynamic-table">
                    <thead>
                        <tr>
                            <!-- Table headers -->
                            <th style="width: 120px;text-align:center;">Maintenance ID</th>
                            <th style="width: 130px;text-align:center;">Last POH Date</th>
                            <th style="width: 110px;text-align:center;">Previous RD</th>
                            
                            <th style="width: 120px;text-align:center;">Workshop IN</th>
                            <th style="width: 110px;text-align:center;">NC Offered</th>
                            
                            <th style="width: 145px;text-align:center;">Despatched date</th>
                            <th style="width: 95px;text-align:center;">POH Shop</th>
                            <th style="width: 145px;text-align:center;">Repair type</th>
                            <th style="width: 120px;text-align:center;">Return date</th>
                        </tr>
                    </thead>
                    <tbody style="text-align:center;">
                        <!-- Dynamic rows will be appended here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-2">
        <div class="card pohcard">

        <div class="card-header0 d-flex flex-row align-items-center" style="color:white;background-color: #00008B;border-radius:10px; position: relative; padding: 10px;">
            <div class="input_box1 col-md-1-5 d-flex align-items-center mt-1 ml-2" style="position: relative; z-index: 1;">
                <input type="text" name="MID" id="MID" size="7" maxlength="7" class="form-control" readonly>
                <label for="MID" class="mb-0 ms-2">Maintenance ID</label>
            </div>
            <div class="position-absolute w-100 text-center">
                <p style="color:white;margin:0;font-size:25px;font-weight: 550;font-family:'Montserrat', sans-serif;">POH PROGRESS</p>
            </div>
        </div>


            <div class="card-body" style="margin-left:-30px;margin-right:-30px;">

                <form id="myForm" method="POST">

                    <input type="hidden" id="hiddenCoachNo" name="hiddenCoachNo">
                    <input type="date" id="workshop_in" style="display: none;"/>

                    <div class="container-fluid">
                        <div class="card-deck">
                            <!-- Inspection -->
                            <div class="card mb-3" style=" border-radius: 15px; box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);">
                                <div class="card-header" style="border-radius:10px;">
                                    <b> Inspection</b>
                                    <input type="text" id="inspection-input-1" readonly>
                                    <input type="text" id="inspection-input-2" readonly>
                                </div>
                                
                                <div class="card-body1">
                                    <div class="input_box2 col-md-12">
                                        <input type="datetime-local" class="form-control" id="P_Insp_IN" name="P_Insp_IN"
                                            onchange="confirmAllDates('P_Insp_IN', 'Primary Inspection IN', 'P_Insp_PDC'); updateDifference('P_Insp_IN', 'P_Insp_OUT', 'inspection-input-2', 'inspection-input-1');">
                                        <label for="P_Insp_IN">IN</label>
                                        <input type="hidden" id="P_Insp_IN_confirmed" name="P_Insp_IN_confirmed" value="true">
                                    </div>
                                    <div class="input_box2 col-md-12">
                                        <input type="date" class="form-control" id="P_Insp_PDC" name="P_Insp_PDC" readonly>
                                        <label for="P_Insp_PDC">PDC</label>
                                    </div>
                                    
                                    <div class="input_box2 col-md-12">
                                        <input type="datetime-local" class="form-control" id="P_Insp_OUT" name="P_Insp_OUT"
                                            onchange="confirmAllDates('P_Insp_OUT', 'Primary Inspection OUT', 'P_Insp_PDC'); updateDifference('P_Insp_IN', 'P_Insp_OUT', 'inspection-input-2', 'inspection-input-1');">
                                        <label for="P_Insp_OUT">OUT</label>
                                        <input type="hidden" id="P_Insp_OUT_confirmed" name="P_Insp_OUT_confirmed" value="true">
                                    </div>
                                </div>

                            </div>

                            <!-- Lift&Lower -->
                            <div class="card mb-3" style=" border-radius: 15px; box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);">
                                <div class="card-header" style="border-radius:10px;">
                                    <b>Lift & Lower</b>
                                    <input type="text" id="lift-lower-input-1" readonly>
                                    <input type="text" id="lift-lower-input-2" readonly>
                                </div>
                                <div class="card-body1">
                                    <div class="input_box2 col-md-12">
                                        <input type="datetime-local" class="form-control" id="L_L_IN" name="L_L_IN"
                                            onchange="confirmAllDates('L_L_IN', 'Lift&Lower IN', 'L_L_PDC');updateDifference('L_L_IN', 'L_L_OUT', 'lift-lower-input-2', 'lift-lower-input-1')">
                                            <label for="L_L_IN">IN</label>
                                            <input type="hidden" id="L_L_IN_confirmed" name="L_L_IN_confirmed" value="true">

                                    </div>
                                    <div class="input_box2 col-md-12">
                                        <input type="date" class="form-control" id="L_L_PDC" name="L_L_PDC" readonly>
                                        <label for="L_L_PDC">PDC</label>

                                    </div>
                                    <div class="input_box2 col-md-12">
                                        <input type="datetime-local" class="form-control" id="L_L_OUT" name="L_L_OUT"
                                            onchange="confirmAllDates('L_L_OUT', 'Lift&Lower OUT', 'L_L_PDC');updateDifference('L_L_IN', 'L_L_OUT', 'lift-lower-input-2', 'lift-lower-input-1')">
                                            <label for="L_L_OUT">OUT</label>
                                            <input type="hidden" id="L_L_OUT_confirmed" name="L_L_OUT_confirmed" value="true">
                                    </div>
                                </div>
                            </div>



                            <!-- Corrosion -->
                            <div class="card mb-3" style=" border-radius: 15px; box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);">
                                <div class="card-header" style="border-radius:10px;">                                    
                                    <b>Corrosion</b>
                                    <input type="text" id="corrosion-input-1" readonly>
                                    <input type="text" id="corrosion-input-2" readonly>
                                </div>
                                <div class="card-body1">
                                    <div class="input_box2 col-md-12">
                                        <input type="datetime-local" class="form-control" id="Corr_IN" name="Corr_IN"
                                            onchange="confirmAllDates('Corr_IN', 'Corrosion IN', 'Corr_PDC');updateDifference('Corr_IN', 'Corr_OUT', 'corrosion-input-2', 'corrosion-input-1')">
                                            <label for="Corr_IN">IN</label>
                                            <input type="hidden" id="Corr_IN_confirmed" name="Corr_IN_confirmed" value="true">

                                    </div>
                                    <div class="input_box2 col-md-12">
                                        <input type="date" class="form-control" id="Corr_PDC" name="Corr_PDC" readonly>
                                        <label for="Corr_PDC">PDC</label>
                                    </div>
                                    <div class="input_box2 col-md-12">
                                        <input type="datetime-local" class="form-control" id="Corr_OUT" name="Corr_OUT"
                                            onchange="confirmAllDates('Corr_OUT', 'Corrosion OUT', 'Corr_PDC');updateDifference('Corr_IN', 'Corr_OUT', 'corrosion-input-2', 'corrosion-input-1')">
                                            <label for="Corr_OUT">OUT</label>
                                            <input type="hidden" id="Corr_OUT_confirmed" name="Corr_OUT_confirmed" value="true">

                                    </div>
                                </div>
                            </div>


                            <!-- Paint -->
                            <div class="card mb-3" style=" border-radius: 15px; box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);">
                                <div class="card-header" style="border-radius:10px;">
                                    <b>Paint</b>
                                    <input type="text" id="paint-input-1" readonly>
                                    <input type="text" id="paint-input-2" readonly>
                                </div>
                                <div class="card-body1">
                                    <div class="input_box2 col-md-12">
                                        <input type="datetime-local" class="form-control" id="Paint_IN" name="Paint_IN"
                                            onchange="confirmAllDates('Paint_IN', 'Paint IN', 'Paint_PDC');updateDifference('Paint_IN', 'Paint_OUT', 'paint-input-2', 'paint-input-1')">
                                            <label for="Paint_IN">IN</label>
                                            <input type="hidden" id="Paint_IN_confirmed" name="Paint_IN_confirmed" value="true">

                                    </div>
                                    <div class="input_box2 col-md-12">
                                        <input type="date" class="form-control" id="Paint_PDC" name="Paint_PDC" readonly>
                                        <label for="Paint_PDC">PDC</label>

                                    </div>
                                    <div class="input_box2 col-md-12">
                                        <input type="datetime-local" class="form-control" id="Paint_OUT" name="Paint_OUT"
                                            onchange="confirmAllDates('Paint_OUT', 'Paint OUT', 'Paint_PDC');updateDifference('Paint_IN', 'Paint_OUT', 'paint-input-2', 'paint-input-1')">
                                            <label for="Paint_OUT">OUT</label>
                                            <input type="hidden" id="Paint_OUT_confirmed" name="Paint_OUT_confirmed" value="true">

                                    </div>
                                </div>
                            </div>
                            <!-- Triming -->
                            <div class="card mb-3" style=" border-radius: 15px; box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);">
                                <div class="card-header" style="border-radius:10px;">
                                    <b>Triming</b>
                                    <input type="text" id="trimming-input-1" readonly>
                                    <input type="text" id="trimming-input-2" readonly>
                                </div>
                                <div class="card-body1">
                                    <div class="input_box2 col-md-12">
                                        <input type="datetime-local" class="form-control" id="Berth_IN" name="Berth_IN"
                                            onchange="confirmAllDates('Berth_IN', 'Triming IN', 'Berth_PDC');updateDifference('Berth_IN', 'Berth_OUT', 'trimming-input-2', 'trimming-input-1')">
                                            <label for="Berth_IN">IN</label>
                                            <input type="hidden" id="Berth_IN_confirmed" name="Berth_IN_confirmed" value="true">
                                    </div>
                                    <div class="input_box2 col-md-12">
                                        <input type="date" class="form-control" id="Berth_PDC" name="Berth_PDC" readonly>
                                        <label for="Berth_PDC">PDC</label>
                                    </div>
                                    <div class="input_box2 col-md-12">
                                        <input type="datetime-local" class="form-control" id="Berth_OUT" name="Berth_OUT"
                                            onchange="confirmAllDates('Berth_OUT', 'Triming OUT', 'Berth_PDC');updateDifference('Berth_IN', 'Berth_OUT', 'trimming-input-2', 'trimming-input-1')">
                                            <label for="Berth_OUT">OUT</label>
                                            <input type="hidden" id="Berth_OUT_confirmed" name="Berth_OUT_confirmed" value="true">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-deck">

                            <!-- Water Tank -->
                            <div class="card mb-3" style=" border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                                <div class="card-header" style="border-radius:10px;">
                                    <b>Water Tank</b>
                                    <input type="text" id="water-tank-input-1" readonly>
                                    <input type="text" id="water-tank-input-2" readonly>
                                </div>
                                <div class="card-body1">
                                    <div class="input_box2 col-md-12">
                                        <input type="datetime-local" class="form-control" id="W_Tank_IN" name="W_Tank_IN"
                                            onchange="confirmAllDates('W_Tank_IN', 'Water Tank IN', 'W_Tank_PDC');updateDifference('W_Tank_IN', 'W_Tank_OUT', 'water-tank-input-2', 'water-tank-input-1')">
                                            <label for="W_Tank_IN">IN</label>
                                            <input type="hidden" id="W_Tank_IN_confirmed" name="W_Tank_IN_confirmed" value="true">
                                    </div>
                                    <div class="input_box2 col-md-12">
                                        <input type="date" class="form-control" id="W_Tank_PDC" name="W_Tank_PDC" readonly>
                                        <label for="W_Tank_PDC">PDC</label>
                                    </div>
                                    <div class="input_box2 col-md-12">
                                        <input type="datetime-local" class="form-control" id="W_Tank_OUT" name="W_Tank_OUT"
                                        onchange="confirmAllDates('W_Tank_OUT', 'Water Tank OUT', 'W_Tank_PDC');updateDifference('W_Tank_IN', 'W_Tank_OUT', 'water-tank-input-2', 'water-tank-input-1')">
                                        <label for="W_Tank_OUT">OUT</label>
                                        <input type="hidden" id="W_Tank_OUT_confirmed" name="W_Tank_OUT_confirmed" value="true">
                                            
                                    </div>
                                </div>
                            </div>



                            <!-- Carriage -->
                            <div class="card mb-3" style=" border-radius: 15px; box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);">
                                <div class="card-header" style="border-radius:10px;">
                                    <b>Carriage</b>
                                    <input type="text" id="carriage-input-1" readonly>
                                    <input type="text" id="carriage-input-2" readonly>
                                </div>
                                <div class="card-body1">
                                    <div class="input_box2 col-md-12">
                                        <input type="datetime-local" class="form-control" id="Carriage_IN" name="Carriage_IN"
                                            onchange="confirmAllDates('Carriage_IN', 'Carriage IN', 'Carriage_PDC');updateDifference('Carriage_IN', 'Carriage_OUT', 'carriage-input-2', 'carriage-input-1')">
                                            <label for="Carriage_IN">IN</label>
                                            <input type="hidden" id="Carriage_IN_confirmed" name="Carriage_IN_confirmed" value="true">
                                    </div>
                                    <div class="input_box2 col-md-12">
                                        <input type="date" class="form-control" id="Carriage_PDC" name="Carriage_PDC" readonly>
                                        <label for="Carriage_PDC">PDC</label>
                                    </div>
                                    <div class="input_box2 col-md-12">
                                        <input type="datetime-local" class="form-control" id="Carriage_OUT" name="Carriage_OUT"
                                        onchange="confirmAllDates('Carriage_OUT', 'Carriage OUT', 'Carriage_PDC');updateDifference('Carriage_IN', 'Carriage_OUT', 'carriage-input-2', 'carriage-input-1')">
                                        <label for="Carriage_OUT">OUT</label>
                                        <input type="hidden" id="Carriage_OUT_confirmed" name="Carriage_OUT_confirmed" value="true">
                                    </div>
                                </div>
                            </div>


                            <!-- ETL AC -->
                            <div class="card mb-3"style=" border-radius: 15px; box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);">
                                <div class="card-header" style="border-radius:10px;">
                                    <b>ETL AC</b>
                                    <input type="text" id="etl-ac-input-1" readonly>
                                    <input type="text" id="etl-ac-input-2" readonly>
                                </div>
                                <div class="card-body1">
                                    <div class="input_box2 col-md-12">
                                        <input type="datetime-local" class="form-control" id="ETL_AC_IN" name="ETL_AC_IN"
                                            onchange="confirmAllDates('ETL_AC_IN', 'ETL AC IN', 'ETL_AC_PDC');updateDifference('ETL_AC_IN', 'ETL_AC_OUT', 'etl-ac-input-2', 'etl-ac-input-1')">
                                            <label for="ETL_AC_IN">IN</label>
                                            <input type="hidden" id="ETL_AC_IN_confirmed" name="ETL_AC_IN_confirmed" value="true">
                                    </div>
                                    <div class="input_box2 col-md-12">
                                        <input type="date" class="form-control" id="ETL_AC_PDC" name="ETL_AC_PDC" readonly>
                                        <label for="ETL_AC_PDC">PDC</label>
                                    </div>
                                    <div class="input_box2 col-md-12">
                                        <input type="datetime-local" class="form-control" id="ETL_AC_OUT" name="ETL_AC_OUT"
                                        onchange="confirmAllDates('ETL_AC_OUT', 'ETL AC OUT', 'ETL_AC_PDC');updateDifference('ETL_AC_IN', 'ETL_AC_OUT', 'etl-ac-input-2', 'etl-ac-input-1')">
                                        <label for="ETL_AC_OUT">OUT</label>
                                        <input type="hidden" id="ETL_AC_OUT_confirmed" name="ETL_AC_OUT_confirmed" value="true">
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Air Brake -->
                            <div class="card mb-3" style=" border-radius: 15px; box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);">
                                <div class="card-header" style="border-radius:10px;">
                                    <b> Air Brake</b>
                                    <input type="text" id="air-brake-input-1" readonly>
                                    <input type="text" id="air-brake-input-2" readonly>
                                </div>
                                <div class="card-body1">
                                    <div class="input_box2 col-md-12">
                                        <input type="datetime-local" class="form-control" id="Air_Br_IN" name="Air_Br_IN"
                                            onchange="confirmAllDates('Air_Br_IN', 'Air Brake IN', 'Air_Br_PDC');updateDifference('Air_Br_IN', 'Air_Br_OUT', 'air-brake-input-2', 'air-brake-input-1')">
                                            <label for="Air_Br_IN">IN</label>
                                            <input type="hidden" id="Air_Br_IN_confirmed" name="Air_Br_IN_confirmed" value="true">
                                    </div>
                                    <div class="input_box2 col-md-12">
                                        <input type="date" class="form-control" id="Air_Br_PDC" name="Air_Br_PDC" readonly>
                                        <label for="Air_Br_PDC">PDC</label>
                                    </div>
                                    <div class="input_box2 col-md-12">
                                        <input type="datetime-local" class="form-control" id="Air_Br_OUT" name="Air_Br_OUT"
                                            onchange="confirmAllDates('Air_Br_OUT', 'Air Brake OUT', 'Air_Br_PDC');updateDifference('Air_Br_IN', 'Air_Br_OUT', 'air-brake-input-2', 'air-brake-input-1')">
                                            <label for="Air_Br_OUT">OUT</label>
                                            <input type="hidden" id="Air_Br_OUT_confirmed" name="Air_Br_OUT_confirmed" value="true">
                                    </div>
                                </div>
                            </div>


                            <!-- NTXR -->
                            <div class="card mb-3" style="border-radius: 15px; box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);">
                                <div class="card-header" style="border-radius:10px;">
                                    <b>NTXR</b>
                                    <input type="text" style="background-color: transparent; border:none;"  id="ntxr-input-2" readonly>
                                </div>
                                <div class="card-body1">
                                    <div class="input_box2 col-md-12">
                                        <input type="datetime-local" class="form-control" id="NTXR_IN" name="NTXR_IN"
                                            onchange="confirmAllDates('NTXR_IN', 'NTXR IN', '');updateDifference('NTXR_IN', 'NTXR_OUT', 'ntxr-input-2', '')">
                                            <label for="NTXR_IN">Offered</label>
                                            <input type="hidden" id="NTXR_IN_confirmed" name="NTXR_IN_confirmed" value="true">
                                    </div>
                                    <div class="input_box2 col-md-12">
                                        <input type="datetime-local" class="form-control" id="NTXR_OUT" name="NTXR_OUT"
                                        onchange="confirmAllDates('NTXR_OUT', 'NTXR OUT', '');updateDifference('NTXR_IN', 'NTXR_OUT', 'ntxr-input-2', '')">
                                        <label for="NTXR_OUT">Fit</label>
                                        <input type="hidden" id="NTXR_OUT_confirmed" name="NTXR_OUT_confirmed" value="true">
                                            
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row mt-1 mb-0">
                            <button class="saveBtn" onclick="onSubmitForm(event)">
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
                    
                    
                </form>
            </div>
        </div>
    </div>
    
<script>

document.addEventListener('DOMContentLoaded', function() {
    const stages = ['P_Insp', 'L_L', 'Corr', 'Paint', 'Berth', 'W_Tank', 'Carriage', 'ETL_AC', 'Air_Br', 'NTXR'];

    stages.forEach((stage) => {
        const inDateElem = document.getElementById(`${stage}_IN`);
        const outDateElem = document.getElementById(`${stage}_OUT`);

        // Attach event listeners for change and input events
        if (inDateElem) {
            inDateElem.addEventListener('change', checkAndHighlightDates);
            inDateElem.addEventListener('input', checkAndHighlightDates);
        }
        if (outDateElem) {
            outDateElem.addEventListener('change', checkAndHighlightDates);
            outDateElem.addEventListener('input', checkAndHighlightDates);
        }

        // Uncomment and use this section if you need to set the minimum date for the next stage based on the current stage's out date
        // const outDateElem = document.getElementById(`${stage}_OUT`);
        // if (outDateElem) {
        //     outDateElem.addEventListener('change', () => {
        //         if (index < stages.length - 1) {
        //             setMinDateForStage(index + 1);
        //         }
        //     });
        // }
    });

    // Add form submit event listener
    document.querySelector('form.mb-3').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent form submission
        let searchInput = document.querySelector('input[name="search"]').value;
        if (searchInput.trim() !== '') {
            fetchCoachDetails(searchInput);  
        }
    });

    handleInitialLoad();
    
    // Function to set the minimum date for OUT date based on IN date
    function handleInDateChange(inInput, outInput) {
        if (inInput.value) {
            const minDate = new Date(inInput.value);
            minDate.setMinutes(minDate.getMinutes() - minDate.getTimezoneOffset()); // Adjust for timezone
            outInput.setAttribute('min', minDate.toISOString().slice(0, 16));
        } else {
            outInput.removeAttribute('min');
            outInput.value = '';
        }
    }

    // Function to initialize date inputs for a card
    function initializeDateInputs(cardId, inSuffix, outSuffix) {
        const inInput = document.getElementById(cardId + inSuffix);
        const outInput = document.getElementById(cardId + outSuffix);
        
        if (inInput && outInput) {
            // Add event listener to IN date
            inInput.addEventListener('change', function() {
                handleInDateChange(inInput, outInput);
                handleDateChange(inInput.id, outInput.id);
            });

            // Add event listener to OUT date
            outInput.addEventListener('change', function() {
                handleDateChange(inInput.id, outInput.id);
            });

            // Initialize state
            handleInDateChange(inInput, outInput);
            handleDateChange(inInput.id, outInput.id); // Ensure correct initial state

        }
    }

    // Initialize all cards
    const cards = [
        { id: 'P_Insp', inSuffix: '_IN', outSuffix: '_OUT' },
        { id: 'L_L', inSuffix: '_IN', outSuffix: '_OUT' },
        { id: 'Corr', inSuffix: '_IN', outSuffix: '_OUT' },
        { id: 'Paint', inSuffix: '_IN', outSuffix: '_OUT' },
        { id: 'Berth', inSuffix: '_IN', outSuffix: '_OUT' },
        { id: 'W_Tank', inSuffix: '_IN', outSuffix: '_OUT' },
        { id: 'Carriage', inSuffix: '_IN', outSuffix: '_OUT' },
        { id: 'ETL_AC', inSuffix: '_IN', outSuffix: '_OUT' },
        { id: 'Air_Br', inSuffix: '_IN', outSuffix: '_OUT' },
        { id: 'NTXR', inSuffix: '_IN', outSuffix: '_OUT' }
    ];

    cards.forEach(card => initializeDateInputs(card.id, card.inSuffix, card.outSuffix));
});

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
    
    // Fetch additional data using AJAX
    fetchWorkshopInAndCorrMHrs(data.coachDetails.CoachID, data.coachDetails.Category);

    fetchMaintenanceID(data.coachDetails.CoachID);
}

function fetchWorkshopInAndCorrMHrs(coachID, category) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'fetch_workin_corr.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);

            // Check if there is an error in the response
            if (response.error) {
                console.log(response.error);
                return;
            }

            // Set the value of the input box with the id 'workshop_in'
            document.getElementById('workshop_in').value = response.workshop_IN || '';

            // Call validateDates after setting the value
            validateDates();

            fetchP_Insp_IN(response.workshop_IN, category, response.Corr_M_Hrs);
        }
    };
    xhr.send('coachID=' + coachID);
}

function fetchCoachDetails(coachNumber) {
    let formData = new FormData();
    formData.append('coachNumber', coachNumber);

    fetch('get_coach_poh.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // console.log('Response Data:', data); // Log the response data

        if (data.success) {
            updateFormFields(data);
            fetchData(data.coachDetails.CoachID);
        } else {
            cleardataFields();
            clearTable();
            clearPOH();
            checkAndHighlightDates();
            clearMID();
            updateDifference('P_Insp_IN', 'P_Insp_OUT', 'inspection-input-2', 'inspection-input-1');
            updateDifference('L_L_IN', 'L_L_OUT', 'lift-lower-input-2', 'lift-lower-input-1');
            updateDifference('Corr_IN', 'Corr_OUT', 'corrosion-input-2', 'corrosion-input-1');
            updateDifference('Paint_IN', 'Paint_OUT', 'paint-input-2', 'paint-input-1');
            updateDifference('Berth_IN', 'Berth_OUT', 'trimming-input-2', 'trimming-input-1');
            updateDifference('W_Tank_IN', 'W_Tank_OUT', 'water-tank-input-2', 'water-tank-input-1');
            updateDifference('Carriage_IN', 'Carriage_OUT', 'carriage-input-2', 'carriage-input-1');
            updateDifference('ETL_AC_IN', 'ETL_AC_OUT', 'etl-ac-input-2', 'etl-ac-input-1');
            updateDifference('Air_Br_IN', 'Air_Br_OUT', 'air-brake-input-2', 'air-brake-input-1');
            alert(data.message); // Alert should trigger here
        }
    })
    .catch(error => {
        console.error('Error fetching coach details:', error);
        alert('An error occurred while fetching coach details.');
    });
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

        tbody.appendChild(tr);
    });
}

function clearTable() {
    const tbody = document.querySelector('#dynamic-table tbody');
    tbody.innerHTML = ''; // Clear existing rows
}

function getCoachID() {
// Retrieve the coach ID from the input field with ID 'coachID'
const coachIDElement = document.getElementById('coachID');
return coachIDElement ? coachIDElement.value : '';
}

function fetchMaintenanceID() {
    const coachID = getCoachID();
    // console.log('Coach ID:', coachID); // Log the coach ID for debugging

    if (!coachID) {
        console.error('Coach ID is empty');
        return;
    }

    const url = 'fetchmaintenanceid.php?coachID=' + coachID;

    fetch(url)
        .then(response => {
            
            return response.json();
        })
        .then(data => {
            if (data.maintenanceID) {
                // console.log('Maintenance ID:', data.maintenanceID); // Log maintenance ID
                document.getElementById('MID').value = data.maintenanceID;
                fetchPOHData(data.maintenanceID);
                checkAndHighlightDates();
                updateDifference('P_Insp_IN', 'P_Insp_OUT', 'inspection-input-2', 'inspection-input-1');
                updateDifference('L_L_IN', 'L_L_OUT', 'lift-lower-input-2', 'lift-lower-input-1');
                updateDifference('Corr_IN', 'Corr_OUT', 'corrosion-input-2', 'corrosion-input-1');
                updateDifference('Paint_IN', 'Paint_OUT', 'paint-input-2', 'paint-input-1');
                updateDifference('Berth_IN', 'Berth_OUT', 'trimming-input-2', 'trimming-input-1');
                updateDifference('W_Tank_IN', 'W_Tank_OUT', 'water-tank-input-2', 'water-tank-input-1');
                updateDifference('Carriage_IN', 'Carriage_OUT', 'carriage-input-2', 'carriage-input-1');
                updateDifference('ETL_AC_IN', 'ETL_AC_OUT', 'etl-ac-input-2', 'etl-ac-input-1');
                updateDifference('Air_Br_IN', 'Air_Br_OUT', 'air-brake-input-2', 'air-brake-input-1');
            } else {
                console.log('No maintenance ID found for coachID:', coachID);
                clearMID();
                clearPOH();
                checkAndHighlightDates();
                updateDifference('P_Insp_IN', 'P_Insp_OUT', 'inspection-input-2', 'inspection-input-1');
                updateDifference('L_L_IN', 'L_L_OUT', 'lift-lower-input-2', 'lift-lower-input-1');
                updateDifference('Corr_IN', 'Corr_OUT', 'corrosion-input-2', 'corrosion-input-1');
                updateDifference('Paint_IN', 'Paint_OUT', 'paint-input-2', 'paint-input-1');
                updateDifference('Berth_IN', 'Berth_OUT', 'trimming-input-2', 'trimming-input-1');
                updateDifference('W_Tank_IN', 'W_Tank_OUT', 'water-tank-input-2', 'water-tank-input-1');
                updateDifference('Carriage_IN', 'Carriage_OUT', 'carriage-input-2', 'carriage-input-1');
                updateDifference('ETL_AC_IN', 'ETL_AC_OUT', 'etl-ac-input-2', 'etl-ac-input-1');
                updateDifference('Air_Br_IN', 'Air_Br_OUT', 'air-brake-input-2', 'air-brake-input-1');
            }
        })
        .catch(error => {
            console.error('Error fetching maintenance ID:', error);
        });
}

function clearMID() {
    const midInput = document.getElementById('MID');
    if (midInput) {
        midInput.value = '';
    } else {
        console.error('Element with ID "MID" not found.');
    }

    const workshopInDate = document.getElementById('workshop_in');
    if (workshopInDate) {
        workshopInDate.value = '';
    } else {
        console.error('Element with ID "workshop_in" not found.');
    }
}


function fetchPOHData(MID) {
    let url = MID ? 'fetch_poh.php?MID=' + MID : 'fetch_poh.php';

    let ids = [
        'P_Insp_IN', 'P_Insp_OUT', 'L_L_IN', 'L_L_OUT', 'Corr_IN', 'Corr_OUT',
        'Paint_IN', 'Paint_OUT', 'Berth_IN', 'Berth_OUT', 'W_Tank_IN', 'W_Tank_OUT',
        'Carriage_IN', 'Carriage_OUT', 'ETL_AC_IN', 'ETL_AC_OUT', 'Air_Br_IN', 'Air_Br_OUT',
        'NTXR_IN', 'NTXR_OUT'
    ];

    function clearFields() {
        ids.forEach(id => {
            let element = document.getElementById(id);
            if (element) {
                element.value = '';
            } else {
                console.warn('Element with ID ' + id + ' not found');
            }
        });
    }

    function formatDateToDateTimeLocal(date) {
        if (!date || isNaN(date.getTime())) return '';
        const pad = num => String(num).padStart(2, '0');
        return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
    }

    // Clear fields before fetching new data
    clearFields();

    //Trigger handleDateChange before fetching data
    ids.forEach((id, index) => {
        if (index % 2 === 0) {
            handleDateChange(id, ids[index + 1]);
        }
    });

    fetch(url)
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error('Error: ' + response.status + ' ' + response.statusText + '\n' + text);
                });
            }
            return response.json();
        })
        .then(data => {
            // console.log('Fetched POH data:', data);  // Log the fetched data

            if (data.success) {
                ids.forEach(id => {
                    let element = document.getElementById(id);
                    if (element) {
                        let dateValue = data.POHDetails[id] ? new Date(data.POHDetails[id]) : null;
                        element.value = formatDateToDateTimeLocal(dateValue);
                    } else {
                        console.warn('Element with ID ' + id + ' not found');
                    }
                });

                // Trigger handleDateChange after updating the fields
                ids.forEach((id, index) => {
                    if (index % 2 === 0) {
                        handleDateChange(id, ids[index + 1]);
                    }
                });

                // Call validateDates after updating the fields
                validateDates();
                
            } else {
                console.error('Error: Data fetching unsuccessful');
            }
        })
        .catch(error => {
            // console.error('Error fetching POH data:', error);
            console.log('No data to fetch');
        });
}


function clearPOH() {
    const form = document.getElementById('myForm');
    if (form) {
        // Clear text inputs, textareas, and datetime-local fields
        const inputs = form.querySelectorAll('input[type="text"], textarea, input[type="datetime-local"],input[type="date"]');
        inputs.forEach(input => input.value = ''); // Set each input to an empty string
    } else {
        console.warn('Form with id "myForm" not found.');
    }
}



// Function to confirm dates
async function confirmAllDates(fieldId, dateType, pdcFieldId) {
    var dateField = document.getElementById(fieldId);
    var confirmField = document.getElementById(fieldId + '_confirmed');
    var pdcField = document.getElementById(pdcFieldId);
    var stages = [
        'P_Insp', 'L_L', 'Corr', 'Paint', 'Berth', 'W_Tank', 'Carriage', 'ETL_AC', 'Air_Br'
    ];

    if (dateField.value && pdcField.value) {
        var confirmation = confirm("You entered " + dateType + ": " + dateField.value + ". Do you confirm?");
        if (confirmation) {
            confirmField.value = 'true';

            var selectedDate = new Date(dateField.value);
            var pdcDate = new Date(pdcField.value);
            
            // Compare only the dates, not times
            var selectedDateOnly = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), selectedDate.getDate());
            var pdcDateOnly = new Date(pdcDate.getFullYear(), pdcDate.getMonth(), pdcDate.getDate());

            if (selectedDateOnly > pdcDateOnly) {
                var stage = stages.find(stage => fieldId.startsWith(stage));
                if (!stage) {
                    console.error(`No stage found for fieldId: ${fieldId}`);
                    return;
                }

                var remark = await askForRemark(dateType);
                
                if (remark) {
                    await storeRemark(stage, dateType, remark);  // Call storeRemark here to save to the database
                } else {
                    alert("A remark must be provided to proceed.");
                    dateField.value = '';
                    dateField.style.backgroundColor = "";
                    confirmField.value = 'false';
                }
            }
        } else {
            dateField.value = '';
            dateField.style.backgroundColor = "";
            confirmField.value = 'false';
        }
    }
}


function askForRemark(dateType) {
    return new Promise((resolve) => {
        function promptForRemark() {
            let remark = prompt(`The ${dateType} date is after the PDC date. Please provide a remark:`);

            if (remark === null) {
                if (confirm("You must provide a remark. Do you want to try again?")) {
                    promptForRemark();
                } else {
                    resolve(null);
                }
            } else if (remark.trim() === "") {
                alert("Please enter a valid remark.");
                promptForRemark();
            } else {
                resolve(remark.trim());
            }
        }
        promptForRemark();
    });
}

async function storeRemark(stage, dateType, remark) {
    console.log(`Attempting to store remark for ${stage} ${dateType}: ${remark}`);
    const formData = new FormData();
    formData.append('coachNo', document.getElementById('coachNo')?.value || '');
    formData.append('category', document.getElementById('category')?.value || '');
    formData.append('code', document.getElementById('code')?.value || '');
    formData.append('stage', stage);
    formData.append('dateType', dateType);
    formData.append('remark', remark);

    try {
        const response = await fetch('store_remark.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        if (result.success) {
            console.log(`Remark stored successfully for ${stage} ${dateType}`);
        } else {
            console.error(`Failed to store remark: ${result.message}`);
        }
    } catch (error) {
        console.error('Error storing remark:', error);
    }
}

// Handle initial load for fields
function handleInitialLoad() {
    const cards = [
        { inId: 'P_Insp_IN', outId: 'P_Insp_OUT' },
        { inId: 'L_L_IN', outId: 'L_L_OUT' },
        { inId: 'Corr_IN', outId: 'Corr_OUT' },
        { inId: 'Paint_IN', outId: 'Paint_OUT' },
        { inId: 'Berth_IN', outId: 'Berth_OUT' },
        { inId: 'W_Tank_IN', outId: 'W_Tank_OUT' },
        { inId: 'Carriage_IN', outId: 'Carriage_OUT' },
        { inId: 'ETL_AC_IN', outId: 'ETL_AC_OUT' },
        { inId: 'Air_Br_IN', outId: 'Air_Br_OUT' },
        { inId: 'NTXR_IN', outId: 'NTXR_OUT' }
    ];

    cards.forEach(card => {
        const inField = document.getElementById(card.inId);
        const outField = document.getElementById(card.outId);
        
        if (inField && outField) {
            // console.log(`Initial Load - IN Value: ${inField.value}, OUT Value: ${outField.value}`);

            if (!inField.value && !outField.value) {
                inField.readOnly = false;
                outField.readOnly = true;
            } else if (inField.value && !outField.value) {
                inField.readOnly = true;
                outField.readOnly = false;
            } else if (inField.value && outField.value) {
                inField.readOnly = true;
                outField.readOnly = true;
            }
        }
    });

}

// Handle date change for a pair of fields
function handleDateChange(inFieldId, outFieldId) {
    const inField = document.getElementById(inFieldId);
    const outField = document.getElementById(outFieldId);

    if (inField && outField) {
        // console.log(`IN Value: ${inField.value}, OUT Value: ${outField.value}`);

        if (inField.value && !outField.value) {
            inField.readOnly = true;
            outField.readOnly = false;
        } else if (inField.value && outField.value) {
            inField.readOnly = true;
            outField.readOnly = true;
        } else if (!inField.value && !outField.value){
            inField.readOnly = false;
            outField.readOnly = true;
        } else {
            inField.readOnly = false;
            outField.readOnly = true;
        }
    }
}

function validateDates() {
    const workshopInInput = document.getElementById('workshop_in');
    const workshopInDate = workshopInInput ? new Date(workshopInInput.value) : null;

    if (workshopInDate && !isNaN(workshopInDate.getTime())) {
        workshopInDate.setMinutes(workshopInDate.getMinutes() - workshopInDate.getTimezoneOffset()); // Adjust for timezone
        // console.log('Workshop IN date:', workshopInDate.toISOString());
    }

    const cards = [
        { inId: 'P_Insp_IN', outId: 'P_Insp_OUT' },
        { inId: 'L_L_IN', outId: 'L_L_OUT' },
        { inId: 'Corr_IN', outId: 'Corr_OUT' },
        { inId: 'Paint_IN', outId: 'Paint_OUT' },
        { inId: 'Berth_IN', outId: 'Berth_OUT' },
        { inId: 'W_Tank_IN', outId: 'W_Tank_OUT' },
        { inId: 'Carriage_IN', outId: 'Carriage_OUT' },
        { inId: 'ETL_AC_IN', outId: 'ETL_AC_OUT' },
        { inId: 'Air_Br_IN', outId: 'Air_Br_OUT' },
        { inId: 'NTXR_IN', outId: 'NTXR_OUT' }
    ];

    cards.forEach(card => {
        const inInput = document.getElementById(card.inId);
        const outInput = document.getElementById(card.outId);

        if (inInput) {
            if (workshopInDate) {
                inInput.setAttribute('min', workshopInDate.toISOString().slice(0, 16));
            }

            if (outInput && inInput.value) {
                const inDate = new Date(inInput.value);
                if (!isNaN(inDate.getTime())) {
                    inDate.setMinutes(inDate.getMinutes() - inDate.getTimezoneOffset()); // Adjust for timezone
                    outInput.setAttribute('min', inDate.toISOString().slice(0, 16));
                }
            } else if (outInput) {
                outInput.removeAttribute('min');
            }

            if (outInput && outInput.value) {
                const outDate = new Date(outInput.value);
                const inDate = new Date(inInput.value);
                if (!isNaN(outDate.getTime()) && !isNaN(inDate.getTime()) && outDate < inDate) {
                    outInput.value = '';
                }
            }
        }
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


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>