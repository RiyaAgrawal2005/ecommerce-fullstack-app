<?php 
session_start();
include 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user'];

$user = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT address FROM users WHERE id='$user_id'"
    )
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial,sans-serif;
        }

        body{
            background:#f5f5f5;
        }

        .box{
            width:60%;
            margin:30px auto;
            background:#fff;
            padding:25px;
            border-radius:12px;
            box-shadow:0 3px 10px rgba(0,0,0,0.1);
        }

        h2{
            margin-bottom:20px;
        }

        h3{
            margin-top:20px;
            margin-bottom:10px;
        }

        .item{
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:15px 0;
            border-bottom:1px solid #eee;
        }

        .item span{
            font-size:15px;
        }

        #total{
            margin-top:20px;
            color:#111827;
        }

        button{
            margin-top:20px;
            padding:12px 25px;
            background:#7c3aed;
            color:white;
            border:none;
            border-radius:8px;
            cursor:pointer;
            font-size:15px;
            font-weight:bold;
        }

        button:hover{
            opacity:0.9;
        }

        #address{
            background:#f9fafb;
            padding:12px;
            border-radius:8px;
            line-height:1.5;
        }

        @media(max-width:768px){

            .box{
                width:95%;
            }

            .item{
                flex-direction:column;
                align-items:flex-start;
                gap:8px;
            }
        }

    </style>
</head>

<body>

<div class="box">

    <h2>🧾 Checkout (Review Order)</h2>

    <!-- ADDRESS -->

    <h3>📍 Delivery Address</h3>

    <p id="address"></p>

    <button onclick="changeAddress()">
        Change Address
    </button>

    <!-- ITEMS -->

    <h3>🛒 Order Items</h3>

    <div id="items"></div>

    <!-- TOTAL -->

    <h3 id="total"></h3>

    <!-- PAYMENT -->

    <button onclick="goPayment()">
        Proceed to Payment 💳
    </button>

</div>

<script>

/* ================= CHANGE ADDRESS ================= */

function changeAddress(){

    let newAddress =
    prompt("Enter new address:");

    if(newAddress){

        localStorage.setItem(
            "address",
            newAddress
        );

        document.getElementById("address")
        .innerText = newAddress;

        /* SAVE ADDRESS IN DATABASE */

        fetch("update_address.php", {

            method:"POST",

            headers:{
                "Content-Type":
                "application/x-www-form-urlencoded"
            },

            body:
            "address=" +
            encodeURIComponent(newAddress)
        });
    }
}

/* ================= PRODUCTS ================= */

let products = <?php

$result = mysqli_query(
    $conn,
    "SELECT * FROM products"
);

$data = [];

while($row = mysqli_fetch_assoc($result)){

    $data[] = $row;
}

echo json_encode($data);

?>;

/* ================= GET URL TYPE ================= */

let urlParams =
new URLSearchParams(window.location.search);

let type = urlParams.get("type");

/* ================= LOCAL STORAGE DATA ================= */

let cart =
JSON.parse(localStorage.getItem("cart")) || [];

let buyNow =
JSON.parse(localStorage.getItem("buyNow"));

/* ================= ADDRESS ================= */

let dbAddress =
`<?php echo $user['address']; ?>`;

let address =
localStorage.getItem("address")
|| dbAddress;

document.getElementById("address")
.innerText =
address || "No address found";

/* ================= ITEMS CONTAINER ================= */

let total = 0;

let container =
document.getElementById("items");

/* =====================================================
   BUY NOW FLOW
===================================================== */

if(type === "buyNow" && buyNow){

    let p = products.find(x =>
        Number(x.id) === Number(buyNow.id)
    );

    if(p){

        let qty = buyNow.qty || 1;

        let price =
        Number(p.discount_price || p.price);

        total += price * qty;

        container.innerHTML += `

            <div class="item">

                <span>
                    ${p.name}
                    (${buyNow.size})
                    x ${qty}
                </span>

                <span>
                    ₹${price * qty}
                </span>

            </div>

        `;

        /* FINAL ORDER DATA */

        cart = [{

            id: buyNow.id,

            size: buyNow.size,

            qty: qty

        }];
    }

}

/* =====================================================
   NORMAL CART FLOW
===================================================== */

else{

    if(cart.length === 0){

        container.innerHTML = `
            <p>Your cart is empty 🛒</p>
        `;
    }

    cart.forEach(item => {

        let p = products.find(x =>
            Number(x.id) === Number(item.id)
        );

        if(p){

            let qty = item.qty || 1;

            let price =
            Number(p.discount_price || p.price);

            total += price * qty;

            container.innerHTML += `

                <div class="item">

                    <span>
                        ${p.name}
                        (${item.size})
                        x ${qty}
                    </span>

                    <span>
                        ₹${price * qty}
                    </span>

                </div>

            `;
        }

    });

}

/* ================= TOTAL ================= */

document.getElementById("total")
.innerText =
"Total: ₹" + total;

/* ================= PAYMENT ================= */

function goPayment(){

    if(total <= 0){

        alert("Cart is empty!");
        return;
    }

    localStorage.setItem(
        "finalTotal",
        total
    );

    localStorage.setItem(
        "finalCart",
        JSON.stringify(cart)
    );

    window.location.href =
    "payment.php";
}

</script>

</body>
</html>