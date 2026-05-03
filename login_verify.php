<?php
session_start();
include 'db.php';

if($_POST['otp'] == $_SESSION['login_otp']){

    $email = $_SESSION['login_email'];

    $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE email='$email'"));

    $_SESSION['user'] = $user['id'];

    header("Location: user_dashboard.php");

}else{
    echo "Wrong OTP";
}
?>