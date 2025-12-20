<?php 
include 'db.php';

// 1. Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Validate GET parameters exist to avoid "Undefined Index" error
if(!isset($_GET['order_id']) || !isset($_GET['tiffin_id'])) {
    header("Location: dashboard.php");
    exit();
}

$order_id = (int)$_GET['order_id'];
$tiffin_id = (int)$_GET['tiffin_id'];
$customer_id = $_SESSION['user_id'];

// 3. Prevent Duplicate Review Error
$check = mysqli_query($conn, "SELECT id FROM reviews WHERE order_id = $order_id");
if(mysqli_num_rows($check) > 0) {
    echo "<script>alert('Review already submitted for this order!'); window.location.href='dashboard.php';</script>";
    exit();
}
?>
<?php 
include 'db.php';
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$order_id = $_GET['order_id'];
$tiffin_id = $_GET['tiffin_id'];

if(isset($_POST['submit_review'])) {
    $rating = $_POST['rating'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $cust_id = $_SESSION['user_id'];
    
    // Get cook_id from tiffin
    $t_res = mysqli_query($conn, "SELECT cook_id FROM tiffins WHERE id = $tiffin_id");
    $t_data = mysqli_fetch_assoc($t_res);
    $cook_id = $t_data['cook_id'];

    $query = "INSERT INTO reviews (order_id, customer_id, cook_id, rating, comment) 
              VALUES ('$order_id', '$cust_id', '$cook_id', '$rating', '$comment')";
    
    if(mysqli_query($conn, $query)) {
        header("Location: dashboard.php?review=success");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Submit Review - TiffinSathi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container" style="max-width: 500px; margin-top: 50px;">
        <div class="card" style="padding: 30px;">
            <h2 style="color: var(--primary); margin-bottom: 20px;">Rate Your Meal</h2>
            <form method="POST">
                <div style="margin-bottom: 15px;">
                    <label>Rating (1-5 Stars)</label>
                    <select name="rating" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #ddd;">
                        <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                        <option value="4">⭐⭐⭐⭐ (Good)</option>
                        <option value="3">⭐⭐⭐ (Average)</option>
                        <option value="2">⭐⭐ (Poor)</option>
                        <option value="1">⭐ (Very Bad)</option>
                    </select>
                </div>
                <div style="margin-bottom: 20px;">
                    <label>Your Feedback</label>
                    <textarea name="comment" rows="4" placeholder="How was the food?" required 
                              style="width:100%; padding:10px; border-radius:8px; border:1px solid #ddd;"></textarea>
                </div>
                <button type="submit" name="submit_review" class="btn" style="width:100%;">Post Review</button>
            </form>
        </div>
    </div>
</body>
</html>