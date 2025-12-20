<?php
// Ensure session is started (usually handled in db.php)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav>
    <div class="nav-container" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <a href="index.php" style="text-decoration: none; margin: 0;">
            <h2 style="margin: 0;">TiffinSathi</h2>
        </a>
        
        <div class="nav-links" style="display: flex; align-items: center;">
            <a href="index.php">Explore Food</a>

            <?php if(!isset($_SESSION['user_id'])): ?>
                <a href="how_it_works.php">How It Works</a>
                <a href="about.php">About</a> 
                <a href="contact.php">Contact</a>
                <a href="login.php" class="btn" style="color: white; padding: 8px 15px; margin-left: 10px;">Login</a>
                <a href="register.php" style="font-weight: bold; margin-left: 15px;">Register</a>
            <?php else: ?>
                <a href="dashboard.php">Dashboard</a>
                
                <?php if($_SESSION['role'] == 'cook'): ?>
                    <a href="view_reviews.php">Reviews</a>
                <?php endif; ?>
                
                
                
                <a href="logout.php" style="color: var(--primary); margin-left: 15px;">Logout</a>
            <?php endif; ?>
        </div>
    </div>
</nav>