<?php
session_start();
include 'db.php';

$userOtp = $_POST['otp'];
if(!isset($_SESSION['signup_data'])){
    echo "Session expired. Please signup again.";
    exit();
}

$data = $_SESSION['signup_data'];

if(time() - $_SESSION['otp_time'] > 120){
    echo "OTP Expired ❌";
    exit();
}

if($userOtp == $data['otp']){

    // mysqli_query($conn, "INSERT INTO users (name,email,phone,gender,is_verified)
    // VALUES (
    //     '{$data['name']}',
    //     '{$data['email']}',
    //     '{$data['phone']}',
    //     '{$data['gender']}',
    //     1
    // )");

    $name = mysqli_real_escape_string($conn, $data['name']);
$email = mysqli_real_escape_string($conn, $data['email']);
$phone = mysqli_real_escape_string($conn, $data['phone']);
$gender = mysqli_real_escape_string($conn, $data['gender']);

mysqli_query($conn, "INSERT INTO users (name,email,phone,gender,is_verified)
VALUES ('$name','$email','$phone','$gender',1)");

    // LOGIN USER
    $user = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT * FROM users WHERE email='{$data['email']}'")
    );

    $_SESSION['user'] = $user['id'];

    header("Location: user_dashboard.php");

}else{
    echo "❌ Invalid OTP";
}
?>