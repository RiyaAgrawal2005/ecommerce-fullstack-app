<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sendOTP($email, $otp){

    $mail = new PHPMailer(true);

    try{
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;

        // 🔴 YOUR EMAIL
        $mail->Username   = '1234riyaagrawal@gmail.com';

        // 🔴 APP PASSWORD (NOT normal password)
        $mail->Password   = 'lulcojjsqcznvwcu';

        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('1234riyaagrawal@gmail.com', 'ShopEasy');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "<h3>Your OTP is: $otp</h3>";

        $mail->send();
        return true;

    }catch(Exception $e){
        return false;
    }
}