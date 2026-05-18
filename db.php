






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
    "sql212.infinityfree.com",
    "if0_41834050",
    "UXA9wy7hFLG",
    "if0_41834050_ecommerce"
);
}

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>