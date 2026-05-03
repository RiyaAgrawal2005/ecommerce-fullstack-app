<?php
session_start();
include 'db.php';

if(isset($_POST['update'])){
    $id = $_POST['id'];
    $status = $_POST['status'];

    mysqli_query($conn, "UPDATE orders SET status='$status' WHERE id='$id'");
}

$orders = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Orders</title>

<style>
body{
    margin:0;
    font-family: Arial;
    background:#f4f6f9;
}

/* HEADER */


.header{
    background: linear-gradient(90deg,#667eea,#764ba2);
    color:#fff;
    padding:15px 25px;
    font-size:20px;
    font-weight:bold;

    display:flex;
    justify-content:space-between;
    align-items:center;
}

/* HOME BUTTON */
.home-btn{
    text-decoration:none;
    background:#fff;
    color:#667eea;
    padding:8px 14px;
    border-radius:6px;
    font-size:14px;
    font-weight:bold;
    transition:0.3s;
}

.home-btn:hover{
    background:#e0e7ff;
}
/* CONTAINER */
.container{
    padding:20px;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

/* HEADER */
th{
    background:#f1f3f6;
    padding:12px;
    text-align:left;
    font-size:14px;
}

/* ROW */
td{
    padding:12px;
    border-bottom:1px solid #eee;
    font-size:14px;
}

/* HOVER */
tr:hover{
    background:#fafafa;
}

/* STATUS BADGES */
.status{
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.pending{background:#fff3cd;color:#856404;}
.packed{background:#e3f2fd;color:#1565c0;}
.shipped{background:#e8f5e9;color:#2e7d32;}
.delivered{background:#d1c4e9;color:#4a148c;}

/* SELECT */
select{
    padding:6px;
    border-radius:5px;
    border:1px solid #ccc;
    outline:none;
}

/* BUTTON */
button{
    padding:7px 12px;
    border:none;
    border-radius:5px;
    background:#667eea;
    color:#fff;
    cursor:pointer;
    font-size:12px;
}

button:hover{
    background:#5a67d8;
}

/* FLEX ACTION */
.action-box{
    display:flex;
    gap:8px;
}
</style>

</head>
<body>

<!-- <div class="header">📦 Admin Order Management</div> -->
<div class="header">
    <span>📦 Admin Order Management</span>

    <a href="admin_dashboard.php" class="home-btn">🏠 Home</a>
</div>

<div class="container">

<table>
<tr>
    <th>Order ID</th>
    <th>Total</th>
    <th>Status</th>
    <th>Update Status</th>
</tr>

<?php while($o = mysqli_fetch_assoc($orders)){ ?>

<tr>
<td>#<?php echo $o['id']; ?></td>

<td><b>₹<?php echo $o['total']; ?></b></td>

<td>
<span class="status <?php echo strtolower($o['status']); ?>">
    <?php echo $o['status']; ?>
</span>
</td>

<td>
<form method="POST" class="action-box">
    <input type="hidden" name="id" value="<?php echo $o['id']; ?>">

    <select name="status">
        <option <?php if($o['status']=="Pending") echo "selected"; ?>>Pending</option>
        <option <?php if($o['status']=="Packed") echo "selected"; ?>>Packed</option>
        <option <?php if($o['status']=="Shipped") echo "selected"; ?>>Shipped</option>
        <option <?php if($o['status']=="Delivered") echo "selected"; ?>>Delivered</option>
    </select>

    <button name="update">Update</button>
</form>
</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>