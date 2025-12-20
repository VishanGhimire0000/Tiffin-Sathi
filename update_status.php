<?php
include 'db.php';

// Security: Only logged-in cooks can update statuses
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'cook') {
    header("Location: index.php");
    exit();
}

if(isset($_GET['id']) && isset($_GET['status'])) {
    $order_id = intval($_GET['id']); 
    $new_status = mysqli_real_escape_string($conn, $_GET['status']);
    $cook_id = $_SESSION['user_id'];

    // Update query with strict ownership check to prevent bugs
    $sql = "UPDATE orders SET status = '$new_status' WHERE id = $order_id AND cook_id = $cook_id";
    
    if(mysqli_query($conn, $sql)) {
        header("Location: dashboard.php?msg=StatusUpdated");
        exit();
    } else {
        die("Error updating status: " . mysqli_error($conn));
    }
}
header("Location: dashboard.php");
?>