<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Buy Now</title>
</head>
<body>

<h2>⚡ Buy Product</h2>

<div id="product"></div>

<script>
let id = localStorage.getItem("buyNow");

let products = <?php
$result = mysqli_query($conn, "SELECT * FROM products");
$data = [];
while($row = mysqli_fetch_assoc($result)){
    $data[] = $row;
}
echo json_encode($data);
?>;

let product = products.find(p => p.id == id);

if(product){
    document.getElementById("product").innerHTML = `
        <img src="${product.image}" width="200">
        <h3>${product.name}</h3>
        <p>₹${product.discount_price}</p>

        <button onclick="placeOrder(${product.discount_price})">
            Confirm Purchase
        </button>
    `;
}

function placeOrder(total){
    alert("Order placed for ₹" + total + " 🎉");
    localStorage.removeItem("buyNow");
}
</script>

</body>
</html>