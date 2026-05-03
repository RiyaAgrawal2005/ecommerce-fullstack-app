<?php
include 'db.php';

$name = trim($_POST['name']);
$image = trim($_POST['image']);

if(empty($name) || empty($image)){
    echo "All fields required ❌";
    exit();
}

// allow URL OR base64
if(
    !filter_var($image, FILTER_VALIDATE_URL) && 
    strpos($image, 'data:image') !== 0
){
    echo "Enter valid Image URL or base64 ❌";
    exit();
}

// insert safely
mysqli_query($conn, "
    INSERT INTO categories(name, image) 
    VALUES('$name', '$image')
");

header("Location: admin_categories.php");
?>