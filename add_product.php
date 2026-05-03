<?php
include 'db.php';

$name = $_POST['name'];
$description = $_POST['description'];
$category = $_POST['category'];
$price = $_POST['price'];
$discount_price = $_POST['discount_price'];
$stock = $_POST['stock'];
$rating = $_POST['rating'];
$image = $_POST['image'];

$sql = "INSERT INTO products 
(name, description, category, price, discount_price, stock, rating, image) 
VALUES 
('$name', '$description', '$category', '$price', '$discount_price', '$stock', '$rating', '$image')";

if(mysqli_query($conn, $sql)){
    echo "<script>alert('Product Added Successfully'); window.location='admin.php';</script>";
}else{
    echo "Error: " . mysqli_error($conn);
}
?>