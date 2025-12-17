<?php 
include 'db.php';

// Security: Only cooks can access this page
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'cook') {
    header("Location: index.php");
    exit();
}

if(isset($_POST['add'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $area = mysqli_real_escape_string($conn, $_POST['area']);
    
    // Handle Image Upload
    $img = time() . '_' . $_FILES['image']['name']; // Added timestamp to prevent duplicate names
    $target = "uploads/" . $img;
    
    if(move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $uid = $_SESSION['user_id'];
        $query = "INSERT INTO tiffins (cook_id, title, price, area, image) VALUES ('$uid', '$title', '$price', '$area', '$img')";
        mysqli_query($conn, $query);
        header("Location: dashboard.php?success=listing_added");
    } else {
        echo "<script>alert('Failed to upload image. Check if uploads folder exists.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post New Listing - TiffinSathi</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .listing-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .listing-card {
            background: white;
            width: 100%;
            max-width: 600px;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .listing-card h2 {
            color: var(--primary);
            margin-bottom: 10px;
            font-size: 1.8rem;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 0.95rem;
        }

        .input-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #444;
            font-size: 0.9rem;
        }

        /* Styling the file input */
        input[type="file"] {
            padding: 10px;
            background: #f8f9fa;
            border: 2px dashed #ddd;
            cursor: pointer;
        }

        input[type="file"]:hover {
            border-color: var(--primary);
        }

        .form-footer {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>

    <nav>
        <div class="logo"><h2>TiffinSathi</h2></div>
        <div>
            <a href="dashboard.php">Back to Dashboard</a>
        </div>
    </nav>

    <div class="listing-wrapper">
        <div class="listing-card">
            <h2>Post a New Tiffin</h2>
            <p class="subtitle">Fill in the details to share your delicious home-cooked meal with your community.</p>

            <form method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <label>Meal Title</label>
                    <input type="text" name="title" placeholder="e.g., Authentic Nepali Thakali Set" required>
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <label>Price (NPR)</label>
                        <input type="number" name="price" placeholder="e.g., 250" required>
                    </div>
                    <div class="input-group">
                        <label>Pickup Area</label>
                        <input type="text" name="area" placeholder="e.g., Patan, Lalitpur" required>
                    </div>
                </div>

                <div class="input-group">
                    <label>Food Image</label>
                    <input type="file" name="image" accept="image/*" required>
                    <small style="color: #888;">Clear photos of the meal get more orders!</small>
                </div>

                <div class="form-footer">
                    <a href="dashboard.php" style="text-decoration: none; color: #666; font-size: 0.9rem;">Cancel</a>
                    <button type="submit" name="add" class="btn" style="padding: 12px 40px;">Publish Listing</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>