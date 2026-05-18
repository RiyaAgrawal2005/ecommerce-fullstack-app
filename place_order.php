<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user'];

/* =========================
   GET USER DETAILS
========================= */

$user_query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE id='$user_id'"
);

$user = mysqli_fetch_assoc($user_query);

/* USER DATA */

$name = $user['name'];
$phone = $user['phone'];
$address = $user['address'];
$city = $user['city'];
$pincode = $user['pincode'];

/* =========================
   PAYMENT DETAILS
========================= */

$payment_method =
mysqli_real_escape_string(
    $conn,
    $_POST['payment_method']
);

$payment_id =
$_POST['payment_id'] ?? 'COD';

$payment_status =
($payment_method == "Cash on Delivery")
? "Pending"
: "Paid";

$order_status = "Pending";

/* =========================
   GET CART FROM LOCALSTORAGE
========================= */

$cart = json_decode($_POST['cart'], true);

if(!$cart || count($cart) == 0){

    die("Cart is empty");
}

/* =========================
   CALCULATE TOTAL
========================= */

$total = 0;

foreach($cart as $item){

    $product_id = $item['id'];

    $product_query = mysqli_query(
        $conn,
        "SELECT * FROM products
         WHERE id='$product_id'"
    );

    $product = mysqli_fetch_assoc($product_query);

    $price = $product['discount_price'];
    $qty = $item['qty'];

    $total += ($price * $qty);
}

/* =========================
   INSERT ORDER
========================= */

mysqli_query(

    $conn,

    "INSERT INTO orders
    (
        user_id,
        customer_name,
        phone,
        total,
        payment_method,
        payment_id,
        payment_status,
        status,
        address,
        city,
        pincode
    )

    VALUES
    (
        '$user_id',
        '$name',
        '$phone',
        '$total',
        '$payment_method',
        '$payment_id',
        '$payment_status',
        '$order_status',
        '$address',
        '$city',
        '$pincode'
    )"
);

$order_id = mysqli_insert_id($conn);

/* =========================
   INSERT ORDER ITEMS
========================= */

foreach($cart as $item){

    $product_id = $item['id'];

    $size = $item['size'];

    $qty = $item['qty'];

    mysqli_query(

        $conn,

        "INSERT INTO order_items
        (
            order_id,
            product_id,
            size,
            qty
        )

        VALUES
        (
            '$order_id',
            '$product_id',
            '$size',
            '$qty'
        )"
    );
}

/* =========================
   SUCCESS
========================= */

header(
    "Location: order_success.php?id=$order_id"
);

exit();
?>