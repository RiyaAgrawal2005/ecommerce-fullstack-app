<?php
session_start();
include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
$user = mysqli_fetch_assoc($result);

if($user && password_verify($password, $user['password'])){
    
    // ✅ FIX HERE
    $_SESSION['user'] = $user['id'];

    header("Location: user_dashboard.php");
    exit();

} else {
    echo "Invalid credentials!";
}
?>