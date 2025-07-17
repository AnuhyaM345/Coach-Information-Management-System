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
    <title>RS Certificate</title>
    <link rel="icon" href="../../SCR_Logo.png" type="image/png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<style>
       .container {
            margin: 20px auto;
            max-width: 1200px;
        }
        .form-row {
            padding:3px;
            margin-bottom: 10px;
            flex-wrap: nowrap;
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
            overflow-x: hidden;
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

        .searchcard {
            height: 50px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            padding-top: 24px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
            margin-top: 70px;
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
            height: 150px;
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


        .col-md-6 {
            padding-right: 5px; /* Adjust padding as needed */
            padding-left: 5px;  /* Adjust padding as needed */
        }
        .card-1 {
            border: 1px solid #ced4da;
            margin-left:10px;
            margin-bottom:5px;
            height:auto;
            border-radius:10px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4);
            border: 1px solid #ccc;
            padding: 10px;
            width: auto;

        }
        
        /* Card header styles */
        .card-header1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            padding: 10px;
            background-color: rgba(0, 0, 139, 1);
            color: white; /* White text */
            border-radius: 6px 6px 0 0; /* Rounded corners for the top of the card */
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);

        }

        .card-2 {
            border: 1px solid #ced4da;
            margin-right:10px;
            margin-bottom:5px;
            height:auto;
            border-radius:10px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4);
            border: 1px solid #ccc;
            padding: 10px;
            width: auto;
        }

        .card-body1,
        .card-body2{
            padding: 13px;
            
        }
        
        .input_box1 {
            position: relative;
            font-weight: bold;
        }

        .input_box1 select,
        .input_box1 input[type="text"],
        .input_box1 input[type="number"]{
            width: 100%;
            padding: 7px;
            border: 1.5px solid #808080;
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
            color: #00008B;
            font-size: 16px;
            transition: all 0.3s;
            pointer-events: none;
        }

        .input_box1 input[type="text"]:focus + label,
        .input_box1 input[type="text"]:not(:placeholder-shown) + label,
        .input_box1 input[type="number"]:focus + label,
        .input_box1 input[type="number"]:not(:placeholder-shown) + label,
        .input_box1 select:focus + label,
        .input_box1 select:not(:placeholder-shown) + label {
            top: -10px;
            left: 10px;
            font-size: 12px;
            color: #00008B;
        }
        
        .form-control[readonly] {
            color: black;
            background-color: transparent;
            cursor: not-allowed;
        }

        .form-control[readonly]:focus {
            background-color: transparent; /* Light gray background remains the same on focus */
            box-shadow: none; /* No box shadow */
        }
        .submitcard{
            border: 1px solid #ced4da; /* Gray border */
            height:60px;
            border-radius:10px;
            margin-bottom:5px;
            background-color: white;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4);
        }
        .traceablecard {
            height: auto;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding-top:10px;
            padding-bottom:10px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
            margin-bottom: 8px; 
        }
        .card-header2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            padding: 5px;
            background-color: rgba(0, 0, 139, 1);
            color: white; /* White text */
            border-radius: 6px 6px 0 0; /* Rounded corners for the top of the card */
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .traceabletablecard {
            height: 200px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 18px;
            padding-top: 15px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
            margin-top: 8px;
            margin-bottom: 10px;
        }
        .addbutton {
        font-family: 'Montserrat', sans-serif;
        font-size: 18px;
        background: #00008B;
        position: relative;
        height: 36px;
        width: 100px;
        cursor: pointer;
        display: flex;
        align-items: center;
        border: none;
        outline: none;
        border-radius: 25px;
        margin-left: auto;
    
        }

        .addbutton .addbutton__text {
        transform: translateX(45px);
        color: #fff;
        font-weight: 600;
        transition: all 0.3s ease-in-out;
        margin-left: -0.4em;
        }

        .addbutton .addbutton__icon {
        position: absolute;
        height: 36px;
        width: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        transform-origin: center center;
        transition: all 0.3s ease-in-out;
        margin-left: -0.1em;
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
        transform: translateX(2em) rotate(0deg) scale(1.1);
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

        .updateBtn {
            font-family: 'Montserrat', sans-serif;
            font-size: 17px;
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
            width: 110px;
            background-size: cover;
            outline: none;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.116);
            margin-left:10px;
            
        }

        .updateBtn span {
            display: block;
            margin-left: 0.2em;
            transition: all 0.3s ease-in-out;
        }

        .updateBtn svg {
            margin-left: 0em;
            display: block;
            transform-origin: center center;
            transition: transform 0.3s ease-in-out;
        }

        .updateBtn:hover .svg-wrapper {
            animation: fly-1 0.6s ease-in-out infinite alternate;
        }

        .updateBtn:hover svg {
            transform: translateX(2em) rotate(0deg) scale(1.1);
        }

        .updateBtn:hover span {
            transform: translateX(5em);
        }

        .updateBtn:active {
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
        .updateBtn:focus {
            outline: none;
        }

        .taddbutton {
        font-family: 'Montserrat', sans-serif;
        font-size: 18px;
        background: #00008B;
        position: relative;
        height: 36px;
        width: 100px;
        cursor: pointer;
        display: flex;
        align-items: center;
        border: none;
        outline: none;
        border-radius: 25px;
        margin-left: 0.5px;
        margin-top:-2px;
        transition: all 0.2s;
        }

        .taddbutton, .taddbutton_icon, .taddbutton_text {
        transition: all 0.3s;
        }

        .taddbutton .taddbutton__text {
        transform: translateX(45px);
        color: #fff;
        font-weight: 600;
        transition: all 0.3s ease-in-out;
        margin-left: -0.2em;
        }

        .taddbutton .taddbutton__icon {
        position: absolute;
        height: 36px;
        width: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        transform-origin: center center;
        transition: all 0.3s ease-in-out;
        margin-left: -0.1em;
        }

        .taddbutton .svg {
        width: 30px;
        stroke: #fff;
        }

        .taddbutton:hover {
        background: #00008b;
        }


        .taddbutton:hover .taddbutton__text {
        transform: translateX(5em);
        color: transparent;
        }

        .taddbutton:hover .taddbutton__icon {
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

        .taddbutton:active .taddbutton__icon {
        background-color: none;
        }

        .taddbutton:active {
        border: 1px solid #00008b;
        }

        .taddbutton:focus {
            outline:none;
        }
        .input_box2 {
            position: relative;
            font-weight: bold;
            
        }
        .input_box2 select,
        .input_box2 input[type="text"],
        .input_box2 input[type="date"],
        .input_box2 input[type="datetime-local"]{
            width: 100%;
            padding: 5px;
            padding-left:10px;
            padding-right:10px;
            border: 1px solid #0000ff;
            border-radius: 10px;
            font-size: 12px;
            outline: none;
            height: 32px;
            color: black;
            font-weight:600;
        }
        .input_box2 label {
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
        .input_box2 input[type="text"]:focus + label,
        .input_box2 input[type="text"]:not(:placeholder-shown) + label,
        .input_box2 input[type="date"]:focus + label,
        .input_box2 input[type="date"]:not(:placeholder-shown) + label,
        .input_box2 input[type="datetime-local"]:not(:placeholder-shown) + label,
        .input_box2 select:focus + label,
        .input_box2 select:not([value=""]):not([value="undefined"]) + label {
            top: -10px;
            left: 10px;
            font-size: 12px;
            color: #808080;
        }

        /* Target the whole HTML page scrollbars */
        html::-webkit-scrollbar {
            width: 10px; /* Vertical scrollbar width */
            height: 10px; /* Horizontal scrollbar height */
        }

        html::-webkit-scrollbar-track {
            background: transparent; /* Background of the scrollbar track */
        }

        html::-webkit-scrollbar-thumb {
            background: #888; /* Color of the scrollbar thumb */
            border-radius: 10px; /* Roundness of the scrollbar thumb */
        }

        html::-webkit-scrollbar-thumb:hover {
            background: #555; /* Color of the scrollbar thumb on hover */
        }

        html::-webkit-scrollbar-button {
            display: none; /* Hide all buttons by default */
        }

        html::-webkit-scrollbar-button:single-button:vertical:decrement,
        html::-webkit-scrollbar-button:single-button:vertical:increment,
        html::-webkit-scrollbar-button:single-button:horizontal:decrement,
        html::-webkit-scrollbar-button:single-button:horizontal:increment {
            display: block; /* Display only the arrow buttons */
            width: 20px; /* Ensure the buttons fit within the scrollbar */
            height: 20px;
            background-size: 100% 100%; /* Ensure the background image fits the button */
        }

        html::-webkit-scrollbar-button:single-button:vertical:decrement {
            background: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24"><path fill="%23555555" d="M8 14l4-4 4 4z"/></svg>') center no-repeat;
        }

        html::-webkit-scrollbar-button:single-button:vertical:increment {
            background: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24"><path fill="%23555555" d="M8 10l4 4 4-4z"/></svg>') center no-repeat;
        }

        html::-webkit-scrollbar-button:single-button:horizontal:decrement {
            background: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24"><path fill="%23555555" d="M14 8l-4 4 4 4z"/></svg>') center no-repeat;
        }

        html::-webkit-scrollbar-button:single-button:horizontal:increment {
            background: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24"><path fill="%23555555" d="M10 8l4 4-4 4z"/></svg>') center no-repeat;
        }
        .deletebutton {
        height: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-left:auto;
        margin-right:auto;
        }

        .bin-button {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 10px;
        background-color: red;
        cursor: pointer;
        border: none;
        transition-duration: 0.3s;
        margin-left:auto;
        margin-right:auto;
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

        .traceable-heading {
            font-family: 'Montserrat', sans-serif;
            margin-bottom:20px;
            font-size:20px;
            font-weight:bold;
        }
        .bogie-heading {
            font-family: 'Montserrat', sans-serif;
            font-size:17px;
            font-weight:bold;
        }
        
        .print-button {
            position: relative;
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
            margin-left: auto;
            margin-right: auto;
            margin-top: 14px;
            margin-bottom: 14px;
            border: none;
            outline: none;
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
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div class="d-flex align-items-center">
            <button class="openbtn" onclick="openNav()">☰</button>
                <img src="../../SCR_Logo.jpg" class="image mr-2" alt="Logo">
                <p class="navbar-brand mb-0">CIMS - RS Certificate</p>
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
    <form class="mb-3 d-flex align-items-center" id="searchForm" method="get" action="RS_pdf_gen.php">
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
    


<!-- Coach Master Details -->
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

<!-- Transactional data table -->
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

<!-- Under Gear Details -->

<form id="rsform" method="post">
<div class="container-fluid">
    
        <div class="card submitcard">
            <div class="container-fluid">
                <div class="form-row" style="margin:10px;">
                    <div class="input_box1 col-md-1-5">
                        <input type="text" name="MID" id="MID" size="10" maxlength="10" class="form-control" readonly>
                        <label for="MID">Maintenance ID</label>
                    </div>
                    
                    <button type="click" class="addbutton" id="addbutton">
                        <span class="addbutton__text">Add</span>
                        <span class="addbutton__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 24 24" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" stroke="currentColor" height="24" fill="none" class="svg"><line y2="19" y1="5" x2="12" x1="12"></line><line y2="12" y1="12" x2="19" x1="5"></line></svg></span>
                    </button>
                    
                    <button type="click" class="updateBtn" id="updateBtn">
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
                        <span>Update</span>
                    </button>
                    
                </div>
            </div>
        </div>
    
</div>

<div class="container-fluid mt-2">
    
        <div class="row">
            <div class="col-md-6">
                <div class="card card-1">
                    <div class="card-header1">PP/PEASD End</div>
                    <div class="card-body1">
                        <div class="form-row"> 
                            <div class="input_box1 col-md-3 offset-md-4 text-center">
                                <input type="text" name="B1No" id="B1No" size="30" maxlength="30" class="form-control">
                                <label for="B1No">Bogie</label>
                            </div>
                        </div>
                        <br>
                        <div class="form-row">
                            <p class="bogie-heading ml-2">Axle 1</p>
                        </div>
                        <div class="form-row">
                            <div class="input_box1 col-md-3">
                                <input type="text" name="B1A1RB1" id="B1A1RB1" size="30" maxlength="30" class="form-control">
                                <label for="B1A1RB1">RB1 No</label>
                            </div>
                            <div class="input_box1 col-md-3">
                                <input type="text" name="B1A1D1" id="B1A1D1" size="30" maxlength="30" class="form-control">
                                <label for="B1A1D1">Disc1 No</label>
                            </div>
                            <div class="input_box1 col-md-3">
                                <input type="text" name="B1A1D2" id="B1A1D2" size="30" maxlength="30" class="form-control">
                                <label for="B1A1D2">Disc2 No</label>
                            </div>
                            <div class="input_box1 col-md-3">
                                <input type="text" name="B1A1RB2" id="B1A1RB2" size="30" maxlength="30" class="form-control">
                                <label for="B1A1RB2">RB2 No</label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="input_box1 col-md-2">
                                <select name="B1A1D1RBType" id="B1A1D1RBType" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT BearingType FROM dbo.tbl_CMS_BearingTypeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['BearingType'].'">' . $row['BearingType'] .'</option>';
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
                                <label for="B1A1D1RBType">RB1 Type</label>
                            </div>
                            <div class="input_box1 col-md-2">
                                <input type="number" name="B1A1D1D" id="B1A1D1D" size="30" maxlength="30" class="form-control">
                                <label for="B1A1D1D">Disc1 Dia</label>
                            </div>
                            <div class="input_box1 col-md-4">
                                <input type="text" name="B1A1" id="B1A1" size="30" maxlength="30" class="form-control">
                                <label for="B1A1">Axle1 No</label>
                            </div>
                            <div class="input_box1 col-md-2">
                                <input type="number" name="B1A1D2D" id="B1A1D2D" size="30" maxlength="30" class="form-control">
                                <label for="B1A1D2D">Disc2 Dia</label>
                            </div>
                            <div class="input_box1 col-md-2">
                                <select name="B1A1D2RBType" id="B1A1D2RBType" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT BearingType FROM dbo.tbl_CMS_BearingTypeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['BearingType'].'">' . $row['BearingType'] .'</option>';
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
                                <label for="B1A1D2RBType">RB2 Type</label>
                            </div>
                        </div>
                        <div class="form-row" style="margin-bottom:0px;">
                            <div class="input_box1 col-md-2">
                                <select name="B1A1D1RBMake" id="B1A1D1RBMake" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT BearingMake FROM dbo.tbl_CMS_BearingMakeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['BearingMake'].'">' . $row['BearingMake'] .'</option>';
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
                                <label for="B1A1D1RBMake">RB1 Make</label>
                            </div>
                            <div class="input_box1 col-md-2">
                                <select name="B1A1D1M" id="B1A1D1M" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT DiscMake FROM dbo.tbl_CMS_DiscMakeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['DiscMake'].'">' . $row['DiscMake'] .'</option>';
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
                                <label for="B1A1D1M">Disc1 Make</label>
                            </div>
                            <div class="input_box1 col-md-2 offset-md-4">
                                <select name="B1A1D2M" id="B1A1D2M" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT DiscMake FROM dbo.tbl_CMS_DiscMakeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['DiscMake'].'">' . $row['DiscMake'] .'</option>';
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
                                <label for="B1A1D2M">Disc2 Make</label>
                            </div>
                            <div class="input_box1 col-md-2">
                                <select name="B1A1D2RBMake" id="B1A1D2RBMake" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT BearingMake FROM dbo.tbl_CMS_BearingMakeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['BearingMake'].'">' . $row['BearingMake'] .'</option>';
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
                                <label for="B1A1D2RBMake">RB2 Make</label>
                            </div>
                        </div>
                        <br>
<!-- ------------------------------------------------------------------------------------------------------------------------ -->
                        <div class="form-row">
                            <p class="bogie-heading ml-2">Axle 2</p>
                        </div>
                        <div class="form-row">
                            <div class="input_box1 col-md-3">
                                <input type="text" name="B1A2RB1" id="B1A2RB1" size="30" maxlength="30" class="form-control">
                                <label for="B1A2RB1">RB1 No</label>
                            </div>
                            <div class="input_box1 col-md-3">
                                <input type="text" name="B1A2D1" id="B1A2D1" size="30" maxlength="30" class="form-control">
                                <label for="B1A2D1">Disc1 No</label>
                            </div>
                            <div class="input_box1 col-md-3">
                                <input type="text" name="B1A2D2" id="B1A2D2" size="30" maxlength="30" class="form-control">
                                <label for="B1A2D2">Disc2 No</label>
                            </div>
                            <div class="input_box1 col-md-3">
                                <input type="text" name="B1A2RB2" id="B1A2RB2" size="30" maxlength="30" class="form-control">
                                <label for="B1A2RB2">RB2 No</label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="input_box1 col-md-2">
                                <select name="B1A2D1RBType" id="B1A2D1RBType" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT BearingType FROM dbo.tbl_CMS_BearingTypeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['BearingType'].'">' . $row['BearingType'] .'</option>';
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
                                <label for="B1A2D1RBType">RB1 Type</label>
                            </div>
                            <div class="input_box1 col-md-2">
                                <input type="number" name="B1A2D1D" id="B1A2D1D" size="30" maxlength="30" class="form-control">
                                <label for="B1A2D1D">Disc1 Dia</label>
                            </div>
                            <div class="input_box1 col-md-4">
                                <input type="text" name="B1A2" id="B1A2" size="30" maxlength="30" class="form-control">
                                <label for="B1A2">Axle2 No</label>
                            </div>
                            <div class="input_box1 col-md-2">
                                <input type="number" name="B1A2D2D" id="B1A2D2D" size="30" maxlength="30" class="form-control">
                                <label for="B1A2D2D">Disc2 Dia</label>
                            </div>
                            <div class="input_box1 col-md-2">
                                <select name="B1A2D2RBType" id="B1A2D2RBType" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT BearingType FROM dbo.tbl_CMS_BearingTypeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['BearingType'].'">' . $row['BearingType'] .'</option>';
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
                                <label for="B1A2D2RBType">RB2 Type</label>
                            </div>
                        </div>
                        <div class="form-row" style="margin-bottom:0px;">
                            <div class="input_box1 col-md-2">
                                <select name="B1A2D1RBMake" id="B1A2D1RBMake" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT BearingMake FROM dbo.tbl_CMS_BearingMakeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['BearingMake'].'">' . $row['BearingMake'] .'</option>';
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
                                <label for="B1A2D1RBMake">RB1 Make</label>
                            </div>
                            <div class="input_box1 col-md-2">
                                <select name="B1A2D1M" id="B1A2D1M" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT DiscMake FROM dbo.tbl_CMS_DiscMakeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['DiscMake'].'">' . $row['DiscMake'] .'</option>';
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
                                <label for="B1A2D1M">Disc1 Make</label>
                            </div>
                            <div class="input_box1 col-md-2 offset-md-4">
                                <select name="B1A2D2M" id="B1A2D2M" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT DiscMake FROM dbo.tbl_CMS_DiscMakeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['DiscMake'].'">' . $row['DiscMake'] .'</option>';
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
                                <label for="B1A2D2M">Disc2 Make</label>
                            </div>
                            <div class="input_box1 col-md-2">
                                <select name="B1A2D2RBMake" id="B1A2D2RBMake" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT BearingMake FROM dbo.tbl_CMS_BearingMakeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['BearingMake'].'">' . $row['BearingMake'] .'</option>';
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
                                <label for="B1A2D2RBMake">RB2 Make</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- -------------------------------- -->
            <div class="col-md-6">
                <div class="card card-2">
                    <div class="card-header1">NPP/NPEASD End</div>
                    <div class="card-body2">
                        <div class="form-row">
                            <div class="input_box1 col-md-3 offset-md-4 text-center">
                                <input type="text" name="B2No" id="B2No" size="30" maxlength="30" class="form-control">
                                <label for="B2No">Bogie</label>
                            </div>
                        </div>
                        <br>
                        <div class="form-row">
                            <p class="bogie-heading ml-2">Axle 1</p>
                        </div>
                        <div class="form-row">
                            <div class="input_box1 col-md-3">
                                <input type="text" name="B2A1RB1" id="B2A1RB1" size="30" maxlength="30" class="form-control">
                                <label for="B2A1RB1">RB1 No</label>
                            </div>
                            <div class="input_box1 col-md-3">
                                <input type="text" name="B2A1D1" id="B2A1D1" size="30" maxlength="30" class="form-control">
                                <label for="B2A1D1">Disc1 No</label>
                            </div>
                            <div class="input_box1 col-md-3">
                                <input type="text" name="B2A1D2" id="B2A1D2" size="30" maxlength="30" class="form-control">
                                <label for="B2A1D2">Disc2 No</label>
                            </div>
                            <div class="input_box1 col-md-3">
                                <input type="text" name="B2A1RB2" id="B2A1RB2" size="30" maxlength="30" class="form-control">
                                <label for="B2A1RB2">RB2 No</label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="input_box1 col-md-2">
                                <select name="B2A1D1RBType" id="B2A1D1RBType" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT BearingType FROM dbo.tbl_CMS_BearingTypeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['BearingType'].'">' . $row['BearingType'] .'</option>';
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
                                <label for="B2A1D1RBType">RB1 Type</label>
                            </div>
                            <div class="input_box1 col-md-2">
                                <input type="number" name="B2A1D1D" id="B2A1D1D" size="30" maxlength="30" class="form-control">
                                <label for="B2A1D1D">Disc1 Dia</label>
                            </div>
                            <div class="input_box1 col-md-4">
                                <input type="text" name="B2A1" id="B2A1" size="30" maxlength="30" class="form-control">
                                <label for="B2A1">Axle1 No</label>
                            </div>
                            <div class="input_box1 col-md-2">
                                <input type="number" name="B2A1D2D" id="B2A1D2D" size="30" maxlength="30" class="form-control">
                                <label for="B2A1D2D">Disc2 Dia</label>
                            </div>
                            <div class="input_box1 col-md-2">
                                <select name="B2A1D2RBType" id="B2A1D2RBType" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT BearingType FROM dbo.tbl_CMS_BearingTypeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['BearingType'].'">' . $row['BearingType'] .'</option>';
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
                                <label for="B2A1D2RBType">RB2 Type</label>
                            </div>
                        </div>
                        <div class="form-row" style="margin-bottom:0px;">
                            <div class="input_box1 col-md-2">
                                <select name="B2A1D1RBMake" id="B2A1D1RBMake" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT BearingMake FROM dbo.tbl_CMS_BearingMakeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['BearingMake'].'">' . $row['BearingMake'] .'</option>';
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
                                <label for="B2A1D1RBMake">RB1 Make</label>
                            </div>
                            <div class="input_box1 col-md-2">
                                <select name="B2A1D1M" id="B2A1D1M" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT DiscMake FROM dbo.tbl_CMS_DiscMakeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['DiscMake'].'">' . $row['DiscMake'] .'</option>';
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
                                <label for="B2A1D1M">Disc1 Make</label>
                            </div>
                            <div class="input_box1 col-md-2 offset-md-4">
                                <select name="B2A1D2M" id="B2A1D2M" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT DiscMake FROM dbo.tbl_CMS_DiscMakeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['DiscMake'].'">' . $row['DiscMake'] .'</option>';
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
                                <label for="B2A1D2M">Disc2 Make</label>
                            </div>
                            <div class="input_box1 col-md-2">
                                <select name="B2A1D2RBMake" id="B2A1D2RBMake" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT BearingMake FROM dbo.tbl_CMS_BearingMakeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['BearingMake'].'">' . $row['BearingMake'] .'</option>';
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
                                <label for="B2A1D2RBMake">RB2 Make</label>
                            </div>
                        </div>
                        <br>
<!-- ------------------------------------------------------------------------------------------------------------------------ -->
                        <div class="form-row">
                            <p class="bogie-heading ml-2">Axle 2</p>
                        </div>
                        <div class="form-row">
                            <div class="input_box1 col-md-3">
                                <input type="text" name="B2A2RB1" id="B2A2RB1" size="30" maxlength="30" class="form-control">
                                <label for="B2A2RB1">RB1 No</label>
                            </div>
                            <div class="input_box1 col-md-3">
                                <input type="text" name="B2A2D1" id="B2A2D1" size="30" maxlength="30" class="form-control">
                                <label for="B2A2D1">Disc1 No</label>
                            </div>
                            <div class="input_box1 col-md-3">
                                <input type="text" name="B2A2D2" id="B2A2D2" size="30" maxlength="30" class="form-control">
                                <label for="B2A2D2">Disc2 No</label>
                            </div>
                            <div class="input_box1 col-md-3">
                                <input type="text" name="B2A2RB2" id="B2A2RB2" size="30" maxlength="30" class="form-control">
                                <label for="B2A2RB2">RB2 No</label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="input_box1 col-md-2">
                                <select name="B2A2D1RBType" id="B2A2D1RBType" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT BearingType FROM dbo.tbl_CMS_BearingTypeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['BearingType'].'">' . $row['BearingType'] .'</option>';
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
                                <label for="B2A2D1RBType">RB1 Type</label>
                            </div>
                            <div class="input_box1 col-md-2">
                                <input type="number" name="B2A2D1D" id="B2A2D1D" size="30" maxlength="30" class="form-control">
                                <label for="B2A2D1D">Disc1 Dia</label>
                            </div>
                            <div class="input_box1 col-md-4">
                                <input type="text" name="B2A2" id="B2A2" size="30" maxlength="30" class="form-control">
                                <label for="B2A2">Axle2 No</label>
                            </div>
                            <div class="input_box1 col-md-2">
                                <input type="number" name="B2A2D2D" id="B2A2D2D" size="30" maxlength="30" class="form-control">
                                <label for="B2A2D2D">Disc2 Dia</label>
                            </div>
                            <div class="input_box1 col-md-2">
                                <select name="B2A2D2RBType" id="B2A2D2RBType" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT BearingType FROM dbo.tbl_CMS_BearingTypeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['BearingType'].'">' . $row['BearingType'] .'</option>';
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
                                <label for="B2A2D2RBType">RB2 Type</label>
                            </div>
                        </div>
                        <div class="form-row" style="margin-bottom:0px;">
                            <div class="input_box1 col-md-2">
                                <select name="B2A2D1RBMake" id="B2A2D1RBMake" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT BearingMake FROM dbo.tbl_CMS_BearingMakeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['BearingMake'].'">' . $row['BearingMake'] .'</option>';
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
                                <label for="B2A2D1RBMake">RB1 Make</label>
                            </div>
                            <div class="input_box1 col-md-2">
                                <select name="B2A2D1M" id="B2A2D1M" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT DiscMake FROM dbo.tbl_CMS_DiscMakeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['DiscMake'].'">' . $row['DiscMake'] .'</option>';
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
                                <label for="B2A2D1M">Disc1 Make</label>
                            </div>
                            <div class="input_box1 col-md-2 offset-md-4">
                                <select name="B2A2D2M" id="B2A2D2M" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT DiscMake FROM dbo.tbl_CMS_DiscMakeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['DiscMake'].'">' . $row['DiscMake'] .'</option>';
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
                                <label for="B2A2D2M">Disc2 Make</label>
                            </div>
                            <div class="input_box1 col-md-2">
                                <select name="B2A2D2RBMake" id="B2A2D2RBMake" class="form-control">
                                    <option value="" disabled selected hidden></option>
                                    <!-- php code -->
                                    <?php
                                    include '../../db.php';
                                    // Assuming $conn is your database connection
                                    if ($conn) {
                                        // Query to retrieve data from the database
                                        $query = "SELECT BearingMake FROM dbo.tbl_CMS_BearingMakeMaster";
                                        $result = sqlsrv_query($conn, $query);

                                        if ($result !== false) {
                                            // Output data of each row
                                            echo '<option value="" selected hidden></option>';
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo '<option value="'.$row['BearingMake'].'">' . $row['BearingMake'] .'</option>';
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
                                <label for="B2A2D2RBMake">RB2 Make</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
</div>
</form>

<!-- Traceable items table -->

<div class="container-fluid mt-1">
    <form id="traceableform" method="post">
        <div class="traceablecard">
        <div class="container-fluid">
            
            <div class="card-header2">
                Traceable Items
            </div>
            <div class="form-row mt-4">
                <div class="input_box2 col-md-1">
                    <input type="text" name="MID2" id="MID2" size="10" maxlength="10" class="form-control" readonly>
                    <label for="MID2">MID</label>
                </div>
                <div class="input_box2 col-md-2">
                    <select id="description" name="description" class="form-control">
                        <option value="" disabled selected hidden></option>
                        <!-- php code -->
                        <!-- Populate this with description options -->
                        <?php
                        include '../../db.php';
                        // Assuming $conn is your database connection
                        if ($conn) {
                            // Query to retrieve data from the database
                            $query = "SELECT DISTINCT(Description) FROM dbo.[tbl_CMS_Traceableitems_Details]";
                            $result = sqlsrv_query($conn, $query);

                            if ($result !== false) {
                                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                    echo '<option value="'.$row['Description'].'">' . $row['Description'] .'</option>';
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
                    <label for="description" class="label">Description</label>
                </div>
                <div class="input_box2 col-md-1-5">
                    <select id="uom" name="uom" class="form-control">
                        <option value="" disabled selected hidden></option>
                        <!-- php code -->
                    </select>
                    <label for="uom" class="label">UOM</label>
                </div>

                <!-- Load the full version of jQuery -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                <div class="input_box2 col-md-1-5">
                    <input type="text" class="form-control" name="range" id="range" size="30" maxlength="30">
                    <label for="range" class="label">Range</label>
                </div>
                <div class="input_box2 col-md-1-5">
                    <input type="text" class="form-control" name="parameter" id="parameter" size="30" maxlength="30">
                    <label for="parameter" class="label">Parameter</label>
                </div>
            
                <div class="input_box2 col-md-1-5">
                    <select id="make" name="make" class="form-control">
                        <option value="" disabled selected hidden></option>
                    </select>
                    <label for="make" class="label">Make</label>
                </div>
                <div class="input_box2 col-md-2">
                    <input type="text" class="form-control" name="remarks" id="remarks" size="30" maxlength="30">
                    <label for="remarks" class="label">Remarks</label>
                </div>
                
                <button type="button" class="taddbutton" id="taddbutton">
                    <span class="taddbutton__text">Add</span>
                    <span class="taddbutton__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 24 24" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" stroke="currentColor" height="24" fill="none" class="svg">
                            <line y2="19" y1="5" x2="12" x1="12"></line>
                            <line y2="12" y1="12" x2="19" x1="5"></line>
                        </svg>
                    </span>
                </button>
                
                <script>
                $(document).ready(function() {
                    $('#description').change(function() {
                        var description = $(this).val();
                        if (description) {
                            $.ajax({
                                type: 'POST',
                                url: 'fetch_uom.php',
                                data: {description: description},
                                success: function(response) {
                                    $('#uom').html(response);
                                },
                                error: function() {
                                    alert('Error fetching UOM data.');
                                }
                            });
                            
                            // Fetch options for the "Make" dropdown based on description
                            $.ajax({
                                type: 'POST',
                                url: 'fetch_make.php',
                                data: {description: description},
                                success: function(response) {
                                    $('#make').html(response);
                                },
                                error: function() {
                                    alert('Error fetching Make data.');
                                }
                            });
                        }
                    });
                });
                </script>
            </div>
        </div>
        </div>
    </form>
</div>

<div class="container-fluid mt-1">
    <div class="traceabletablecard d-flex justify-content-between">
        <div class="table-container">
            <table id="traceable-table">
                <thead>
                    <tr>
                        <!-- Table headers -->
                        <th style="width:70px;text-align:center;">MID</th>
                        <th style="text-align:center;">Description</th>
                        <th style="width:80px;text-align:center;">UOM</th>
                        <th style="width:80px;text-align:center;">Range</th>
                        <th style="width:80px;text-align:center;">Parameter</th>
                        <th style="width:90px;text-align:center;">Make</th>
                        <th style="text-align:center;">Remarks</th>
                        <th style="width:60px;text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody style="text-align:center;">
                    <!-- Dynamic rows will be appended here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<button class="print-button"><span class="print-icon"></span>Print</button>
<script>
    document.querySelector('.print-button').addEventListener('click', function() {
        var coachNo = document.getElementById('coachNo').value;
        window.open('RS_pdf_gen.php?coachNo=' + encodeURIComponent(coachNo), '_blank');
    });
</script>


    
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
    //add ug data
    document.getElementById('addbutton').addEventListener('click', function(e){
            e.preventDefault(); // Prevent default form submission
            addUGdata();
        });

    document.getElementById('taddbutton').addEventListener('click', function(e){
        e.preventDefault(); // Prevent default form submission
        addTForm();
    });
    
    document.getElementById('updateBtn').addEventListener('click', function(e){
        e.preventDefault(); // Prevent default form submission
        updateUGdata();
    });

    // Fetch data and populate table on page load
    fetchData();
    fetchTraceableTable();
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
    
    // Function to format date to "YYYY-MM-DDTHH:MM"
    function formatDateToDateTimeLocal(date) {
        if (!date) return null;
        const pad = num => String(num).padStart(2, '0');
        return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate()) + 'T' + pad(date.getHours()) + ':' + pad(date.getMinutes());
        // return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
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

    fetchMaintenanceID(data.coachDetails.CoachID);

    fetchUGData(data.coachDetails.CoachID);
}

function fetchCoachDetails(coachNumber) {
    let formData = new FormData();
    formData.append('coachNumber', coachNumber);

    fetch('get_coach_rs.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateFormFields(data);
            fetchData(data.coachDetails.CoachID);

        } else {
            clearDataFields();
            clearTable();
            clearUGFields();
            clearMIDFields();
            cleartraceableTable();
            clearTFormFields();
            alert(data.message);
        }
    })
    .catch(error => console.error('Error fetching coach details:', error));
}

function clearDataFields() {
    const form = document.getElementById('dataform');
    const inputs = form.querySelectorAll('input');
    inputs.forEach(input => {
            input.value = ''
            ;
    });
}

function fetchData(coachID) {
    let url = coachID ? 'fetch_table_data_rs.php?coachID=' + coachID : 'fetch_table_data_rs.php';
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


function fetchMaintenanceID() {
const coachID = getCoachID();
console.log('Coach ID:', coachID); // Log the coach ID for debugging
if (!coachID) {
    console.error('Coach ID is empty');
    return;
}
const url = 'fetchmaintenanceid.php?coachID=' + coachID;

fetch(url)
    .then(response => response.json())
    .then(data => {
        if (data.maintenanceID) {
            document.getElementById('MID').value = data.maintenanceID;
            document.getElementById('MID2').value = data.maintenanceID;
            fetchTraceableTable(data.maintenanceID);
        } else {
            clearMIDFields();
            console.error('No maintenance ID found for coachID:', coachID);
        }
    })
    .catch(error => {
        console.error('Error fetching maintenance ID:', error);
    });
}

function getCoachID() {
// Retrieve the coach ID from the input field with ID 'coachID'
const coachIDElement = document.getElementById('coachID');
return coachIDElement ? coachIDElement.value : '';
}

function clearMIDFields() {
    document.getElementById('MID').value = '';
    document.getElementById('MID2').value = '';
}

function fetchUGData() {
const coachID = getCoachID();
console.log('Coach ID:', coachID); // Log the coach ID for debugging
if (!coachID) {
    console.error('Coach ID is empty');
    return;
}
const url = 'fetch_under_gear_rs.php?coachID=' + coachID;

fetch(url)
    .then(response => response.json())
    .then(data => {
            document.getElementById('B1No').value = data.B1No;
            document.getElementById('B1A1RB1').value = data.B1A1RB1;
            document.getElementById('B1A1D1').value = data.B1A1D1;
            document.getElementById('B1A1D2').value = data.B1A1D2;
            document.getElementById('B1A1RB2').value = data.B1A1RB2;
            document.getElementById('B1A1D1RBType').value = data.B1A1D1RBType;
            document.getElementById('B1A1D1D').value = data.B1A1D1D;
            document.getElementById('B1A1').value = data.B1A1;
            document.getElementById('B1A1D2D').value = data.B1A1D2D;
            document.getElementById('B1A1D2RBType').value = data.B1A1D2RBType;
            document.getElementById('B1A1D1RBMake').value = data.B1A1D1RBMake;
            document.getElementById('B1A1D1M').value = data.B1A1D1M;
            document.getElementById('B1A1D2M').value = data.B1A1D2M;
            document.getElementById('B1A1D2RBMake').value = data.B1A1D2RBMake;

            document.getElementById('B1A2RB1').value = data.B1A2RB1;
            document.getElementById('B1A2D1').value = data.B1A2D1;
            document.getElementById('B1A2D2').value = data.B1A2D2;
            document.getElementById('B1A2RB2').value = data.B1A2RB2;
            document.getElementById('B1A2D1RBType').value = data.B1A2D1RBType;
            document.getElementById('B1A2D1D').value = data.B1A2D1D;
            document.getElementById('B1A2').value = data.B1A2;
            document.getElementById('B1A2D2D').value = data.B1A2D2D;
            document.getElementById('B1A2D2RBType').value = data.B1A2D2RBType;
            document.getElementById('B1A2D1RBMake').value = data.B1A2D1RBMake;
            document.getElementById('B1A2D1M').value = data.B1A2D1M;
            document.getElementById('B1A2D2M').value = data.B1A2D2M;
            document.getElementById('B1A2D2RBMake').value = data.B1A2D2RBMake;

            document.getElementById('B2No').value = data.B2No;
            document.getElementById('B2A1RB1').value = data.B2A1RB1;
            document.getElementById('B2A1D1').value = data.B2A1D1;
            document.getElementById('B2A1D2').value = data.B2A1D2;
            document.getElementById('B2A1RB2').value = data.B2A1RB2;
            document.getElementById('B2A1D1RBType').value = data.B2A1D1RBType;
            document.getElementById('B2A1D1D').value = data.B2A1D1D;
            document.getElementById('B2A1').value = data.B2A1;
            document.getElementById('B2A1D2D').value = data.B2A1D2D;
            document.getElementById('B2A1D2RBType').value = data.B2A1D2RBType;
            document.getElementById('B2A1D1RBMake').value = data.B2A1D1RBMake;
            document.getElementById('B2A1D1M').value = data.B2A1D1M;
            document.getElementById('B2A1D2M').value = data.B2A1D2M;
            document.getElementById('B2A1D2RBMake').value = data.B2A1D2RBMake;

            document.getElementById('B2A2RB1').value = data.B2A2RB1;
            document.getElementById('B2A2D1').value = data.B2A2D1;
            document.getElementById('B2A2D2').value = data.B2A2D2;
            document.getElementById('B2A2RB2').value = data.B2A2RB2;
            document.getElementById('B2A2D1RBType').value = data.B2A2D1RBType;
            document.getElementById('B2A2D1D').value = data.B2A2D1D;
            document.getElementById('B2A2').value = data.B2A2;
            document.getElementById('B2A2D2D').value = data.B2A2D2D;
            document.getElementById('B2A2D2RBType').value = data.B2A2D2RBType;
            document.getElementById('B2A2D1RBMake').value = data.B2A2D1RBMake;
            document.getElementById('B2A2D1M').value = data.B2A2D1M;
            document.getElementById('B2A2D2M').value = data.B2A2D2M;
            document.getElementById('B2A2D2RBMake').value = data.B2A2D2RBMake;
    
    })
    .catch(error => {
        console.error('Error fetching data:', error);
    });
}

function addUGdata() {
    const MIDVal = document.getElementById('MID').value.trim(); // Fetch MID value and trim whitespace

    if (MIDVal === '') {
        alert('Please search for Coach No first.'); // Display an alert if MID is empty
        return; // Stop further execution
    }

    const formData = new FormData(document.getElementById('rsform'));

    fetch('add_ugdata_rs.php', {
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
        if (data.success) {
            document.getElementById('searchButton').click();
            alert(data.message); // Display success message
            // Optionally, reset form or update UI as needed
        } else {
            alert(data.message); // Display error message
            // Optionally, handle specific error scenarios
            if (data.message === 'UG details already exist.') {
                // Handle case where coach details already exist
                // For example, show a different message or prevent duplicate submissions
            }
        }
    })
    .catch(error => {
        console.error('Error adding new UG details:', error);
        alert('An error occurred while adding the new UG details.');
    });
}


function clearUGFields() {
    var fields = document.querySelectorAll('.form-control');
            fields.forEach(function(field) {
                field.value = '';
            });
}

function updateUGdata() {
    const MIDVal = document.getElementById('MID').value.trim(); // Fetch MID value and trim whitespace

    if (MIDVal === '') {
        alert('Please search for Coach No first.'); // Display an alert if MID is empty
        return; // Stop further execution
    }

    const formData = new FormData(document.getElementById('rsform'));

    fetch('update_ugdata_rs.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json(); // Parse response JSON here
    })
    .then(data => {
        console.log(data);
        if (data.success) {
            document.getElementById('searchButton').click();
            alert(data.message); // Display success message
        } else {
            alert(data.message); // Display error message
        }
    })
    .catch(error => {
        console.error('Error updating UG details:', error);
        alert('An error occurred while updating the UG details.');
    });
}



function fetchTraceableTable(MID) {
    let url = MID ? 'fetch_traceabletable_data.php?MID=' + MID : 'fetch_traceabletable_data.php';
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
                populateTraceableTable(data.traceableData);
            } else {
                cleartraceableTable();
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
}

function populateTraceableTable(data) {
    const tbody = document.querySelector('#traceable-table tbody');
    tbody.innerHTML = ''; // Clear existing rows

    data.forEach(row => {
        const tr = document.createElement('tr');

        // Iterate over each key in the row data
        Object.keys(row).forEach((key, colIndex) => {
            const cell = colIndex === 0 ? document.createElement('th') : document.createElement('td');
            cell.textContent = row[key];
            tr.appendChild(cell);
        });

        // Add delete button
        const deleteCell = document.createElement('td');
        const binButton = document.createElement('button');
        binButton.type = 'button'; // Ensure it's not a submit button
        binButton.classList.add('bin-button'); // Apply your bin button styles
        binButton.onclick = function() {
            const MID2 = tr.cells[0].textContent; // Get MID2 from first column
            const description = tr.cells[1].textContent; // Get description from second column
            
            console.log('Clicked delete button for MID2:', MID2);
            console.log('Description:', description);

            deleteRow(MID2, description); // Pass MID2 and description
        };

        // Append SVGs and other contents for your bin button
        binButton.innerHTML = `
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
        `;

        deleteCell.appendChild(binButton);
        tr.appendChild(deleteCell);

        tbody.appendChild(tr);
    });
}


// Function to add a new row to the table (example implementation)
function addTRow(TData) {
    const tableBody = document.querySelector('#traceable-table tbody');
    const newRow = document.createElement('tr');
    
    newRow.innerHTML = 
    '<td>' + TData.MID2 + '</td>' +
    '<td>' + TData.description + '</td>' +
    '<td>' + TData.uom + '</td>' +
    '<td>' + TData.range + '</td>' +
    '<td>' + TData.parameter + '</td>' +
    '<td>' + TData.make + '</td>' +
    '<td>' + TData.remarks + '</td>';
    // '<td><button class="deletebutton btn-danger" onclick="deleteRow(MID2,description)">Delete</button></td>'; // Adding a delete button

    tableBody.appendChild(newRow);

    document.getElementById('searchButton').click();
}

function addTForm() {
    const MIDInput = document.getElementById('MID2');
    const MIDValue = MIDInput.value.trim(); // Get trimmed value of MID2 input
    const descriptionInput = document.getElementById('description');
    const descriptionValue = descriptionInput.value.trim(); // Get trimmed value of description input
    
    if (MIDValue === '') {
        alert('Please search for the coach no before adding.'); // Display an alert if MID2 is empty
        return; // Exit function if MID2 is empty
    }

    // Check if the (MID2, description) pair already exists in the table
    const tableBody = document.querySelector('#traceable-table tbody');
    const rows = tableBody.querySelectorAll('tr');

    console.log("Checking existing rows for duplicates...");
    for (let row of rows) {
        const existingMID = row.cells[0].textContent.trim(); // Get MID2 from first column
        const existingDescription = row.cells[1].textContent.trim(); // Get description from second column
        console.log(`Existing MID2: ${existingMID}, Existing Description: ${existingDescription}`);

        if (existingMID === MIDValue && existingDescription === descriptionValue) {
            alert('This MID and Description pair already exists in the table.');
            return; // Exit function if the pair already exists
        }
    }

    // Confirmation dialog before adding
    const confirmed = window.confirm('Are you sure you want to add this record?');
    if (!confirmed) {
        return; // Exit function if not confirmed
    }

    const formData = new FormData(document.getElementById('traceableform'));

    fetch('add_traceable.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const TData = {
                MID2: formData.get('MID2'),
                description: formData.get('description'),
                uom: formData.get('uom'),
                range: formData.get('range'),
                parameter: formData.get('parameter'),
                make: formData.get('make'),
                remarks: formData.get('remarks')
            };
            addTRow(TData);
            clearTFormFields(); // Clear the form fields after successful addition
            alert('Record added successfully!');
        } else {
            alert('Failed to add traceable items: ' + JSON.stringify(data.error));
            console.error('Failed to add traceable items:', data.error);
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        alert('Failed to add traceable items: ' + error.message);
    });
}



function deleteRow(MID2, description) {
    if (confirm('Are you sure you want to delete this row?')) {
        fetch('delete_traceable.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ MID2: MID2, description: description }), // Pass both values
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Row deleted successfully!');
                var MID2InputValue = document.getElementById('MID2').value;
                fetchTraceableTable(MID2InputValue);
            } else {
                alert('Failed to delete row: ' + JSON.stringify(data.error));
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            alert('Failed to delete row: ' + error.message);
        });
    }
}

function cleartraceableTable() {
    const tbody = document.querySelector('#traceable-table tbody');
    tbody.innerHTML = ''; // Clear existing rows
}

function clearTFormFields() {
    const form = document.getElementById('traceableform');
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

</script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>   
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>