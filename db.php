<?php

$server = $_SERVER['HTTP_HOST'];

if ($server == "localhost") {

    // Localhost Database

    $conn = mysqli_connect(
        "localhost",
        "root",
        "",
        "ecommerce"
    );

} else {

    // Live Hosting Database

    $conn = mysqli_connect(
        "YOUR_HOST",
        "YOUR_USERNAME",
        "YOUR_PASSWORD",
        "YOUR_DATABASE"
    );
}

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
