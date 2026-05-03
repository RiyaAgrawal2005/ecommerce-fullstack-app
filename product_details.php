<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>

    <style>
        body{
            font-family: Arial;
            background:#f5f5f5;
        }

        .container{
            width:80%;
            margin:40px auto;
            display:flex;
            gap:30px;
            background:white;
            padding:20px;
            border-radius:10px;
            box-shadow:0 0 10px #ccc;
        }

        .left img{
            width:300px;
            border-radius:10px;
        }

        .right{
            flex:1;
        }

        .price{
            color:green;
            font-size:22px;
            font-weight:bold;
        }

        .old-price{
            text-decoration:line-through;
            color:gray;
            margin-left:10px;
        }

        .btn{
            padding:12px 20px;
            margin-top:15px;
            border:none;
            border-radius:5px;
            cursor:pointer;
            font-size:16px;
        }

        .buy{
            background:#ff5722;
            color:white;
        }

        .cart{
            background:black;
            color:white;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="left">
        <img id="pImg">
    </div>

    <div class="right">
        <h2 id="pName"></h2>

        <p id="pDesc"></p>
        <p id="pSize"></p>

        <p>
            <span class="price" id="pPrice"></span>
            <span class="old-price" id="pOld"></span>
           
        </p>

        <p id="pRating"></p>

        <button class="btn cart" onclick="addToCart()">Add to Cart 🛒</button>
        <button class="btn buy" onclick="goAddress()">Buy Now ⚡</button>
    </div>

</div>

<script>
// all products from DB
let products = <?php
$result = mysqli_query($conn, "SELECT * FROM products");
$data = [];
while($row = mysqli_fetch_assoc($result)){
    $data[] = $row;
}
echo json_encode($data);
?>;

// get selected product id
// let id = localStorage.getItem("buyNow");
let buyNowData = JSON.parse(localStorage.getItem("buyNow"));

let id = buyNowData?.id;
let selectedSize = buyNowData?.size;

// find product
let product = products.find(p => p.id == id);

if(product){
    document.getElementById("pImg").src = product.image;
    document.getElementById("pName").innerText = product.name;
    document.getElementById("pDesc").innerText = product.description;
    document.getElementById("pSize").innerText = "Size: " + selectedSize;
    document.getElementById("pPrice").innerText = "₹" + product.discount_price;
    document.getElementById("pOld").innerText = "₹" + product.price;
    document.getElementById("pRating").innerText = "⭐ " + product.rating;
}else{
    document.body.innerHTML = "<h2 style='text-align:center'>Product not found ❌</h2>";
}

// Add to cart from details page
function addToCart(){
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    // cart.push(id);
    cart.push({
    id: id,
    size: selectedSize,
    qty: 1
});

    localStorage.setItem("cart", JSON.stringify(cart));

    alert("Added to Cart 🛒");
}

// Checkout
function goAddress(){
    window.location.href = "address.php";
}
</script>

</body>
</html>