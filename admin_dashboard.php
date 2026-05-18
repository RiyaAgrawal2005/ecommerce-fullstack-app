<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

/* =========================
   DASHBOARD DATA
========================= */

$totalProducts = mysqli_fetch_row(
    mysqli_query($conn, "SELECT COUNT(*) FROM products")
)[0];

$totalOrders = mysqli_fetch_row(
    mysqli_query($conn, "SELECT COUNT(*) FROM orders")
)[0];

$pendingOrders = mysqli_fetch_row(
    mysqli_query($conn, "SELECT COUNT(*) FROM orders WHERE status='Pending'")
)[0];

$deliveredOrders = mysqli_fetch_row(
    mysqli_query($conn, "SELECT COUNT(*) FROM orders WHERE status='Delivered'")
)[0];

$cancelledOrders = mysqli_fetch_row(
    mysqli_query($conn, "SELECT COUNT(*) FROM orders WHERE status='Cancelled'")
)[0];

$revRow = mysqli_fetch_row(
    mysqli_query($conn, "SELECT SUM(total) FROM orders WHERE status='Delivered'")
);

$totalRevenue = $revRow[0] ?? 0;

$recentOrders = mysqli_query(
    $conn,
    "SELECT * FROM orders ORDER BY id DESC LIMIT 5"
);

$lowStock = mysqli_query(
    $conn,
    "SELECT * FROM products WHERE stock < 5"
);

$admin_id = intval($_SESSION['admin']);

$admin = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM admins WHERE id='$admin_id'")
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial;
}

body{
    background:#f4f6f9;
}

.dashboard{
    display:flex;
    min-height:100vh;
}

/* SIDEBAR */

.sidebar{
    width:250px;
    background:#111827;
    color:white;
    padding:25px;
}

.sidebar h2{
    text-align:center;
    margin-bottom:35px;
    font-size:28px;
}

.sidebar ul{
    list-style:none;
}

.sidebar ul li{
    margin:12px 0;
}

.sidebar ul li a{
    text-decoration:none;
    color:white;
    display:block;
    padding:14px;
    border-radius:10px;
    transition:0.3s;
    font-size:15px;
}

.sidebar ul li a:hover,
.sidebar .active a{
    background:#2563eb;
}

/* MAIN */

.main{
    flex:1;
    padding:25px;
}

/* TOPBAR */

.topbar{
    background:white;
    padding:15px 20px;
    border-radius:14px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}

.topbar input{
    width:320px;
    padding:12px;
    border:none;
    background:#f1f3f6;
    border-radius:8px;
    outline:none;
}

/* PROFILE */

.profile{
    display:flex;
    align-items:center;
    gap:10px;
    cursor:pointer;
}

.profile img{
    width:42px;
    height:42px;
    border-radius:50%;
}

/* CARDS */

.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-top:25px;
}

.card{
    background:white;
    padding:25px;
    border-radius:16px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.card h4{
    color:#666;
    margin-bottom:10px;
    font-size:15px;
}

.card h2{
    font-size:30px;
    color:#111;
}

/* SECTION */

.section{
    margin-top:30px;
}

.section-title{
    margin-bottom:15px;
    font-size:22px;
    font-weight:600;
}

/* TABLE BOX */

.table-box{
    background:white;
    border-radius:16px;
    padding:20px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    overflow:auto;
}

/* TABLE */

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#111827;
    color:white;
    padding:14px;
    font-size:14px;
    text-align:left;
}

td{
    padding:14px;
    border-bottom:1px solid #eee;
    font-size:14px;
}

tr:hover{
    background:#fafafa;
}

/* STATUS */

.status{
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.pending{
    background:#fff3cd;
    color:#856404;
}

.packed{
    background:#dbeafe;
    color:#1e40af;
}

.shipped{
    background:#d1fae5;
    color:#065f46;
}

.delivered{
    background:#dcfce7;
    color:#166534;
}

.cancelled{
    background:#fee2e2;
    color:#991b1b;
}

/* LOW STOCK */

.stock-item{
    background:#fff3cd;
    color:#856404;
    padding:14px;
    border-radius:10px;
    margin-bottom:12px;
    font-weight:600;
}

/* BUTTON */

.view-btn{
    display:inline-block;
    margin-top:15px;
    text-decoration:none;
    background:#2563eb;
    color:white;
    padding:10px 16px;
    border-radius:8px;
    transition:0.3s;
}

.view-btn:hover{
    background:#1d4ed8;
}

.product-box{
    display:flex;
    align-items:center;
    gap:10px;
}

.product-box img{
    width:50px;
    height:50px;
    object-fit:cover;
    border-radius:10px;
}

</style>
</head>

<body>

<div class="dashboard">

    <!-- SIDEBAR -->

    <div class="sidebar">

        <h2>ShopEasy</h2>

        <ul>
            <li class="active">
                <a href="admin_dashboard.php">🏠 Dashboard</a>
            </li>

            <li>
                <a href="admin.php">📦 Products</a>
            </li>

            <li>
                <a href="admin_orders.php">🛒 Orders</a>
            </li>

            <li>
                <a href="admin_categories.php">📂 Categories</a>
            </li>

            <li>
                <a href="inventory.php">📊 Inventory</a>
            </li>

            <li>
                <a href="invoices.php">🧾 Invoices</a>
            </li>

            <li>
                <a href="settings.php">⚙ Settings</a>
            </li>

            <li>
                <a href="logout.php">🚪 Logout</a>
            </li>
        </ul>

    </div>

    <!-- MAIN -->

    <div class="main">

        <!-- TOPBAR -->

        <div class="topbar">

            <input type="text" placeholder="Search orders, products...">

            <div class="profile">

                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($admin['name']); ?>">

                <span>
                    <?php echo $admin['name']; ?>
                </span>

            </div>

        </div>

        <!-- CARDS -->

        <div class="cards">

            <div class="card">
                <h4>Total Products</h4>
                <h2><?php echo $totalProducts; ?></h2>
            </div>

            <div class="card">
                <h4>Total Orders</h4>
                <h2><?php echo $totalOrders; ?></h2>
            </div>

            <div class="card">
                <h4>Pending Orders</h4>
                <h2><?php echo $pendingOrders; ?></h2>
            </div>

            <div class="card">
                <h4>Delivered Orders</h4>
                <h2><?php echo $deliveredOrders; ?></h2>
            </div>

            <div class="card">
                <h4>Cancelled Orders</h4>
                <h2><?php echo $cancelledOrders; ?></h2>
            </div>

            <div class="card">
                <h4>Total Revenue</h4>
                <h2>₹<?php echo $totalRevenue; ?></h2>
            </div>

        </div>

        <!-- LOW STOCK -->

        <div class="section">

            <div class="section-title">
                ⚠ Low Stock Products
            </div>

            <div class="table-box">

                <?php if(mysqli_num_rows($lowStock) > 0){ ?>

                    <?php while($p = mysqli_fetch_assoc($lowStock)){ ?>

                        <div class="stock-item">
                            <?php echo $p['name']; ?>
                            — Only <?php echo $p['stock']; ?> left
                        </div>

                    <?php } ?>

                <?php } else { ?>

                    <p>All products are in stock ✅</p>

                <?php } ?>

            </div>

        </div>

        <!-- RECENT ORDERS -->

        <div class="section">

            <div class="section-title">
                🛒 Recent Orders
            </div>

            <div class="table-box">

                <table>

                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                    </tr>

                    <?php while($row = mysqli_fetch_assoc($recentOrders)){ ?>

                    <?php

                    $items = mysqli_query($conn, "
                        SELECT p.name, p.image
                        FROM order_items oi
                        JOIN products p
                        ON oi.product_id = p.id
                        WHERE oi.order_id = ".$row['id']."
                        LIMIT 1
                    ");

                    $item = mysqli_fetch_assoc($items);

                    $productImage = $item['image'] ?? 'https://via.placeholder.com/50';

                    $productName = $item['name'] ?? 'Deleted Product';

                    ?>

                    <tr>

                        <td>#<?php echo $row['id']; ?></td>

                        <td>
                            <?php echo $row['customer_name'] ?? 'Customer'; ?>
                        </td>

                        <td>

                            <div class="product-box">

                                <img src="<?php echo $productImage; ?>">

                                <?php echo $productName; ?>

                            </div>

                        </td>

                        <td>
                            ₹<?php echo $row['total']; ?>
                        </td>

                        <td>
                            <?php echo $row['payment_method']; ?>
                        </td>

                        <td>

                            <span class="status <?php echo strtolower($row['status']); ?>">

                                <?php echo $row['status']; ?>

                            </span>

                        </td>

                    </tr>

                    <?php } ?>

                </table>

                <a href="admin_orders.php" class="view-btn">
                    View All Orders →
                </a>

            </div>

        </div>

    </div>

</div>

</body>
</html>