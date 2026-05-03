<?php
session_start();
include 'send_mail.php';

if(!isset($_SESSION['signup_data'])){
    echo "Session expired. Please signup again.";
    exit();
}

// ✅ RESET TIME HERE (THIS WAS MISSING)
$_SESSION['otp_time'] = time();

$data = $_SESSION['signup_data'];

$otp = rand(100000,999999);

// update OTP
$_SESSION['signup_data']['otp'] = $otp;

// send again
sendOTP($data['email'], $otp);

header("Location: verify_otp.php");
?>