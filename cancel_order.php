<?php
include 'db.php';

$order_id = $_POST['order_id'];

mysqli_query($conn, "
    UPDATE orders 
    SET status='Cancelled' 
    WHERE id='$order_id'
");

header("Location: orders.php");
?>