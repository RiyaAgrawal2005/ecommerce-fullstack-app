<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user'];
$address = $_POST['address'];

mysqli_query($conn, "
UPDATE users SET address='$address' WHERE id='$user_id'
");

header("Location: profile.php");
?>