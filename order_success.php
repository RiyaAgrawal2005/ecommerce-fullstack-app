<!DOCTYPE html>
<html>
<head>
    <title>Order Placed</title>

    <style>

        body{
            font-family:Arial;
            background:#f5f5f5;
        }

        .box{
            width:400px;
            margin:100px auto;
            background:white;
            padding:30px;
            text-align:center;
            border-radius:10px;
            box-shadow:0 0 10px #ccc;
        }

        h1{
            color:green;
        }

        a{
            display:inline-block;
            margin-top:20px;
            padding:12px 20px;
            background:#007bff;
            color:white;
            text-decoration:none;
            border-radius:6px;
        }

    </style>
</head>
<body>

<div class="box">

<h1>✅ Order Placed Successfully!</h1>

<p>
Thank you for shopping with us.
Your order has been placed successfully.
</p>

<a href="user_dashboard.php">
Continue Shopping
</a>

</div>

<script>

// clear cart after success
localStorage.removeItem("cart");
localStorage.removeItem("buyNow");
localStorage.removeItem("finalCart");
localStorage.removeItem("finalTotal");

</script>

</body>
</html>