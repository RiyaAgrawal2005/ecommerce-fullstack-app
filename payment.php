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

    <!-- ORDER SUMMARY -->
    <div class="summary">
        <h3 id="showTotal"></h3>
    </div>

    <!-- PAYMENT FORM -->

    <form action="place_order.php" method="POST" id="paymentForm">

        <!-- USER DETAILS -->

        <input type="hidden" name="name"
        value="<?php echo $_POST['name'] ?? ''; ?>">

        <input type="hidden" name="phone"
        value="<?php echo $_POST['phone'] ?? ''; ?>">

        <input type="hidden" name="address"
        value="<?php echo $_POST['address'] ?? ''; ?>">

        <input type="hidden" name="city"
        value="<?php echo $_POST['city'] ?? ''; ?>">

        <input type="hidden" name="pincode"
        value="<?php echo $_POST['pincode'] ?? ''; ?>">

        <!-- TOTAL -->
        <input type="hidden" name="total" id="hiddenTotal">

        <!-- CART -->
        <input type="hidden" name="cart" id="cartData">

        <!-- PAYMENT METHOD -->

        <label>Select Payment Method</label>

        <select name="payment_method" id="paymentMethod" required>

            <option value="">-- Select --</option>

            <option value="Cash on Delivery">
                Cash on Delivery
            </option>

            <option value="UPI">
                UPI
            </option>

            <option value="Debit/Credit Card">
                Debit/Credit Card
            </option>

        </select>

        <button type="submit">
            Place Order ✅
        </button>

    </form>

</div>

<!-- RAZORPAY -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>

/* =========================
   LOAD TOTAL
========================= */

let total =
localStorage.getItem("finalTotal") || 0;

document.getElementById("showTotal")
.innerText = "Total Amount: ₹" + total;

document.getElementById("hiddenTotal")
.value = total;


/* =========================
   LOAD CART
========================= */

let finalCart =
localStorage.getItem("finalCart");

let buyNow =
localStorage.getItem("buyNow");

/* BUY NOW */
if(buyNow){

    finalCart =
    JSON.stringify([JSON.parse(buyNow)]);
}

/* SAVE CART */
document.getElementById("cartData")
.value = finalCart || "[]";


/* =========================
   PAYMENT SUBMIT
========================= */

document.getElementById("paymentForm")
.addEventListener("submit", function(e){

    let method =
    document.getElementById("paymentMethod").value;

    /* NO METHOD */

    if(method === ""){

        alert("Please select payment method");

        e.preventDefault();

        return;
    }

    /* CASH ON DELIVERY */

    if(method === "Cash on Delivery"){

        return true;
    }

    /* ONLINE PAYMENT */

    e.preventDefault();

    var options = {

        "key": "rzp_test_Sol65Yamt4xPaw",

        "amount": total * 100,

        "currency": "INR",

        "name": "Riya Ecommerce",

        "description": "Order Payment",

        "method": {
            "upi": true,
            "card": true,
            "netbanking": true,
            "wallet": true
        },

        "handler": function (response){

            alert("✅ Payment Successful!");

            /* CREATE PAYMENT INPUT */

            let input =
            document.createElement("input");

            input.type = "hidden";

            input.name = "payment_id";

            input.value =
            response.razorpay_payment_id;

            document
            .getElementById("paymentForm")
            .appendChild(input);

            /* SUBMIT FORM */

            document
            .getElementById("paymentForm")
            .submit();
        },

        "theme": {
            "color": "#3399cc"
        }
    };

    var rzp1 = new Razorpay(options);

    rzp1.open();

});

</script>

</body>
</html>