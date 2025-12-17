<nav>
    <div class="logo">
        <h2>TiffinSathi</h2>
    </div>
    <div class="menu">
        <a href="index.php">Explore Food</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php" style="color: var(--primary);">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php" class="btn" style="color:white; padding: 8px 15px;">Join Now</a>
        <?php endif; ?>
    </div>
</nav>