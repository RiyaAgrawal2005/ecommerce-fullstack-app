<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

/* =========================
   FETCH REAL DATA
========================= */

// Total Products
$totalProducts = mysqli_fetch_row(
    mysqli_query($conn, "SELECT COUNT(*) FROM products")
)[0];

// Total Orders (exclude cancelled)
$totalOrders = mysqli_fetch_row(
    mysqli_query($conn, "SELECT COUNT(*) FROM orders WHERE status != 'Cancelled'")
)[0];

// Total Revenue (only delivered)
$revRow = mysqli_fetch_row(
    mysqli_query($conn, "SELECT SUM(total) FROM orders WHERE status = 'Delivered'")
);
$totalRevenue = $revRow[0] ?? 0;

// Recent Orders (last 5)
$recentOrders = mysqli_query(
    $conn,
    "SELECT * FROM orders ORDER BY id DESC LIMIT 5"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<style>
    .status{
    padding:4px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.pending{background:#fff3cd;color:#856404;}
.packed{background:#e3f2fd;color:#1565c0;}
.shipped{background:#d0f0fd;color:#0277bd;}
.delivered{background:#d4edda;color:#155724;}
.cancelled{background:#f8d7da;color:#721c24;}
.returned{background:#fce4ec;color:#ad1457;}
.exchanged{background:#ede7f6;color:#512da8;}
</style>
<body>

<div class="dashboard">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>ShopEasy</h2>

        <ul>
            <li class="active">Dashboard</li>
            <li><a href="admin.php">Products</a></li>
            <li><a href="admin_orders.php">Orders</a></li>
            
            <li><a href="admin_categories.php">Category</a></li>
<li><a href="inventory.php">Inventory</a></li>
<li><a href="invoices.php">Invoices</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main">

        <!-- TOP BAR -->
        <div class="topbar">
            <input type="text" placeholder="Search...">

            <?php


$admin_id = intval($_SESSION['admin']);
$admin = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM admins WHERE id=$admin_id")
);
?>

<div class="profile-wrapper">

    <div class="profile" onclick="toggleProfile()">
        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($admin['name']); ?>">
        <span><?php echo $admin['name']; ?></span>
    </div>

    <div id="profileBox" class="profile-box">
       
        
    </div>

</div>
        </div>

        <!-- CARDS -->
        <div class="cards">
            <div class="card">
                Total Products 
                <h3><?php echo $totalProducts; ?></h3>
            </div>

            <div class="card">
                Orders 
                <h3><?php echo $totalOrders; ?></h3>
            </div>

            <div class="card">
                Revenue 
                <h3>₹<?php echo $totalRevenue ? $totalRevenue : 0; ?></h3>
            </div>
        </div>

        <!-- TABLE -->
        <div class="table-box">
            <h3>Recent Orders</h3>

            <table>
<tr>
    <th>ID</th>
    <th>Product</th>
    <th>Price</th>
    <th>Status</th>
    <th>Update</th>
</tr>

<?php while($row = mysqli_fetch_assoc($recentOrders)){ 

$items = mysqli_query($conn, "
    SELECT p.name 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ".$row['id']." LIMIT 1
");

$item = mysqli_fetch_assoc($items);
?>

<tr>
    <td>#<?php echo $row['id']; ?></td>

    <td><?php echo $item['name'] ?? 'N/A'; ?></td>

    <td>₹<?php echo $row['total']; ?></td>

    <td>
        <span class="status <?php echo strtolower($row['status']); ?>">
            <?php echo $row['status']; ?>
        </span>
    </td>

    <td>
        <form method="POST" action="update_status.php">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <select name="status">
                <option <?php if($row['status']=="Pending") echo "selected"; ?>>Pending</option>
                <option <?php if($row['status']=="Packed") echo "selected"; ?>>Packed</option>
                <option <?php if($row['status']=="Shipped") echo "selected"; ?>>Shipped</option>
                <option <?php if($row['status']=="Delivered") echo "selected"; ?>>Delivered</option>
                <option <?php if($row['status']=="Cancelled") echo "selected"; ?>>Cancelled</option>
                <option <?php if($row['status']=="Returned") echo "selected"; ?>>Returned</option>
                <option <?php if($row['status']=="Exchanged") echo "selected"; ?>>Exchanged</option>
            </select>

            <button>Update</button>
        </form>
    </td>
</tr>

<?php } ?>

                <?php if(mysqli_num_rows($recentOrders) == 0){ ?>
<tr>
    <td colspan="3">No orders yet</td>
</tr>
<?php } ?>

            </table>
        </div>

    </div>
</div>
<script>
  function toggleProfile(){
    let box = document.getElementById("profileBox");
    box.style.display = "block";
}

// close on outside click
document.addEventListener("click", function(e){
    let profile = document.querySelector(".profile");
    let box = document.getElementById("profileBox");

    if(!profile.contains(e.target)){
        box.style.display = "none";
    }
});

function updateStatus(id, status){
    fetch("update_status.php", {
        method: "POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"},
        body: "id=" + id + "&status=" + status
    })
    .then(() => alert("Status Updated ✅"));

}
</script>
</body>
</html>