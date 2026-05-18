<?php
session_start();
include 'db.php';

if(isset($_POST['update'])){
    $id = $_POST['id'];
    $status = $_POST['status'];

    mysqli_query($conn, "UPDATE orders SET status='$status' WHERE id='$id'");
}

if(isset($_POST['delete'])){

    $id = $_POST['id'];

    // delete order items first
    mysqli_query($conn,
    "DELETE FROM order_items WHERE order_id='$id'");

    // delete order
    mysqli_query($conn,
    "DELETE FROM orders WHERE id='$id'");
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



table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:14px;
    overflow:hidden;
    box-shadow:0 4px 15px rgba(0,0,0,0.08);
}

th{
    background:#111;
    color:#fff;
    padding:14px;
    font-size:14px;
    text-align:left;
}

td{
    padding:14px;
    border-bottom:1px solid #eee;
    vertical-align:middle;
    font-size:14px;
}

tr:hover{
    background:#fafafa;
}

.action-box{
    display:flex;
    gap:8px;
}

select{
    padding:8px;
    border-radius:6px;
    border:1px solid #ccc;
}

button{
    padding:8px 14px;
    border:none;
    border-radius:6px;
    background:#667eea;
    color:#fff;
    cursor:pointer;
    font-weight:bold;
}

button:hover{
    background:#5a67d8;
}

.update-btn{
    background:#667eea;
}

.update-btn:hover{
    background:#5a67d8;
}

.delete-btn{
    background:#e53935;
}

.delete-btn:hover{
    background:#c62828;
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
    <th>Product</th>
    <th>Customer</th>
    <th>Phone</th>
    <th>Address</th>
    <th>Qty</th>
    <th>Size</th>
    <th>Total</th>
    <th>Payment</th>
    <th>Payment Status</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while($o = mysqli_fetch_assoc($orders)){ 

$items = mysqli_query($conn, "

SELECT oi.*, p.name, p.image

FROM order_items oi

JOIN products p
ON oi.product_id = p.id

WHERE oi.order_id = ".$o['id']." LIMIT 1

");

// $item = mysqli_fetch_assoc($items);
$item = mysqli_fetch_assoc($items);

if(!$item){
    $item = [
        'name' => 'Product Deleted',
        'image' => 'https://via.placeholder.com/60',
        'qty' => 1,
        'size' => 'N/A'
    ];
}

?>

<tr>

<td>
    <b>#<?php echo $o['id']; ?></b>
</td>

<td>
    <div style="display:flex;align-items:center;gap:10px;">

        <img src="<?php echo $item['image']; ?>"
        style="
        width:60px;
        height:60px;
        object-fit:cover;
        border-radius:10px;
        border:1px solid #ddd;
        ">

        <div>
            <b><?php echo $item['name']; ?></b>
        </div>

    </div>
</td>

<td>
    <?php echo $o['customer_name'] ?? 'Customer'; ?>
</td>

<td>
    <?php echo $o['phone'] ?? 'N/A'; ?>
</td>

<td style="max-width:200px;">
    <?php echo $o['address']; ?>,
    <?php echo $o['city']; ?> -
    <?php echo $o['pincode']; ?>
</td>

<td>
    <?php echo $item['qty']; ?>
</td>

<td>
    <?php echo $item['size']; ?>
</td>

<td>
    <b>₹<?php echo $o['total']; ?></b>
</td>

<td>
    <?php echo $o['payment_method']; ?>
</td>

<td>
    <span class="status pending">
        <?php echo $o['payment_status'] ?? 'Pending'; ?>
    </span>
</td>

<td>

<span class="status <?php echo strtolower($o['status']); ?>">

<?php echo $o['status']; ?>

</span>

</td>

<td>

<form method="POST" class="action-box">

<input type="hidden"
name="id"
value="<?php echo $o['id']; ?>">

<select name="status">

<option <?php if($o['status']=="Pending") echo "selected"; ?>>
Pending
</option>

<option <?php if($o['status']=="Packed") echo "selected"; ?>>
Packed
</option>

<option <?php if($o['status']=="Shipped") echo "selected"; ?>>
Shipped
</option>

<option <?php if($o['status']=="Delivered") echo "selected"; ?>>
Delivered
</option>

<option <?php if($o['status']=="Cancelled") echo "selected"; ?>>
Cancelled
</option>

</select>

<button name="update">
Update
</button>

<button 
name="delete"
class="delete-btn"
onclick="return confirm('Delete this order?')">

Delete

</button>

</form>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>