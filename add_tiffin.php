<?php
include 'db.php';

// Access control: Only logged-in cooks can see this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'cook') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['publish'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $area = mysqli_real_escape_string($conn, $_POST['area']);
    $cook_id = $_SESSION['user_id'];

    // Secure image upload
    $image_name = time() . '_' . basename($_FILES['image']['name']);
    $target = "uploads/" . $image_name;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $sql = "INSERT INTO tiffins (cook_id, title, price, area, image) VALUES ('$cook_id', '$title', '$price', '$area', '$image_name')";
        if (mysqli_query($conn, $sql)) {
            header("Location: dashboard.php?success=1");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Post New Tiffin - TiffinSathi</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .form-card {
            max-width: 600px;
            margin: 60px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        .form-card h1 { color: #e63946; text-align: center; margin-bottom: 10px; font-size: 2rem; }
        .form-card p { text-align: center; color: #666; margin-bottom: 30px; }
        
        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; font-weight: bold; margin-bottom: 8px; color: #333; }
        .input-field {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 1rem;
        }
        .input-field:focus { border-color: #e63946; outline: none; box-shadow: 0 0 5px rgba(230, 57, 70, 0.2); }
        
        .flex-row { display: flex; gap: 20px; }
        .flex-row .input-group { flex: 1; }

        .upload-area {
            border: 2px dashed #ccc;
            padding: 30px;
            text-align: center;
            border-radius: 8px;
            background: #fafafa;
            cursor: pointer;
            transition: 0.3s;
        }
        .upload-area:hover { border-color: #e63946; background: #fff5f5; }
        
        .btn-submit {
            width: 100%;
            padding: 15px;
            background: #e63946;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-submit:hover { background: #d62828; transform: translateY(-2px); }
        .cancel-btn { display: block; text-align: center; margin-top: 15px; color: #888; text-decoration: none; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="form-card">
        <h1>Post a New Tiffin</h1>
        <p>Enter the details of your home-cooked meal.</p>

        <form action="add_tiffin.php" method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label>Meal Title</label>
                <input type="text" name="title" class="input-field" placeholder="e.g., Authentic Nepali Thali" required>
            </div>

            <div class="flex-row">
                <div class="input-group">
                    <label>Price (NPR)</label>
                    <input type="number" name="price" class="input-field" placeholder="e.g., 250" required>
                </div>
                <div class="input-group">
                    <label>Pickup Area</label>
                    <input type="text" name="area" class="input-field" placeholder="e.g., Patan" required>
                </div>
            </div>

            <div class="input-group">
                <label>Food Image</label>
                <div class="upload-area" onclick="document.getElementById('file-input').click()">
                    <span id="file-name">Click to select meal image</span>
                    <input type="file" name="image" id="file-input" style="display:none;" required onchange="showName(this)">
                </div>
            </div>

            <button type="submit" name="publish" class="btn-submit">Publish Listing</button>
            <a href="dashboard.php" class="cancel-btn">Cancel and return</a>
        </form>
    </div>

    <script>
        function showName(input) {
            const fileName = input.files[0].name;
            document.getElementById('file-name').innerText = "Selected: " + fileName;
        }
    </script>
</body>
</html>