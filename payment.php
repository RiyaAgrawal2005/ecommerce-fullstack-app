<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>

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

        .summary{
            background:#fafafa;
            padding:15px;
            border-radius:5px;
            margin-bottom:15px;
        }

        select, button{
            width:100%;
            padding:10px;
            margin-top:10px;
        }

        button{
            background:#28a745;
            color:white;
            border:none;
            border-radius:5px;
            cursor:pointer;
            font-size:16px;
        }

        button:hover{
            background:#218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>💳 Payment</h2>

    <!-- Order Summary -->
    <div class="summary">
        <h3>Total Amount: ₹<?php echo $_POST['total']; ?></h3>
    </div>

    <form action="place_order.php" method="POST">

        <!-- Pass all data forward -->
        <input type="hidden" name="name" value="<?php echo $_POST['name']; ?>">
        <input type="hidden" name="phone" value="<?php echo $_POST['phone']; ?>">
        <input type="hidden" name="address" value="<?php echo $_POST['address']; ?>">
        <input type="hidden" name="city" value="<?php echo $_POST['city']; ?>">
        <input type="hidden" name="pincode" value="<?php echo $_POST['pincode']; ?>">
        <input type="hidden" name="total" value="<?php echo $_POST['total']; ?>">
        
        <input type="hidden" name="cart" id="cartData">
        <label>Select Payment Method</label>

        <select name="payment_method" required>
            <option value="">-- Select --</option>
            <option>Cash on Delivery</option>
            <option>UPI</option>
            <option>Debit/Credit Card</option>
        </select>

        <button type="submit">Place Order ✅</button>
    </form>
</div>


<script>
let cart = localStorage.getItem("cart");

// also handle Buy Now
let buyNow = localStorage.getItem("buyNow");

if(buyNow){
    cart = JSON.stringify([JSON.parse(buyNow)]);
}

document.getElementById("cartData").value = cart;
</script>

</body>
</html>