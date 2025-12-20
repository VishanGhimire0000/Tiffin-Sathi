<?php include 'db.php'; 
if(isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $res = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    if(mysqli_num_rows($res) > 0) {
        $user = mysqli_fetch_assoc($res);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard.php");
    } else {
        echo "<script>alert('Invalid email or password');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - TiffinSathi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container" style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
        <div class="card" style="width: 100%; max-width: 400px; padding: 40px;">
            <h2 style="text-align: center; color: var(--primary); margin-bottom: 20px;">Welcome Back</h2>
            
            <form method="POST">
                <div style="margin-bottom: 15px;">
                    <label style="display:block; margin-bottom:5px;">Email Address</label>
                    <input type="email" name="email" placeholder="email@example.com" required 
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display:block; margin-bottom:5px;">Password</label>
                    <input type="password" name="password" placeholder="••••••••" required 
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
                <button type="submit" name="login" class="btn" style="width: 100%;">Login</button>
            </form>
            
            <p style="text-align: center; margin-top: 20px; font-size: 0.9rem;">
                Don't have an account? <a href="register.php" style="color: var(--primary); text-decoration: none; font-weight: bold;">Register here</a>
            </p>
        </div>
    </div>
</body>
</html>