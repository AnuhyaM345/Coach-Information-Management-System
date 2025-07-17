<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: " . ($_SESSION['securitylevel'] == 'admin' ? 'ADMIN' : 'USER') . "/Dashboard/Dashboard.php");
    exit();
}
require('db.php');
$user_name = "";
$errors = array(); 
$error_message = ""; 

if (isset($_POST['login_user'])) {
  $user_name = $_POST['user_name'];
  $password = $_POST['pwd'];

  $query = "SELECT * FROM [CoachMaster_V_2018].[dbo].[tbl_CMS_UserAccount] WHERE username=? AND [password]=?";
  $params = array($user_name, $password);
  $stmt = sqlsrv_query($conn, $query, $params);

  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $_SESSION['username'] = $row['username'];
    $_SESSION['securitylevel'] = $row['securitylevel'];
    $_SESSION['Shop'] = $row['Shop'];  // Make sure this line is present
    $landing_page = ($row['securitylevel'] == 'admin') ? 'ADMIN/Dashboard/Dashboard.php' : 'USER/Dashboard/Dashboard.php';
    header("Location: $landing_page");
    exit();
} else {
    $error_message = "Wrong username/password combination";
  }
  sqlsrv_free_stmt($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CIMS - Login</title>
    <link rel="icon" href="SCR_Logo.png" type="image/jpg">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            box-sizing: border-box;
        }

        .logo-section {
            text-align: center;
            margin-right: 150px;
            margin-left: -10px; 
        }

        .logo {
            width: 250px;
            height: 250px;
        }

        .logo-section .name {
            margin-top: 5px;
            margin-bottom: -12px;
            color: #00008b;
            font-size: 40px;
            font-weight: 500;
        }

        .logo-section p {
            font-size: 19px;
            color: #00008b;
        }

        .login-section {
            max-width: 400px; 
        }

        .login-section form {
            display: flex;
            flex-direction: column;
        }

        .login-section label {
            margin-bottom: -9px;
            margin-left: 10px;
            padding: 1px 5px;
            font-size: 15px;
            color: #00008b;
            background-color: white;
            z-index: 100;
            width: 78px;
        }

        .login-section input {
        margin-bottom: 20px;
        padding: 10px;
        border: 1.5px solid #c0c0c0;
        border-radius: 10px;
        font-size: 15px;
        width: 300px;
        transition: border-color 0.3s ease;
        }

        .login-section input:focus {
            outline: none;
            border-color: #00008b;
        }

        .login-section button {
            font-family: 'Montserrat', sans-serif;
            background-color: #00008b;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 10px;
            font-size: 20px;
            cursor: pointer;
            margin-top: 20px;
            font-weight: 500;
            width: 240px;
            margin-left: auto;
            margin-right: auto;
        }

        .login-section svg {
            margin-right: 11px;
            margin-bottom: -5px;
        }

        .login-section .links {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        .login-section .links a {
            color: #333399;
            text-decoration: none;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .login-section .links a:hover {
            text-decoration: underline;
        }
        
        @keyframes shake {
        0%, 20%, 40%, 60%, 80% {
            transform: translateX(8px);
        }
        10%, 30%, 50%, 70%, 90% {
            transform: translateX(-8px);
        }
        }

        @keyframes glow-red {
        50% {
            border-color: red;
        }
        }

        .animate-wrong-password {
        animation-name: shake, glow-red;
        animation-duration: 0.7s, 0.35s;
        animation-iteration-count: 1, 2;
        }

        .tooltip {
        position: relative;
        display: inline-block;
        left: 170px;
        top: 255px;
        }

        .tooltip .tooltip-text {
          visibility: hidden;
          width: 165px;
          background-color: #212121;
          color: #fff;
          text-align: left;
          border-radius: 5px;
          padding: 5px 18px;
          position: absolute;
          z-index: 1;
          bottom: 25%; /* Position above the SVG */
          left: 0%;
          margin-left: -200px; /* Center the tooltip */
          opacity: 0;
          transition: opacity 0.3s;
        }

        .tooltip:hover .tooltip-text {
        visibility: visible;
        opacity: 1;
        }

        .tooltip-text a {
        color: white; /* Default link color */
        text-decoration: none; /* Remove underline */
        cursor: pointer; /* Pointer cursor on hover */
        transition: color 0.3s, text-decoration 0.3s; /* Smooth transition for color and text-decoration */
        }

        .tooltip-text a:hover,.tooltip-text a:focus {
        color: #0056b3; /* Darker color on hover/focus */
        text-decoration: underline; /* Underline on hover/focus */
        }
    </style>
<script>
    <?php if (!empty($error_message)): ?>
    document.addEventListener('DOMContentLoaded', function() {
        var inputs = document.querySelectorAll('.login-section input');
        inputs.forEach(function(input) {
            input.classList.add('animate-wrong-password');
        });
        
        setTimeout(function() {
            inputs.forEach(function(input) {
                input.classList.remove('animate-wrong-password');
            });
        }, 1000);
    });
    <?php endif; ?>
</script>
</head>

<body>
    <div class="logo-section">
        <img src="SCR_Logo.png" alt="Indian Railways Logo" class="logo">
        <p class="name">CIMS</p>
        <p>Coach Information Management System</p>
    </div>
    <div class="login-section">
        <form method="post" action="">
            <label for="user">Username</label>
            <input type="text" id="user_name" name="user_name" required>
            
            <label for="pass">Password</label>
            <input type="password" id="pwd" name="pwd" required>
            
            <button type="submit" name="login_user">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
	            <path fill="white" d="M12 21v-2h7V5h-7V3h7q.825 0 1.413.588T21 5v14q0 .825-.587 1.413T19 21zm-2-4l-1.375-1.45l2.55-2.55H3v-2h8.175l-2.55-2.55L10 7l5 5z" />
                </svg>Login
            </button>
            
            <div class="links">
                <a href="#">Forgot Password?</a>
                <a href="#">Don't have an account? Register Here</a>
            </div>
        </form>
    </div>
    <div class="tooltip">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
            <path fill="#00008b" d="M16 8c0 2.21-1.79 4-4 4s-4-1.79-4-4l.11-.94L5 5.5L12 2l7 3.5v5h-1V6l-2.11 1.06zm-4 6c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4" />
        </svg>
        <div class="tooltip-text">
        <a href="https://www.linkedin.com/in/adarsh-kumar-8091662b1" target="_blank">Adarsh Kumar B</a><br>
        <a href="http://www.linkedin.com/in/anuhya-mattaparthi-15b8b4306" target="_blank">Anuhya M</a><br>
        <a href="https://www.linkedin.com/in/akunuru-jagadeesh-778630280" target="_blank">Jagadeesh A</a><br>
        <a href="https://www.linkedin.com/in/nagavarapu-jayasree-b33a93282" target="_blank">Jayasree N</a><br>
        <a href="http://www.linkedin.com/in/karthik-pedduri-39485a320" target="_blank">Karthik Raj P</a><br>
        <a href="https://www.linkedin.com/in/keerthi-chandana-bobbala-081442269" target="_blank">Keerthi Chandana B</a><br>
        <a href="https://www.linkedin.com/in/anupoju-suma-040771320" target="_blank">Suma A</a><br>
        <a href="http://www.linkedin.com/in/yasa-varalakshmi-783a082b6" target="_blank">Varalakshmi Y</a><br>
        <a href="https://www.linkedin.com/in/konduri-varun-a16971260" target="_blank">Varun K</a><br>
        <a href="https://www.linkedin.com/in/viha-singuluri-2b881429a" target="_blank">Viha S</a>
        </div>
    </div>
</body>
</html>