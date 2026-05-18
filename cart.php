<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Shopping Cart</title>

<link rel="stylesheet" href="style.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background:#f5f7fb;
    color:#111827;
}

/* ================= HEADER ================= */

header{
    background:linear-gradient(135deg,#7c3aed,#4f46e5);
    padding:18px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    color:white;
    position:sticky;
    top:0;
    z-index:100;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

header h1{
    font-size:28px;
}

nav{
    display:flex;
    gap:15px;
}

nav a{
    color:white;
    text-decoration:none;
    font-weight:500;
    padding:10px 16px;
    border-radius:12px;
    transition:0.3s;
}

nav a:hover{
    background:rgba(255,255,255,0.15);
}

/* ================= PAGE ================= */

.wrapper{
    width:95%;
    max-width:1400px;
    margin:30px auto;
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:25px;
}

/* ================= CART ITEMS ================= */

.left{
    display:flex;
    flex-direction:column;
    gap:20px;
}

.cart-card{
    background:white;
    border-radius:20px;
    padding:20px;
    display:flex;
    gap:20px;
    box-shadow:0 5px 18px rgba(0,0,0,0.07);
    transition:0.3s;
}

.cart-card:hover{
    transform:translateY(-4px);
}

.product-img{
    width:150px;
    height:150px;
    background:#f9fafb;
    border-radius:18px;
    overflow:hidden;
    flex-shrink:0;
}

.product-img img{
    width:100%;
    height:100%;
    object-fit:cover;
}

/* ================= PRODUCT INFO ================= */

.product-info{
    flex:1;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
}

.top{
    display:flex;
    justify-content:space-between;
    gap:15px;
}

.top h2{
    font-size:20px;
    margin-bottom:8px;
    color:#111827;
}

.category{
    color:#7c3aed;
    font-size:13px;
    font-weight:600;
    margin-bottom:8px;
    text-transform:uppercase;
}

.desc{
    color:#6b7280;
    font-size:14px;
    line-height:1.5;
}

/* ================= PRICE ================= */

.price-box{
    margin-top:12px;
}

.price{
    font-size:24px;
    font-weight:700;
}

.old-price{
    margin-left:10px;
    text-decoration:line-through;
    color:#9ca3af;
    font-size:14px;
}

.discount{
    color:#16a34a;
    font-weight:700;
    margin-left:10px;
    font-size:14px;
}

/* ================= ACTIONS ================= */

.bottom{
    margin-top:18px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:15px;
}

/* QUANTITY */

.qty-box{
    display:flex;
    align-items:center;
    gap:10px;
}

.qty-btn{
    width:35px;
    height:35px;
    border:none;
    border-radius:10px;
    background:#ede9fe;
    color:#5b21b6;
    font-size:18px;
    cursor:pointer;
    font-weight:700;
}

.qty{
    font-size:16px;
    font-weight:600;
    width:30px;
    text-align:center;
}

/* BUTTONS */

.action-buttons{
    display:flex;
    gap:12px;
}

.btn{
    border:none;
    padding:12px 18px;
    border-radius:12px;
    cursor:pointer;
    font-weight:600;
    transition:0.3s;
}

.remove-btn{
    background:#fee2e2;
    color:#dc2626;
}

.remove-btn:hover{
    background:#fecaca;
}

.save-btn{
    background:#e0f2fe;
    color:#0284c7;
}

.save-btn:hover{
    background:#bae6fd;
}

/* ================= SUMMARY ================= */

.summary{
    background:white;
    border-radius:22px;
    padding:25px;
    box-shadow:0 5px 18px rgba(0,0,0,0.07);
    height:fit-content;
    position:sticky;
    top:100px;
}

.summary h2{
    margin-bottom:25px;
    font-size:24px;
}

.summary-row{
    display:flex;
    justify-content:space-between;
    margin-bottom:18px;
    font-size:15px;
}

.summary-row.total{
    border-top:1px solid #e5e7eb;
    padding-top:18px;
    margin-top:18px;
    font-size:20px;
    font-weight:700;
}

.green{
    color:#16a34a;
    font-weight:700;
}

/* COUPON */

.coupon{
    margin:20px 0;
    display:flex;
    gap:10px;
}

.coupon input{
    flex:1;
    padding:12px;
    border:1px solid #ddd;
    border-radius:12px;
    outline:none;
}

.apply-btn{
    padding:12px 18px;
    border:none;
    border-radius:12px;
    background:#111827;
    color:white;
    cursor:pointer;
    font-weight:600;
}

/* CHECKOUT */

.checkout-btn{
    width:100%;
    padding:16px;
    border:none;
    border-radius:14px;
    background:linear-gradient(135deg,#7c3aed,#4f46e5);
    color:white;
    font-size:17px;
    font-weight:700;
    cursor:pointer;
    transition:0.3s;
}

.checkout-btn:hover{
    transform:translateY(-2px);
}

/* EMPTY CART */

.empty{
    background:white;
    padding:60px 20px;
    border-radius:22px;
    text-align:center;
    box-shadow:0 5px 18px rgba(0,0,0,0.07);
}

.empty h2{
    margin-top:20px;
    font-size:28px;
}

.empty p{
    margin-top:10px;
    color:#6b7280;
}

.empty a{
    display:inline-block;
    margin-top:25px;
    background:#7c3aed;
    color:white;
    text-decoration:none;
    padding:14px 24px;
    border-radius:14px;
    font-weight:600;
}

/* RESPONSIVE */

@media(max-width:992px){

    .wrapper{
        grid-template-columns:1fr;
    }

    .summary{
        position:static;
    }
}

@media(max-width:768px){

    header{
        padding:18px 20px;
        flex-direction:column;
        gap:15px;
    }

    .cart-card{
        flex-direction:column;
    }

    .product-img{
        width:100%;
        height:240px;
    }

    .bottom{
        flex-direction:column;
        align-items:flex-start;
    }
}

</style>
</head>

<body>

<!-- ================= HEADER ================= -->

<header>

    <h1>🛒 Shopping Cart</h1>

    <nav>
        <a href="user_dashboard.php">🏠 Home</a>
        <a href="wishlist_page.php">❤️ Wishlist</a>
        <a href="orders.php">📦 Orders</a>
        <a href="profile.php">👤 Profile</a>
    </nav>

</header>

<!-- ================= MAIN ================= -->

<div class="wrapper">

    <!-- LEFT -->
    <div class="left" id="cart-items">

        <!-- CART ITEMS LOAD HERE -->

    </div>

    <!-- RIGHT -->
    <div class="summary">

        <h2>Order Summary</h2>

        <div class="summary-row">
            <span>Subtotal</span>
            <span id="subtotal">₹0</span>
        </div>

        <div class="summary-row">
            <span>Delivery</span>
            <span class="green">FREE</span>
        </div>

        <div class="summary-row">
            <span>Discount</span>
            <span class="green" id="discount">- ₹0</span>
        </div>

        <!-- COUPON -->

        <div class="coupon">

            <input type="text" placeholder="Enter Coupon">

            <button class="apply-btn">
                Apply
            </button>

        </div>

        <div class="summary-row total">
            <span>Total</span>
            <span id="total">₹0</span>
        </div>

        <form action="checkout.php" method="POST">

            <input type="hidden" name="total" id="totalInput">

            <button type="button" onclick="goAddress()" class="checkout-btn">
                Proceed to Checkout ➡️
            </button>

        </form>

    </div>

</div>




<script>

function goAddress(){
    window.location.href = "address.php";
}

let products = <?php
include 'db.php';

$result = mysqli_query($conn, "SELECT * FROM products");

$data = [];

while($row = mysqli_fetch_assoc($result)){
    $data[] = $row;
}

echo json_encode($data);
?>;

/* ================= CART LOGIC ================= */

let cart = JSON.parse(localStorage.getItem("cart")) || [];

let container = document.getElementById("cart-items");

/* ORIGINAL PRICE TOTAL */
let subtotal = 0;

/* DISCOUNT TOTAL */
let totalDiscount = 0;

/* FINAL PRICE */
let finalTotal = 0;

if(cart.length === 0){

    container.innerHTML = `
    
        <div class="empty">

            <div style="font-size:80px;">🛒</div>

            <h2>Your Cart is Empty</h2>

            <p>Add products to your cart and start shopping.</p>

            <a href="user_dashboard.php">
                Continue Shopping
            </a>

        </div>

    `;

}else{

    cart.forEach((item,index)=>{

        let product = products.find(p => p.id == item.id);

        if(product){

            let qty = item.qty || 1;

            /* ORIGINAL PRICE */
            let originalPrice =
            Number(product.price) * qty;

            /* DISCOUNT PRICE */
            let discountPrice =
            Number(product.discount_price) * qty;

            /* DISCOUNT AMOUNT */
            let discountAmount =
            (Number(product.price) - Number(product.discount_price)) * qty;

            subtotal += originalPrice;

            totalDiscount += discountAmount;

            finalTotal += discountPrice;

            let discountPercent = Math.round(
                ((product.price - product.discount_price) / product.price) * 100
            );

            container.innerHTML += `

            <div class="cart-card">

                <!-- IMAGE -->

                <div class="product-img">

                    <img src="${product.image}">

                </div>

                <!-- INFO -->

                <div class="product-info">

                    <div>

                        <div class="top">

                            <div>

                                <p class="category">
                                    ${product.category}
                                </p>

                                <h2>
                                    ${product.name}
                                </h2>

                                <p class="desc">
                                    ${product.description.substring(0,100)}...
                                </p>

                            </div>

                        </div>

                        <!-- PRICE -->

                        <div class="price-box">

                            <span class="price">
                                ₹${product.discount_price}
                            </span>

                            <span class="old-price">
                                ₹${product.price}
                            </span>

                            <span class="discount">
                                ${discountPercent}% OFF
                            </span>

                        </div>

                    </div>

                    <!-- BOTTOM -->

                    <div class="bottom">

                        <!-- QTY -->

                        <div class="qty-box">

                            <button class="qty-btn"
                            onclick="changeQty(${index},-1)">
                                -
                            </button>

                            <div class="qty">
                                ${qty}
                            </div>

                            <button class="qty-btn"
                            onclick="changeQty(${index},1)">
                                +
                            </button>

                        </div>

                        <!-- BUTTONS -->

                        <div class="action-buttons">

                            <button class="btn save-btn">
                                Save for Later
                            </button>

                            <button class="btn remove-btn"
                            onclick="removeItem(${index})">
                                Remove
                            </button>

                        </div>

                    </div>

                </div>

            </div>

            `;
        }

    });

}

/* ================= SUMMARY ================= */

/* SUBTOTAL = ORIGINAL PRICE TOTAL */
document.getElementById("subtotal").innerText =
"₹" + subtotal;

/* DISCOUNT */
document.getElementById("discount").innerText =
"- ₹" + totalDiscount;

/* FINAL TOTAL */
document.getElementById("total").innerText =
"₹" + finalTotal;

/* CHECKOUT TOTAL */
document.getElementById("totalInput").value =
finalTotal;

/* REMOVE ITEM */

function removeItem(index){

    cart.splice(index,1);

    localStorage.setItem(
        "cart",
        JSON.stringify(cart)
    );

    location.reload();
}

/* CHANGE QUANTITY */

function changeQty(index,change){

    if(cart[index].qty){

        cart[index].qty += change;

    }else{

        cart[index].qty = 1 + change;
    }

    if(cart[index].qty <= 0){

        cart[index].qty = 1;
    }

    localStorage.setItem(
        "cart",
        JSON.stringify(cart)
    );

    location.reload();
}

</script>

</body>
</html>