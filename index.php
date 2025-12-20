<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>TiffinSathi - Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <section class="hero" style="background-image: url('https://images.unsplash.com/photo-1547592166-23ac45744acd?auto=format&fit=crop&w=1500&q=80');">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>Fresh Home Food in Nepal</h1>
            <p>Connect directly with local home cooks near you for authentic meals.</p>
        </div>
    </section>

    <div class="container">
        <div class="grid">
            <?php
            $res = mysqli_query($conn, "SELECT * FROM tiffins");
            while($row = mysqli_fetch_assoc($res)) {
                echo "
                <div class='card'>
                    <img src='uploads/{$row['image']}' alt='food'>
                    <div class='card-content'>
                        <h3>{$row['title']}</h3>
                        <p style='color: #666;'>📍 {$row['area']}</p>
                        <h4 style='color: var(--primary)'>Rs. {$row['price']}</h4>
                        <a href='order.php?id={$row['id']}' class='btn' style='margin-top:10px; width:100%'>Order Now</a>
                    </div>
                </div>";
            }
            ?>
        </div>
    </div>
</body>
</html>