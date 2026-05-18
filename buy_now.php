<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user'];

/* GET BUY NOW PRODUCT */

$cart = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "SELECT c.*, p.name, p.image,
                p.discount_price, p.price
         FROM cart c
         JOIN products p
         ON c.product_id = p.id
         WHERE c.user_id='$user_id'
         AND c.buy_now='1'
         LIMIT 1"
    )
);

if(!$cart){
    echo "No product found";
    exit();
}

$total =
$cart['discount_price'] * $cart['qty'];
?>

<!DOCTYPE html>
<html>
<head>

<title>Buy Now</title>

<style>

body{
    font-family:Arial;
    background:#f5f5f5;
    margin:0;
}

.container{
    max-width:900px;
    margin:40px auto;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

.product{
    display:flex;
    gap:25px;
    align-items:center;
}

.product img{
    width:220px;
    border-radius:12px;
}

.info{
    flex:1;
}

.price{
    font-size:28px;
    font-weight:bold;
    color:#7c3aed;
    margin-top:10px;
}

.old{
    text-decoration:line-through;
    color:#777;
    font-size:16px;
    margin-left:10px;
}

.extra{
    margin-top:15px;
    color:#555;
    line-height:28px;
}

button{
    margin-top:25px;
    width:100%;
    padding:16px;
    border:none;
    background:#7c3aed;
    color:white;
    font-size:18px;
    border-radius:12px;
    cursor:pointer;
    font-weight:bold;
}

button:hover{
    opacity:0.9;
}

</style>

</head>

<body>

<div class="container">

    <h2>⚡ Confirm Your Order</h2>

    <div class="product">

        <img src="<?php echo $cart['image']; ?>">

        <div class="info">

            <h2>
                <?php echo $cart['name']; ?>
            </h2>

            <div class="price">

                ₹<?php echo $cart['discount_price']; ?>

                <span class="old">
                    ₹<?php echo $cart['price']; ?>
                </span>

            </div>

            <div class="extra">

                <p>
                    <b>Size:</b>
                    <?php echo $cart['size']; ?>
                </p>

                <p>
                    <b>Quantity:</b>
                    <?php echo $cart['qty']; ?>
                </p>

                <p>
                    <b>Delivery Address:</b><br>
                    <?php echo $cart['address']; ?>
                </p>

                <p>
                    <b>Total:</b>
                    ₹<?php echo $total; ?>
                </p>

            </div>

        </div>

    </div>

    <form method="POST" action="place_order.php">

        <input
        type="hidden"
        name="buy_now"
        value="1">

        <button type="submit">
            Place Order 🛍
        </button>

    </form>

</div>

</body>
</html>