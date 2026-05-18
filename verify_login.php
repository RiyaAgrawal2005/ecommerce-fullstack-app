<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family: Arial, sans-serif;
        }

        body{
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:linear-gradient(to right, #141e30, #243b55);
        }

        .otp-box{
            width:350px;
            background:white;
            padding:35px;
            border-radius:12px;
            box-shadow:0 0 15px rgba(0,0,0,0.3);
            text-align:center;
        }

        .otp-box h2{
            margin-bottom:20px;
            color:#333;
        }

        .otp-box p{
            color:gray;
            margin-bottom:20px;
            font-size:14px;
        }

        .otp-box input{
            width:100%;
            padding:12px;
            border:1px solid #ccc;
            border-radius:6px;
            font-size:15px;
            margin-bottom:15px;
            outline:none;
            transition:0.3s;
        }

        .otp-box input:focus{
            border-color:#007bff;
            box-shadow:0 0 5px rgba(0,123,255,0.4);
        }

        .otp-box button{
            width:100%;
            padding:12px;
            background:#007bff;
            color:white;
            border:none;
            border-radius:6px;
            font-size:16px;
            cursor:pointer;
            transition:0.3s;
        }

        .otp-box button:hover{
            background:#0056b3;
        }
    </style>
</head>
<body>

<div class="otp-box">

    <h2>OTP Verification</h2>

    <p>Enter the OTP sent to your email/mobile</p>

    <form action="login_verify.php" method="POST">

        <input type="text" name="otp" placeholder="Enter OTP" required>

        <button>Login</button>

    </form>

</div>

</body>
</html>