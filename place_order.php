






















<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user'];

$total = $_POST['total'];
$cart = json_decode($_POST['cart'], true);

// 🔥 NEW FIELDS
$name = $_POST['name'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$city = $_POST['city'];
$pincode = $_POST['pincode'];
$payment_method = $_POST['payment_method'];

// ✅ SAVE ORDER (UPDATED)
mysqli_query($conn, "
INSERT INTO orders 
(user_id, total, status, payment_method, address, city, pincode) 
VALUES 
('$user_id', '$total', 'Pending', '$payment_method', '$address', '$city', '$pincode')
");

$order_id = mysqli_insert_id($conn);

// 📦 SAVE ITEMS
foreach($cart as $item){
    $pid = $item['id'];
    $size = $item['size'];
    $qty = $item['qty'];

    mysqli_query($conn, "
    INSERT INTO order_items (order_id, product_id, size, qty)
    VALUES ('$order_id', '$pid', '$size', '$qty')
    ");
}

// 🧹 CLEAR CART
echo "<script>
localStorage.removeItem('cart');
localStorage.removeItem('buyNow');
window.location.href='orders.php';
</script>";
?>