<?php 
include 'db.php';
$c_id = intval($_GET['id']);
$cook_res = mysqli_query($conn, "SELECT name, email, phone FROM users WHERE id = $c_id AND role = 'cook'");
$cook = mysqli_fetch_assoc($cook_res);
if(!$cook) header("Location: index.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $cook['name']; ?> - Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container" style="text-align: center; margin-top: 50px;">
        <div class="register-card" style="margin: auto; max-width: 500px;">
            <div style="font-size: 50px;">👨‍🍳</div>
            <h2><?php echo $cook['name']; ?></h2>
            <hr style="margin: 20px 0; opacity: 0.1;">
            
            <p style="margin-bottom: 10px;"><strong>Contact Details</strong></p>
            <div style="background: #f9f9f9; padding: 20px; border-radius: 10px; text-align: left;">
                <p style="margin-bottom: 8px;">📧 <strong>Email:</strong> <?php echo $cook['email']; ?></p>
                <p>📞 <strong>Phone:</strong> <?php echo $cook['phone']; ?></p>
            </div>

            <h3 style="margin-top: 30px;">Active Menu</h3>
            <?php
            $items = mysqli_query($conn, "SELECT * FROM tiffins WHERE cook_id = $c_id");
            while($it = mysqli_fetch_assoc($items)): ?>
                <div style="display: flex; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee;">
                    <span><?php echo $it['title']; ?></span>
                    <strong>Rs. <?php echo $it['price']; ?></strong>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>