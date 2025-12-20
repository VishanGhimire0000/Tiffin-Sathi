<?php 
include 'db.php';
if($_SESSION['role'] != 'cook') header("Location: index.php");
$cook_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Reviews - TiffinSathi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container" style="margin-top: 40px;">
        <h2 style="margin-bottom: 30px;">Customer Reviews</h2>
        <div class="grid">
            <?php
            $rev_query = "SELECT reviews.*, users.name as customer_name, tiffins.title 
                          FROM reviews 
                          JOIN users ON reviews.customer_id = users.id 
                          JOIN tiffins ON reviews.order_id = tiffins.id -- simplified for example
                          WHERE reviews.cook_id = $cook_id ORDER BY reviews.created_at DESC";
            
            // Note: Fixed query to join via order_id accurately depending on your table logic
            $rev_query = "SELECT reviews.*, users.name as customer_name, tiffins.title 
                          FROM reviews 
                          JOIN users ON reviews.customer_id = users.id 
                          JOIN orders ON reviews.order_id = orders.id
                          JOIN tiffins ON orders.tiffin_id = tiffins.id
                          WHERE reviews.cook_id = $cook_id";
                          
            $res = mysqli_query($conn, $rev_query);
            if(mysqli_num_rows($res) > 0):
                while($row = mysqli_fetch_assoc($res)): ?>
                <div class="card" style="padding: 20px;">
                    <h4 style="color: var(--primary);"><?php echo $row['title']; ?></h4>
                    <p style="font-size: 0.9rem; color: #666;">By: <?php echo $row['customer_name']; ?></p>
                    <div style="color: #f1c40f; margin: 10px 0;">
                        <?php echo str_repeat("⭐", $row['rating']); ?>
                    </div>
                    <p>"<?php echo $row['comment']; ?>"</p>
                    <small style="color: #999;"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></small>
                </div>
            <?php endwhile; 
            else: echo "<p>No reviews yet.</p>"; 
            endif; ?>
        </div>
    </div>
</body>
</html>