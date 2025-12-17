<?php include 'db.php'; 
if(isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $res = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    if(mysqli_num_rows($res) > 0) {
        $user = mysqli_fetch_assoc($res);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role']; // This is 'cook' or 'customer'

        // Success! Send them to the dashboard
        header("Location: dashboard.php");
    } else {
        echo "<script>alert('Invalid email or password');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-box">
        <h2>Welcome Back</h2>
        <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login" class="btn" style="width:100%">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>