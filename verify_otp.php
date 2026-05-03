<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
</head>
<body>

<h2>Enter OTP</h2>

<form action="verify_signup.php" method="POST">
    <input type="text" name="otp" placeholder="Enter OTP" required>
    <button type="submit">Verify & Signup</button>
    <p>OTP valid for 2 minutes</p>
</form>
<form action="resend_otp.php" method="POST">
    <button type="submit">Resend OTP</button>

</form>



</body>
</html>