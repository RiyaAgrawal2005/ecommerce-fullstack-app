
<?php 
session_start();
include 'db.php';

$user_id = $_SESSION['user'];

$user = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT address FROM users WHERE id='$user_id'")
);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>

    <style>
        body{font-family: Arial; background:#f5f5f5;}
        .box{
            width:60%;
            margin:30px auto;
            background:#fff;
            padding:20px;
            border-radius:10px;
        }
        .item{
            display:flex;
            justify-content:space-between;
            padding:10px 0;
            border-bottom:1px solid #eee;
        }
        button{
            margin-top:20px;
            padding:12px 25px;
            background:#007bff;
            color:white;
            border:none;
            border-radius:6px;
            cursor:pointer;
        }
    </style>
</head>
<body>

<div class="box">

<h2>🧾 Checkout (Review Order)</h2>

<!-- <h3>📍 Address:</h3>
<p id="address"></p> -->

<h3>📍 Address:</h3>
<p id="address"></p>

<button onclick="changeAddress()">Change Address</button>

<div id="items"></div>
<h3 id="total"></h3>

<button onclick="goPayment()">Proceed to Payment 💳</button>

</div>

<script>


function changeAddress(){
    let newAddress = prompt("Enter new address:");

    if(newAddress){

        localStorage.setItem("address", newAddress);
        document.getElementById("address").innerText = newAddress;

        // SAVE TO DB
        fetch("update_address.php", {
            method: "POST",
            headers: {"Content-Type":"application/x-www-form-urlencoded"},
            body: "address=" + encodeURIComponent(newAddress)
        });
    }
}

// 📦 Products
let products = <?php
include 'db.php';
$result = mysqli_query($conn, "SELECT * FROM products");
$data = [];
while($row = mysqli_fetch_assoc($result)){
    $data[] = $row;
}
echo json_encode($data);
?>;

// 🛒 Data
let cart = JSON.parse(localStorage.getItem("cart")) || [];
let buyNow = JSON.parse(localStorage.getItem("buyNow"));
// let address = localStorage.getItem("address");

// document.getElementById("address").innerText = address || "No address found";

let dbAddress = `<?php echo $user['address']; ?>`;
let address = localStorage.getItem("address") || dbAddress;

document.getElementById("address").innerText = address || "No address found";


let total = 0;
let container = document.getElementById("items");

// ⚡ BUY NOW
if(buyNow){
    let p = products.find(x => Number(x.id) === Number(buyNow.id));

    if(p){
        let price = Number(p.discount_price || p.price);
        total += price;

        container.innerHTML += `
            <div class="item">
                <span>${p.name} (${buyNow.size})</span>
                <span>₹${price}</span>
            </div>
        `;

        cart = [{
            id: buyNow.id,
            size: buyNow.size,
            qty: 1
        }];
    }
}

// 🛒 CART
else{
    cart.forEach(item => {
        let p = products.find(x => Number(x.id) === Number(item.id));

        if(p){
            let price = Number(p.discount_price || p.price);
            total += price * item.qty;

            container.innerHTML += `
                <div class="item">
                    <span>${p.name} (${item.size}) x ${item.qty}</span>
                    <span>₹${price * item.qty}</span>
                </div>
            `;
        }
    });
}

// 💰 Total
document.getElementById("total").innerText = "Total: ₹" + total;

// 👉 GO TO PAYMENT
function goPayment(){
    localStorage.setItem("finalTotal", total);
    localStorage.setItem("finalCart", JSON.stringify(cart));

    window.location.href = "payment.php";
}

</script>

</body>
</html>