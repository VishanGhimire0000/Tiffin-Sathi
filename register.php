<?php 
include 'db.php'; 

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = $_POST['role'];

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Email already registered!');</script>";
    } else {
        $sql = "INSERT INTO users (name, email, phone, password, role) VALUES ('$name', '$email', '$phone', '$password', '$role')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Registration Successful! Please Login.'); window.location.href='login.php';</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - TiffinSathi</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Role Selection Styling */
        .role-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }

        .role-card {
            position: relative;
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 12px;
            overflow: hidden;
            transition: 0.3s;
            text-align: center;
            background: #f9f9f9;
        }

        .role-card img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            display: block;
            filter: grayscale(40%);
        }

        .role-card label {
            display: block;
            padding: 10px;
            font-weight: bold;
            cursor: pointer;
            color: #444;
        }

        /* Hide the actual radio button */
        .role-card input[type="radio"] {
            display: none;
        }

        /* Highlight card when selected */
        .role-card input[type="radio"]:checked + .role-content {
            border: 2px solid var(--primary);
            background: #fff5f5;
        }
        
        .role-card input[type="radio"]:checked + .role-content img {
            filter: grayscale(0%);
        }

        .role-card input[type="radio"]:checked + .role-content label {
            color: var(--primary);
        }

        .role-content {
            border: 2px solid #eee;
            border-radius: 12px;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container" style="display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 40px 20px;">
        <div class="card" style="width: 100%; max-width: 500px; padding: 40px;">
            <h2 style="text-align: center; color: var(--primary); margin-bottom: 5px;">Create Account</h2>
            <p style="text-align: center; color: #666; margin-bottom: 25px;">How would you like to use TiffinSathi?</p>

            <form method="POST">
                <div class="role-container">
                    <div class="role-card">
                        <input type="radio" name="role" value="customer" id="buy_food" checked>
                        <label for="buy_food" class="role-content">
                            <img src="https://images.unsplash.com/photo-1543353071-873f17a7a088?auto=format&fit=crop&w=500&q=80" alt="Eat">
                            <label>Buy Food</label>
                        </label>
                    </div>

                    <div class="role-card">
                        <input type="radio" name="role" value="cook" id="cook_food">
                        <label for="cook_food" class="role-content">
                            <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&w=500&q=80" alt="Cook">
                            <label>Cook Food</label>
                        </label>
                    </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display:block; margin-bottom:5px;">Full Name</label>
                    <input type="text" name="name" placeholder="John Doe" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display:block; margin-bottom:5px;">Email Address</label>
                    <input type="email" name="email" placeholder="john@example.com" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display:block; margin-bottom:5px;">Phone Number</label>
                    <input type="text" name="phone" placeholder="98XXXXXXXX" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;">
                </div>
                <div style="margin-bottom: 25px;">
                    <label style="display:block; margin-bottom:5px;">Password</label>
                    <input type="password" name="password" placeholder="••••••••" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;">
                </div>

                <button type="submit" name="register" class="btn" style="width: 100%;">Sign Up</button>
            </form>

            <p style="text-align: center; margin-top: 20px; font-size: 0.9rem;">
                Already have an account? <a href="login.php" style="color: var(--primary); text-decoration: none; font-weight: bold;">Login here</a>
            </p>
        </div>
    </div>
</body>
</html>