<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

$orders = mysqli_query($conn,"
    SELECT o.*, u.name, u.email 
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Invoices</title>

<style>
.box{
    width:80%;
    margin:20px auto;
    background:#fff;
    padding:20px;
    border-radius:10px;
    margin-bottom:20px;
}
</style>

</head>
<body>

<h2>🧾 Invoices</h2>

<?php while($o = mysqli_fetch_assoc($orders)){ ?>

<div class="box">

<h3>Order #<?php echo $o['id']; ?></h3>

<p><b>Name:</b> <?php echo $o['name']; ?></p>
<p><b>Email:</b> <?php echo $o['email']; ?></p>
<p><b>Address:</b> <?php echo $o['address']; ?></p>

<p><b>Total:</b> ₹<?php echo $o['total']; ?></p>
<p><b>Status:</b> <?php echo $o['status']; ?></p>

</div>

<?php } ?>

</body>
</html>s