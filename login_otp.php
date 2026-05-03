<?php
session_start();
include 'db.php';

$email = $_POST['email'];

$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE email='$email'"));

if(!$user){
    echo "User not found";
    exit();
}

$otp = rand(100000,999999);

$_SESSION['login_otp'] = $otp;
$_SESSION['login_email'] = $email;

// send email
// mail($email, "Login OTP", "Your OTP: $otp");
include 'send_mail.php';
sendOTP($email, $otp);

header("Location: verify_login.php");
?>