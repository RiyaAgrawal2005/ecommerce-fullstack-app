<?php
session_start();

$_SESSION['otp_time'] = time();

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$gender = $_POST['gender'];

include 'db.php';

$check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

if(mysqli_num_rows($check) > 0){
    echo "Email already registered ❌";
    exit();
}

$otp = rand(100000,999999);

// store in session
$_SESSION['signup_data'] = [
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'gender' => $gender,
    'otp' => $otp
];

// SEND EMAIL (simple mail)
$subject = "Your OTP Code";
$message = "Your OTP is: $otp";
$headers = "From: no-reply@yourapp.com";

// mail($email, $subject, $message, $headers);
include 'send_mail.php';

sendOTP($email, $otp);

header("Location: verify_otp.php");
?>