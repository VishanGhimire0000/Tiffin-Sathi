<?php
include 'db.php';
$id = $_GET['id'];
$uid = $_SESSION['user_id'];
// Only allow delete if the tiffin belongs to the logged-in cook
mysqli_query($conn, "DELETE FROM tiffins WHERE id = $id AND cook_id = $uid");
header("Location: dashboard.php");
?>