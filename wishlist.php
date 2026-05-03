<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user'];
$product_id = $_POST['product_id'];

// prevent duplicate
$check = mysqli_query($conn, 
    "SELECT * FROM wishlist WHERE user_id='$user_id' AND product_id='$product_id'"
);

if(mysqli_num_rows($check) == 0){
    mysqli_query($conn, 
        "INSERT INTO wishlist (user_id, product_id) VALUES ('$user_id', '$product_id')"
    );
}

echo "added";
?>