<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>

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

        .login-box{
            width:350px;
            background:#fff;
            padding:35px;
            border-radius:12px;
            box-shadow:0 0 15px rgba(0,0,0,0.3);
            text-align:center;
        }

        .login-box h2{
            margin-bottom:25px;
            color:#333;
        }

        .login-box input{
            width:100%;
            padding:12px;
            margin:10px 0;
            border:1px solid #ccc;
            border-radius:6px;
            font-size:15px;
            outline:none;
            transition:0.3s;
        }

        .login-box input:focus{
            border-color:#007bff;
            box-shadow:0 0 5px rgba(0,123,255,0.4);
        }

        .login-box button{
            width:100%;
            padding:12px;
            background:#007bff;
            color:white;
            border:none;
            border-radius:6px;
            font-size:16px;
            cursor:pointer;
            margin-top:10px;
            transition:0.3s;
        }

        .login-box button:hover{
            background:#0056b3;
        }

        .error{
            color:red;
            margin-top:15px;
            font-size:14px;
        }
    </style>
</head>
<body>

<div class="login-box">

<form method="POST">

    <h2>Admin Login</h2>

    <input type="text" name="username" placeholder="Username" required>

    <input type="password" name="password" placeholder="Password" required>

    <button name="login">Login</button>

</form>

<?php
if(isset($_POST['login'])){

    if($_POST['username'] == "admin" && $_POST['password'] == "admin1234"){

        $_SESSION['admin'] = true;

        header("Location: admin_dashboard.php");

    } else {

        echo "<p class='error'>Invalid Login</p>";
    }
}
?>

</div>

</body>
</html>