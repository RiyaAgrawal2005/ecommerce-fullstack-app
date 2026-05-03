<?php
include 'db.php';

$id = $_GET['id'];

$product = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM products WHERE id='$id'")
);

$category = $product['category'];

// discount %
$discount = round((($product['price'] - $product['discount_price']) / $product['price']) * 100);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $product['name']; ?></title>
    <link rel="stylesheet" href="product.css">
</head>
<body>
<div class="top-bar">

    <div class="logo">🛍 MyShop</div>

    <div class="actions">
        <div class="icon" onclick="window.location.href='wishlist_page.php'">
            ❤️ Wishlist
        </div>

        <div class="icon" onclick="window.location.href='cart.php'">
            🛒 Cart
        </div>
    </div>

</div>

<!-- <div class="container">

   
    <img class="main-img" src="<?php echo $product['image']; ?>">

   
    <h2><?php echo $product['name']; ?></h2>

   
    <p class="desc"><?php echo $product['description']; ?></p>

   
    <div class="price-box">
        <span class="new">₹<?php echo $product['discount_price']; ?></span>
        <span class="old">₹<?php echo $product['price']; ?></span>
        <span class="off"><?php echo $discount; ?>% OFF</span>
    </div>

  
    <p class="rating">⭐ 4.2 | 120 Reviews</p>

   
   <h4>Select Size</h4>
<div class="sizes">

<?php
$cat = strtolower($category);


if(in_array($cat, ['fashion','mens','womens','kids'])){ ?>

    <button onclick="selectSize('S')">S</button>
    <button onclick="selectSize('M')">M</button>
    <button onclick="selectSize('L')">L</button>
    <button onclick="selectSize('XL')">XL</button>

<?php }


elseif($cat == 'shoes'){ ?>

    <button onclick="selectSize('6')">6</button>
    <button onclick="selectSize('7')">7</button>
    <button onclick="selectSize('8')">8</button>
    <button onclick="selectSize('9')">9</button>
    <button onclick="selectSize('10')">10</button>

<?php }


elseif(in_array($cat, ['bags','jewellery'])){ ?>

    <button onclick="selectSize('Free Size')">Free Size</button>

<?php }


else { ?>

    <p>No size required</p>

<?php } ?>

</div>

    <p id="selectedSize"></p>

    
    <h4>Delivery Address</h4>
    <textarea id="address" placeholder="Enter your address"></textarea>

    <p class="delivery">🚚 Free Delivery Available</p>

  
    <div class="btns">
        <button onclick="addToWishlist()">❤️ Wishlist</button>
        <button onclick="addToCart()">🛒 Add to Cart</button>
        <button onclick="buyNow()">⚡ Buy Now</button>
    </div>

</div> -->




<div class="container">

    <!-- LEFT -->
    <div class="left">

        <img class="main-img" src="<?php echo $product['image']; ?>">

        <h2 class="pname"><?php echo $product['name']; ?></h2>

        <p class="desc"><?php echo $product['description']; ?></p>

         <div class="price-box">
            ₹<?php echo $product['discount_price']; ?>
            <span class="old">₹<?php echo $product['price']; ?></span>
            <span class="off"><?php echo $discount; ?>% OFF</span>
        </div>

        <!-- RATING -->
        <p class="rating">⭐ 4.2 | 120 Reviews</p>

    </div>

    <!-- RIGHT -->
    <div class="right">

        <!-- PRICE -->
       

        <!-- SIZE -->
         
   <h4>Select Size</h4>
<div class="sizes">

<?php
$cat = strtolower($category);


if(in_array($cat, ['fashion','mens','womens','kids'])){ ?>

    <button onclick="selectSize('S')">S</button>
    <button onclick="selectSize('M')">M</button>
    <button onclick="selectSize('L')">L</button>
    <button onclick="selectSize('XL')">XL</button>

<?php }


elseif($cat == 'shoes'){ ?>

    <button onclick="selectSize('6')">6</button>
    <button onclick="selectSize('7')">7</button>
    <button onclick="selectSize('8')">8</button>
    <button onclick="selectSize('9')">9</button>
    <button onclick="selectSize('10')">10</button>

<?php }


elseif(in_array($cat, ['bags','jewellery'])){ ?>

    <button onclick="selectSize('Free Size')">Free Size</button>

<?php }


else { ?>

    <p>No size required</p>

<?php } ?>

</div>

    <p id="selectedSize"></p>

    
    <h4>Delivery Address</h4>
    <textarea id="address" placeholder="Enter your address"></textarea>

    <p class="delivery">🚚 Free Delivery Available</p>

  
    <div class="btns">
        <button onclick="addToWishlist()">❤️ Wishlist</button>
        <button onclick="addToCart()">🛒 Add to Cart</button>
        <button onclick="buyNow()">⚡ Buy Now</button>
    </div>

</div>
</div>



<!-- RELATED PRODUCTS -->
<h3 class="heading">Related Products</h3>

<div class="products">

<?php
$related = mysqli_query($conn, "SELECT * FROM products WHERE category='$category' AND id!='$id' LIMIT 4");

while($r = mysqli_fetch_assoc($related)){
?>

<div class="card"
onclick="window.location.href='product_detail.php?id=<?php echo $r['id']; ?>'">

    <img src="<?php echo $r['image']; ?>">
    <p><?php echo substr($r['name'],0,20); ?></p>
    <p>₹<?php echo $r['discount_price']; ?></p>

</div>

<?php } ?>

</div>

<script>



let selectedSize = null;

function selectSize(size){
    selectedSize = size;
    document.getElementById("selectedSize").innerText = "Selected: " + size;

    let buttons = document.querySelectorAll(".sizes button");

    buttons.forEach(btn => {
        btn.classList.remove("active-size");
        if(btn.innerText == size){
            btn.classList.add("active-size");
        }
    });
}

// ADD TO CART
function addToCart(){

    let noSizeCategories = ["beauty","electronics","home","toys","books"];

    let category = "<?php echo strtolower($category); ?>";

    if(!selectedSize && !noSizeCategories.includes(category)){
        alert("Select size first!");
        return;
    }

    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    cart.push({
        id: <?php echo $id ?>,
        size: selectedSize || "N/A",
        qty: 1
    });

    localStorage.setItem("cart", JSON.stringify(cart));

    alert("Added to cart 🛒");
}

// BUY NOW
function buyNow(){

    let address = document.getElementById("address").value;
    let category = "<?php echo strtolower($category); ?>";

    let noSizeCategories = ["beauty","electronics","home","toys","books"];

    if(address == ""){
        alert("Enter address first!");
        return;
    }

    if(!selectedSize && !noSizeCategories.includes(category)){
        alert("Select size first!");
        return;
    }

    localStorage.setItem("buyNow", JSON.stringify({
        id: <?php echo $id ?>,
        size: selectedSize || "N/A",
        address: address,
        qty: 1
    }));

    window.location.href = "checkout.php";
}

// WISHLIST
function addToWishlist(){
    fetch("wishlist.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "product_id=<?php echo $id ?>"
    })
    .then(res => res.text())
    .then(() => alert("Added to Wishlist ❤️"));
}
</script>

</body>
</html>