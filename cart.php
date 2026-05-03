<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>🛒 Your Cart</h1>
    <nav>
        <a href="user_dashboard.php">🏠 Home</a>
    </nav>
</header>

<section class="products" id="cart-items"></section>

<div style="text-align:center; margin-top:20px;">
    <h2 id="total"></h2>
</div>

<div style="text-align:center; margin:30px;">
    <form action="checkout.php" method="POST">
        <input type="hidden" name="total" id="totalInput">

       <!-- <button id="checkoutBtn" style="
    padding:12px 25px;
    background:#28a745;
    color:white;
    border:none;
    border-radius:6px;
    font-size:16px;
    cursor:pointer;"onclick="goCheckout()">Proceed to Checkout ➡️
</button> -->
<button type="button" onclick="goAddress()" id="checkoutBtn">
    Proceed ➡️
</button>
    </form>
</div>

<script>

function goAddress(){
    window.location.href = "address.php";
}

let products = <?php
include 'db.php';
$result = mysqli_query($conn, "SELECT * FROM products");
$data = [];

while($row = mysqli_fetch_assoc($result)){
    $data[] = $row;
}

echo json_encode($data);
?>;
</script>

<script src="cart.js"></script>

</body>
</html>