<?php
include 'db.php';

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Role Check
if ($_SESSION['role'] !== 'customer') {
    echo "<script>alert('Only customers can place orders!'); window.location.href='index.php';</script>";
    exit();
}

// 3. Process and Display Receipt
if (isset($_GET['id'])) {
    $tiffin_id = mysqli_real_escape_string($conn, $_GET['id']);
    $customer_id = $_SESSION['user_id'];

    // Fetch tiffin and cook details
    $tiffin_res = mysqli_query($conn, "SELECT * FROM tiffins WHERE id = '$tiffin_id'");
    $tiffin_data = mysqli_fetch_assoc($tiffin_res);
    
    if ($tiffin_data) {
        $cook_id = $tiffin_data['cook_id'];
        $title = $tiffin_data['title'];
        $price = $tiffin_data['price'];
        $area = $tiffin_data['area'];

        // Insert into orders table
        $sql = "INSERT INTO orders (customer_id, tiffin_id, cook_id, status) 
                VALUES ('$customer_id', '$tiffin_id', '$cook_id', 'Pending')";

        if (mysqli_query($conn, $sql)) {
            $order_id = mysqli_insert_id($conn); // Get the ID of the new order
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Order Success - TiffinSathi</title>
                <link rel="stylesheet" href="style.css">
                <style>
                    .receipt-card {
                        background: white; padding: 30px; border-radius: 15px;
                        max-width: 500px; margin: 50px auto; text-align: center;
                        box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-top: 5px solid var(--primary);
                    }
                    .check-mark { font-size: 50px; color: #2ecc71; margin-bottom: 10px; }
                    .receipt-details { text-align: left; margin: 20px 0; padding: 15px; background: #f9f9f9; border-radius: 10px; }
                    .detail-row { display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px dashed #ddd; padding-bottom: 5px; }
                </style>
            </head>
            <body>
                <div class="receipt-card">
                    <div class="check-mark">✔</div>
                    <h2>Order Successfully Placed!</h2>
                    <p>Your order has been sent to the cook.</p>

                    <div class="receipt-details">
                        <div class="detail-row"><strong>Order ID:</strong> <span>#<?php echo $order_id; ?></span></div>
                        <div class="detail-row"><strong>Item:</strong> <span><?php echo $title; ?></span></div>
                        <div class="detail-row"><strong>Area:</strong> <span><?php echo $area; ?></span></div>
                        <div class="detail-row"><strong>Total Price:</strong> <span style="color:var(--primary); font-weight:bold;">Rs. <?php echo $price; ?></span></div>
                        <div class="detail-row"><strong>Status:</strong> <span>Pending Confirmation</span></div>
                    </div>

                    <a href="dashboard.php" class="btn">View My Orders</a>
                    <br><br>
                    <a href="index.php" style="text-decoration:none; color:#666;">Back to Home</a>
                </div>
            </body>
            </html>
            <?php
            exit(); // Stop further execution so the receipt stays on screen
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Tiffin not found.";
    }
} else {
    header("Location: index.php");
}
?>