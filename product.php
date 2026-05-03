<?php
include 'db.php';

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
$product = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $product['name']; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="card" style="width:500px; margin:50px auto;">
    <img src="<?php echo $product['image']; ?>">

    <h2><?php echo $product['name']; ?></h2>

    <p><?php echo $product['description']; ?></p>

    <h3>₹<?php echo $product['discount_price']; ?></h3>

    <p>⭐ <?php echo $product['rating']; ?></p>

    <p>Category: <?php echo $product['category']; ?></p>

    <p><?php echo ($product['stock'] > 0) ? "In Stock" : "Out of Stock"; ?></p>

    <button onclick="addToCart(<?php echo $product['id']; ?>)">
        Add to Cart
    </button>
</div>

<script src="script.js"></script>
</body>
</html>