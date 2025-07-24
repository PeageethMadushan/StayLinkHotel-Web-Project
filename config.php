<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "staylink_hotel";

// Create a new connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check for any connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
