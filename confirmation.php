<?php
include 'db.php';

if (!isset($_GET['order_id'])) {
    header("Location: dashboard.php");
    exit();
}

$order_id = intval($_GET['order_id']);

// Fetch order and tiffin details to show on this page
$query = "SELECT o.*, t.title, t.price FROM orders o 
          JOIN tiffins t ON o.tiffin_id = t.id 
          WHERE o.id = $order_id";
$res = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order Confirmed - TiffinSathi</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .success-card {
            max-width: 500px;
            margin: 100px auto;
            background: white;
            padding: 50px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        .check-icon {
            font-size: 5rem;
            color: #2ecc71;
            margin-bottom: 20px;
        }
        .btn-home {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 25px;
            background: #e63946;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="success-card">
        <div class="check-icon">✔</div>
        <h1 style="color: #333;">Order Confirmed!</h1>
        <p style="color: #666; margin-top: 10px;">Thank you for your order. Your meal is being prepared.</p>
        
        <div style="margin-top: 30px; padding: 20px; background: #f9f9f9; border-radius: 10px; text-align: left;">
            <p><strong>Order ID:</strong> #<?php echo $order['id']; ?></p>
            <p><strong>Meal:</strong> <?php echo htmlspecialchars($order['title']); ?></p>
            <p><strong>Total Price:</strong> Rs. <?php echo number_format($order['price'], 2); ?></p>
            <p><strong>Status:</strong> <span style="color: #f39c12; font-weight: bold;"><?php echo $order['status']; ?></span></p>
        </div>

        <a href="dashboard.php" class="btn-home">Go to My Orders</a>
    </div>
</body>
</html>