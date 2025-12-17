<?php include 'db.php'; ?>
<?php include 'navbar.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>TiffinSathi - Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>


    <div class="container">
        <header style="text-align:center; margin-bottom: 3rem;">
            <h1>Fresh Home Food in Nepal</h1>
            <p>Connect directly with local home cooks near you.</p>
        </header>

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
                        <a href='order.php?id={$row['id']}' class='btn'>Order Now</a>
                    </div>
                </div>";
            }
            ?>
        </div>
    </div>
</body>
</html>