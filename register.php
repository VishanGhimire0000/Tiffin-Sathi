<?php 
include 'db.php'; 

// Logic to handle the form submission
if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = $_POST['role'];

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Email already registered!');</script>";
    } else {
        $sql = "INSERT INTO users (name, email, phone, password, role) 
                VALUES ('$name', '$email', '$phone', '$password', '$role')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Registration Successful! Please Login.'); window.location.href='login.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join TiffinSathi</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .auth-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-card {
            background: white;
            width: 100%;
            max-width: 480px;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        .register-card h2 { color: var(--primary); text-align: center; margin-bottom: 5px; }
        .subtitle { text-align: center; color: #777; margin-bottom: 25px; font-size: 0.9rem; }
        
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; font-size: 0.85rem; margin-bottom: 5px; font-weight: 600; color: #444; }
        
        /* The Card-Style Role Selector */
        .role-selection { display: flex; gap: 15px; margin: 15px 0 25px 0; }
        .role-option { flex: 1; position: relative; }
        .role-option input { position: absolute; opacity: 0; cursor: pointer; }
        .role-label {
            display: block; padding: 12px; border: 2px solid #eee; border-radius: 12px;
            text-align: center; cursor: pointer; transition: 0.3s; font-weight: 600; font-size: 0.9rem;
        }
        .role-option input:checked + .role-label {
            border-color: var(--primary); background: #fff5f6; color: var(--primary);
        }
        
        .footer-text { text-align: center; margin-top: 20px; font-size: 0.9rem; }
        .footer-text a { color: var(--primary); text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <nav>
        <div class="logo"><h2>TiffinSathi</h2></div>
        <div><a href="index.php">Home</a></div>
    </nav>

    <div class="auth-wrapper">
        <div class="register-card">
            <h2>Create Account</h2>
            <p class="subtitle">Delicious home-cooked meals are one step away.</p>

            <form method="POST" action="register.php">
                <div class="input-group">
                    <label>Full Name</label>
                    <input type="text" name="name" placeholder="E.g. Rajesh Hamal" required>
                </div>

                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="example@mail.com" required>
                </div>

                <div class="input-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" placeholder="98XXXXXXXX" pattern="[0-9]{10}" maxlength="10" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Create a strong password" required>
                </div>

                <label style="font-size: 0.85rem; font-weight: 600; color: #444;">Join TiffinSathi as a:</label>
                <div class="role-selection">
                    <div class="role-option">
                        <input type="radio" name="role" value="customer" id="cust" checked>
                        <label for="cust" class="role-label">😋 Customer</label>
                    </div>
                    <div class="role-option">
                        <input type="radio" name="role" value="cook" id="cook">
                        <label for="cook" class="role-label">👨‍🍳 Cook</label>
                    </div>
                </div>

                <button type="submit" name="register" class="btn" style="width: 100%;">Sign Up</button>
            </form>

            <div class="footer-text">
                Already have an account? <a href="login.php">Login here</a>
            </div>
        </div>
    </div>

</body>
</html>