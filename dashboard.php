<?php 
include 'db.php'; 

// Redirect if not logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$uid = $_SESSION['user_id'];
$role = $_SESSION['role'];
$user_name = $_SESSION['user_name'];
?>
<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TiffinSathi</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Extra Dashboard-Specific Styles */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: var(--shadow);
            text-align: center;
            border-bottom: 4px solid var(--primary);
        }
        .stat-card h3 { font-size: 0.9rem; color: #666; text-transform: uppercase; }
        .stat-card p { font-size: 1.8rem; font-weight: bold; color: var(--secondary); margin-top: 5px; }
        
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; }
        .badge-pending { background: #fff4e6; color: #fd7e14; }
        .badge-delivered { background: #ebfbee; color: #40c057; }
        
        .btn-sm { padding: 6px 12px; font-size: 0.8rem; }
        .img-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body>


    <div class="container">
        <header style="margin-bottom: 40px;">
            <h1>Namaste, <?php echo $user_name; ?>!</h1>
            <p style="color: #666;">Account Type: <span class="badge badge-delivered" style="background:#eee; color:#333;"><?php echo ucfirst($role); ?></span></p>
        </header>

        <?php if($role == 'cook'): ?>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Items</h3>
                    <p><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM tiffins WHERE cook_id=$uid")); ?></p>
                </div>
                <div class="stat-card">
                    <h3>New Orders</h3>
                    <p><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM orders WHERE cook_id=$uid AND status='Pending'")); ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Earnings</h3>
                    <p>Rs. <?php 
                        $earning = mysqli_query($conn, "SELECT SUM(tiffins.price) as total FROM orders JOIN tiffins ON orders.tiffin_id = tiffins.id WHERE orders.cook_id=$uid AND orders.status='Delivered'");
                        $row = mysqli_fetch_assoc($earning);
                        echo $row['total'] ?? 0;
                    ?></p>
                </div>
            </div>

            <div class="dashboard-container">
                <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h2>My Tiffin Menu</h2>
                    <a href="add_tiffin.php" class="btn btn-sm">+ Add New Meal</a>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Item Name</th>
                            <th>Price</th>
                            <th>Area</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $tiffins = mysqli_query($conn, "SELECT * FROM tiffins WHERE cook_id = $uid");
                        while($t = mysqli_fetch_assoc($tiffins)): ?>
                            <tr>
                                <td><img src="uploads/<?php echo $t['image']; ?>" class="img-thumb"></td>
                                <td><strong><?php echo $t['title']; ?></strong></td>
                                <td>Rs. <?php echo $t['price']; ?></td>
                                <td>📍 <?php echo $t['area']; ?></td>
                                <td><a href="delete_tiffin.php?id=<?php echo $t['id']; ?>" style="color:#e74c3c; font-size:0.8rem;" onclick="return confirm('Delete this meal?')">Remove</a></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="dashboard-container">
                <h2>Customer Orders</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Tiffin</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $orders = mysqli_query($conn, "SELECT orders.*, tiffins.title, users.name as c_name 
                                                     FROM orders 
                                                     JOIN tiffins ON orders.tiffin_id = tiffins.id 
                                                     JOIN users ON orders.customer_id = users.id 
                                                     WHERE orders.cook_id = $uid ORDER BY orders.id DESC");
                        while($o = mysqli_fetch_assoc($orders)): ?>
                            <tr>
                                <td>#<?php echo $o['id']; ?></td>
                                <td><?php echo $o['c_name']; ?></td>
                                <td><?php echo $o['title']; ?></td>
                                <td><span class="badge badge-<?php echo strtolower($o['status']); ?>"><?php echo $o['status']; ?></span></td>
                                <td>
                                    <?php if($o['status'] == 'Pending'): ?>
                                        <a href="update_status.php?id=<?php echo $o['id']; ?>&status=Delivered" class="btn btn-sm">Mark Delivered</a>
                                    <?php else: ?>
                                        <span style="color:#aaa;">✔ Completed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        <?php else: ?>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Active Orders</h3>
                    <p><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM orders WHERE customer_id=$uid AND status='Pending'")); ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Spent</h3>
                    <p>Rs. <?php 
                        $spent = mysqli_query($conn, "SELECT SUM(tiffins.price) as total FROM orders JOIN tiffins ON orders.tiffin_id = tiffins.id WHERE orders.customer_id=$uid");
                        $row = mysqli_fetch_assoc($spent);
                        echo $row['total'] ?? 0;
                    ?></p>
                </div>
            </div>

            <div class="dashboard-container">
                <h2>My Order History</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Meal Name</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $my_orders = mysqli_query($conn, "SELECT orders.*, tiffins.title, tiffins.price 
                                                        FROM orders 
                                                        JOIN tiffins ON orders.tiffin_id = tiffins.id 
                                                        WHERE orders.customer_id = $uid ORDER BY orders.id DESC");
                        if(mysqli_num_rows($my_orders) > 0):
                            while($mo = mysqli_fetch_assoc($my_orders)): ?>
                                <tr>
                                    <td>#<?php echo $mo['id']; ?></td>
                                    <td><strong><?php echo $mo['title']; ?></strong></td>
                                    <td>Rs. <?php echo $mo['price']; ?></td>
                                    <td><span class="badge badge-<?php echo strtolower($mo['status']); ?>"><?php echo $mo['status']; ?></span></td>
                                    <td><?php echo date('M d', strtotime($mo['order_date'])); ?></td>
                                </tr>
                            <?php endwhile; 
                        else: ?>
                            <tr><td colspan="5" style="text-align:center; padding: 40px;">No orders yet. <a href="index.php">Go explore some food!</a></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>