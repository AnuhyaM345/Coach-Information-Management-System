<?php
// Database configuration
$serverName = getenv('DB_SERVER') ?: "ANU-TRAVELMATE\SQLEXPRESS";
$database = getenv('DB_NAME') ?: "CoachMaster_V_2018";
$username = getenv('DB_USERNAME') ?: "";
$password = getenv('DB_PASSWORD') ?: "";

$connectionOptions = array(
    "Database" => $database,
    "Uid" => $username,
    "PWD" => $password
);

// Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Check connection
if (!$conn) {
    die("Connection failed: " . print_r(sqlsrv_errors(), true));
}