<?php
include 'db.php';
// Ensure session is started to access user role and ID
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Access Control: Block cooks and guests from ordering
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    echo "<script>alert('Please login as a customer to place an order.'); window.location='login.php';</script>";
    exit();
}

// Check if a tiffin ID is provided
if (!isset($_GET['id'])) {
    header("Location: explore.php");
    exit();
}

$t_id = intval($_GET['id']);

// Fetch Tiffin Details and Cook Name
$query = "SELECT t.*, u.name as cook_name FROM tiffins t 
          JOIN users u ON t.cook_id = u.id 
          WHERE t.id = $t_id";
$res = mysqli_query($conn, $query);
$tiffin = mysqli_fetch_assoc($res);

if (!$tiffin) {
    echo "Meal not found.";
    exit();
}

// 2. Fix Logic: Process order only when button is clicked
if (isset($_POST['confirm_order'])) {
    $u_id = $_SESSION['user_id'];
    $cook_id = $tiffin['cook_id'];

    // Insert data into orders table
    $insert = "INSERT INTO orders (customer_id, tiffin_id, cook_id, status) 
               VALUES ($u_id, $t_id, $cook_id, 'Pending')";
    
    if (mysqli_query($conn, $insert)) {
        // GET THE NEW ORDER ID to pass to confirmation page
        $new_order_id = mysqli_insert_id($conn); 
        
        // REDIRECT TO THE NEW CONFIRMATION PAGE
        header("Location: confirmation.php?order_id=" . $new_order_id);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo htmlspecialchars($tiffin['title']); ?> - TiffinSathi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color: #f8f9fa;">
    <?php include 'navbar.php'; ?>

    <div class="container" style="max-width: 900px; margin: 50px auto;">
        <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); display: flex; gap: 40px; align-items: center;">
            <div style="flex: 1;">
                <img src="uploads/<?php echo $tiffin['image']; ?>" style="width: 100%; border-radius: 15px; object-fit: cover; height: 300px;">
            </div>
            
            <div style="flex: 1;">
                <a href="cook_profile.php?id=<?php echo $tiffin['cook_id']; ?>" style="color: #e63946; text-decoration: none; font-size: 0.9rem; font-weight: 600;">View Cook Profile</a>
                <h1 style="margin: 10px 0; font-size: 2.5rem; color: #333;"><?php echo htmlspecialchars($tiffin['title']); ?></h1>
                <p style="color: #666; font-size: 1.1rem; margin-bottom: 20px;">📍 <?php echo htmlspecialchars($tiffin['area']); ?></p>
                
                <h2 style="color: #e63946; font-size: 2rem; margin-bottom: 30px;">Rs. <?php echo number_format($tiffin['price'], 2); ?></h2>
                
                <form method="POST" action="order.php?id=<?php echo $t_id; ?>">
                    <button type="submit" name="confirm_order" style="width: 100%; padding: 15px; background: #e63946; color: white; border: none; border-radius: 10px; font-size: 1.2rem; font-weight: bold; cursor: pointer; transition: 0.3s;">
                        Confirm Order
                    </button>
                </form>
            </div>
        </div>

        <div style="margin-top: 40px; background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
            <h3 style="margin-bottom: 30px; font-size: 1.5rem; color: #333; border-bottom: 2px solid #f4f4f4; padding-bottom: 15px;">What others are saying</h3>
            
            <?php
            // Join reviews with users and orders because reviews table lacks tiffin_id
            $rev_query = "SELECT r.*, u.name FROM reviews r 
                          JOIN users u ON r.customer_id = u.id 
                          JOIN orders o ON r.order_id = o.id 
                          WHERE o.tiffin_id = $t_id 
                          ORDER BY r.id DESC";
            $revs = mysqli_query($conn, $rev_query);

            if (mysqli_num_rows($revs) > 0):
                while ($r = mysqli_fetch_assoc($revs)): ?>
                    <div style="border-bottom: 1px solid #f4f4f4; padding-bottom: 20px; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <strong><?php echo htmlspecialchars($r['name']); ?></strong>
                            <span style="color: #f1c40f;"><?php echo str_repeat('⭐', $r['rating']); ?></span>
                        </div>
                        <p style="margin-top: 10px; color: #555; line-height: 1.6;"><?php echo htmlspecialchars($r['comment']); ?></p>
                        <small style="color: #999;"><?php echo date('M d, Y', strtotime($r['created_at'])); ?></small>
                    </div>
                <?php endwhile;
            else: ?>
                <p style="color: #999; text-align: center; padding: 20px;">No reviews yet for this meal.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>