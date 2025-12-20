<?php
$conn = mysqli_connect("localhost", "root", "","tiffinsathi");
if (!$conn) { die("Connection failed: " . mysqli_connect_error()); }

// Check if a session is already started before starting one
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>