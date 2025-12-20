<?php 
include 'db.php'; 

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$uid = $_SESSION['user_id'];
$role = $_SESSION['role'];
$user_name = $_SESSION['user_name'];

// --- SEPARATED LOGIC FOR COOKS AND CUSTOMERS ---
if($role == 'cook') {
    $total_items_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM tiffins WHERE cook_id = $uid AND is_active = 1");
    $total_items = mysqli_fetch_assoc($total_items_res)['total'];

    $new_orders_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE cook_id = $uid AND status = 'Pending'");
    $new_orders = mysqli_fetch_assoc($new_orders_res)['total'];

    $earnings_res = mysqli_query($conn, "SELECT SUM(t.price) as total 
                                        FROM orders o 
                                        JOIN tiffins t ON o.tiffin_id = t.id 
                                        WHERE o.cook_id = $uid AND o.status = 'Delivered'");
    $earnings = mysqli_fetch_assoc($earnings_res)['total'] ?? 0;
} else {
    // Customer specific stats
    $count_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE customer_id = $uid");
    $total_orders = mysqli_fetch_assoc($count_res)['total'];

    $spent_res = mysqli_query($conn, "SELECT SUM(t.price) as total 
                                     FROM orders o 
                                     JOIN tiffins t ON o.tiffin_id = t.id 
                                     WHERE o.customer_id = $uid AND o.status = 'Delivered'");
    $total_spent = mysqli_fetch_assoc($spent_res)['total'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Dashboard - TiffinSathi</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-box { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); text-align: center; border-top: 4px solid var(--primary); }
        .stat-box h3 { font-size: 0.9rem; color: #777; text-transform: uppercase; }
        .stat-box p { font-size: 1.8rem; font-weight: bold; margin-top: 10px; color: #333; }
        .badge { padding: 4px 10px; border-radius: 5px; font-size: 0.8rem; font-weight: bold; }
        .badge-pending { background: #ffeaa7; color: #d35400; }
        .badge-delivered { background: #55efc4; color: #00b894; }
        .badge-confirmed { background: #81ecec; color: #0984e3; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container" style="margin-top: 30px;">
        <div style="margin-bottom: 30px;">
            <h1>Namaste, <?php echo htmlspecialchars($user_name); ?>!</h1>
            <p>You are viewing your <strong><?php echo ucfirst($role); ?> Dashboard</strong></p>
        </div>

        <?php if($role == 'cook'): ?>
    <div class="stats-grid">
        <div class="stat-box"><h3>Active Meals</h3><p><?php echo $total_items; ?></p></div>
        <div class="stat-box"><h3>New Orders</h3><p><?php echo $new_orders; ?></p></div>
        <div class="stat-box"><h3>Total Earnings</h3><p>Rs. <?php echo number_format($earnings); ?></p></div>
    </div>

    <div class="card" style="padding: 20px; margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Manage My Tiffins</h2>
            <a href="add_tiffin.php" class="btn" style="background: #2ecc71;">+ Add New Meal</a>
        </div>
        
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid #eee;">
                    <th>Image</th>
                    <th>Meal Name</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $my_tiffins = mysqli_query($conn, "SELECT * FROM tiffins WHERE cook_id = $uid");
                while($t = mysqli_fetch_assoc($my_tiffins)): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td><img src="uploads/<?php echo $t['image']; ?>" style="width:50px; height:50px; object-fit:cover; border-radius:5px;"></td>
                    <td style="padding: 15px 0;"><strong><?php echo htmlspecialchars($t['title']); ?></strong></td>
                    <td>Rs. <?php echo $t['price']; ?></td>
                    <td><a href="delete_tiffin.php?id=<?php echo $t['id']; ?>" onmouseover="this.style.backgroundColor='#c0392b';" 
   onmouseout="this.style.backgroundColor='#e74c3c';"
   style="
    background-color: #e74c3c; 
    color: white; 
    padding: 7px 15px; 
    text-decoration: none; 
    border-radius: 6px; 
    font-size: 0.85rem; 
    font-weight: 600;
    transition: background 0.3s;
    display: inline-block;
">Delete</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="card" style="padding: 20px;">
        <h2 style="margin-bottom: 15px;">Incoming Orders</h2>
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="background: #f8f9fa; text-align: left;">
                <th style="padding:10px;">Order ID</th><th>Meal</th><th>Status</th><th>Action</th>
            </tr>
            <?php 
            $orders = mysqli_query($conn, "SELECT o.*, t.title FROM orders o JOIN tiffins t ON o.tiffin_id = t.id WHERE o.cook_id = $uid ORDER BY o.id DESC");
            while($o = mysqli_fetch_assoc($orders)): ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding:10px;">#<?php echo $o['id']; ?></td>
                <td><?php echo $o['title']; ?></td>
                <td><span class="badge badge-<?php echo strtolower($o['status']); ?>"><?php echo $o['status']; ?></span></td>
                <td>
                    <?php if($o['status'] == 'Pending'): ?>
                        <a href="update_status.php?id=<?php echo $o['id']; ?>&status=Confirmed" onmouseover="this.style.backgroundColor='#2980b9';" 
   onmouseout="this.style.backgroundColor='#3498db';"
   style="
    background-color: #3498db; 
    color: white; 
    padding: 7px 15px; 
    text-decoration: none; 
    border-radius: 6px; 
    font-size: 0.85rem;
    font-weight: 600;
    transition: background 0.3s;
    display: inline-block;
">Confirm</a>
                    <?php elseif($o['status'] == 'Confirmed'): ?>
                        <a href="update_status.php?id=<?php echo $o['id']; ?>&status=Delivered" onmouseover="this.style.backgroundColor='#1e8449';" 
   onmouseout="this.style.backgroundColor='#27ae60';"
   style="
    background-color: #27ae60; 
    color: white; 
    padding: 7px 15px; 
    text-decoration: none; 
    border-radius: 6px; 
    font-size: 0.85rem;
    font-weight: 600;
    transition: background 0.3s;
    display: inline-block;
">Mark Delivered</a>
                    <?php else: ?>
                        <span style="color: #999;">Completed</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>


        <?php else: ?>
            <div class="stats-grid">
                <div class="stat-box" style="border-top-color: #0984e3;">
                    <h3>Total Orders</h3>
                    <p><?php echo $total_orders; ?></p>
                </div>
                <div class="stat-box" style="border-top-color: #6c5ce7;">
                    <h3>Total Spent</h3>
                    <p>Rs. <?php echo number_format($total_spent); ?></p>
                </div>
            </div>

            <div class="card" style="padding: 20px;">
                <h2 style="margin-bottom: 15px;">My Order History</h2>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="background: #f8f9fa; text-align: left;">
                        <th style="padding:10px;">Order ID</th><th>Meal</th><th>Price</th><th>Status</th><th>Review</th>
                    </tr>
                    <?php 
                    $my_orders = mysqli_query($conn, "SELECT o.*, t.title, t.price FROM orders o JOIN tiffins t ON o.tiffin_id = t.id WHERE o.customer_id = $uid ORDER BY o.id DESC");
                    while($mo = mysqli_fetch_assoc($my_orders)): 
                        $oid = $mo['id'];
                        $check_rev = mysqli_query($conn, "SELECT id FROM reviews WHERE order_id = $oid");
                        $has_reviewed = mysqli_num_rows($check_rev) > 0;
                    ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding:10px;">#<?php echo $mo['id']; ?></td>
                        <td><?php echo htmlspecialchars($mo['title']); ?></td>
                        <td>Rs. <?php echo $mo['price']; ?></td>
                        <td><span class="badge badge-<?php echo strtolower($mo['status']); ?>"><?php echo $mo['status']; ?></span></td>
                        <td>
                            <?php if($mo['status'] == 'Delivered'): ?>
                                <?php if(!$has_reviewed): ?>
                                    <a href="submit_review.php?order_id=<?php echo $oid; ?>&tiffin_id=<?php echo $mo['tiffin_id']; ?>" onmouseover="this.style.backgroundColor='#d35400';" 
   onmouseout="this.style.backgroundColor='#f39c12';"
   style="
    background-color: #f39c12; 
    color: white; 
    padding: 7px 15px; 
    text-decoration: none; 
    border-radius: 6px; 
    font-size: 0.85rem; 
    font-weight: 600;
    transition: background 0.3s;
    display: inline-block;
">Rate Food</a>
                                <?php else: ?>
                                    <span style="color: green;">✔ Rated</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <small style="color: #999;">Wait for delivery</small>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>