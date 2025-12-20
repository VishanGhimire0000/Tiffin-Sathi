<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Contact Us - TiffinSathi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <section class="hero" style="background-image: url('https://images.unsplash.com/photo-1423666639041-f56000c27a9a?auto=format&fit=crop&w=1500&q=80');">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>Get in Touch</h1>
        <p>Have questions or feedback? We're here to help.</p>
    </div>
</section>

    <div class="container" style="max-width: 600px;">
        <div class="card" style="padding: 40px;">
            <form action="#">
                <div style="margin-bottom: 15px;">
                    <label>Full Name</label>
                    <input type="text" placeholder="Enter your name" required style="width:100%; padding:10px; margin-top:5px; border:1px solid #ddd; border-radius:5px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Email Address</label>
                    <input type="email" placeholder="Enter your email" required style="width:100%; padding:10px; margin-top:5px; border:1px solid #ddd; border-radius:5px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Message</label>
                    <textarea placeholder="How can we help?" rows="5" style="width: 100%; padding: 10px; margin-top:5px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
                </div>
                <button type="submit" class="btn" style="width: 100%;">Send Message</button>
            </form>
        </div>
    </div>
</body>
</html>