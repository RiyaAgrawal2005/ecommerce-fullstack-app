<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Delivery Address</title>

    <style>
        body{
            font-family: Arial;
            background:#f5f5f5;
        }

        .container{
            width:400px;
            margin:50px auto;
            background:white;
            padding:25px;
            border-radius:10px;
            box-shadow:0 0 10px #ccc;
        }

        h2{
            text-align:center;
        }

        input, textarea{
            width:100%;
            padding:10px;
            margin:10px 0;
            border-radius:5px;
            border:1px solid #ccc;
        }

        button{
            width:100%;
            padding:12px;
            background:#ff5722;
            color:white;
            border:none;
            border-radius:5px;
            cursor:pointer;
            font-size:16px;
        }

        button:hover{
            background:#e64a19;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📦 Delivery Address</h2>

    <form action="payment.php" method="POST">

        <input type="text" name="name" placeholder="Full Name" required>

        <input type="text" name="phone" placeholder="Mobile Number" required pattern="[0-9]{10}">

        <textarea name="address" placeholder="Full Address" required></textarea>

        <input type="text" name="city" placeholder="City" required>

        <input type="text" name="pincode" placeholder="Pincode" required pattern="[0-9]{6}">

        <!-- Hidden total -->
        <input type="hidden" name="total" id="totalInput">

        <button type="submit">Continue to Payment ➡️</button>
    </form>
</div>

<script>
// get total from localStorage
let total = localStorage.getItem("finalTotal") || 0;
document.getElementById("totalInput").value = total;
</script>

</body>
</html>