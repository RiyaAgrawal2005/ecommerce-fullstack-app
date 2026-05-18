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






<div class="container">

    <!-- LEFT -->
    <div class="left">

        <!-- <img class="main-img" src="<?php echo $product['image']; ?>"> -->


<div class="image-slider">

    <!-- LEFT ARROW -->

    <button class="slider-btn left-btn" onclick="prevImage()">
        ❮
    </button>

    <!-- MAIN IMAGE -->

    <img 
    id="mainImage"
    class="main-img"
    src="<?php echo $product['image']; ?>">

    <!-- RIGHT ARROW -->

    <button class="slider-btn right-btn" onclick="nextImage()">
        ❯
    </button>

    <!-- DOTS -->

    <div class="dots" id="dots"></div>

</div>


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
$related = mysqli_query($conn, "SELECT * FROM products WHERE FIND_IN_SET('$category', category) AND id!='$id' LIMIT 4");

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


let productImages = [

"<?php echo $product['image']; ?>",

"<?php echo $product['image2']; ?>",

"<?php echo $product['image3']; ?>",

"<?php echo $product['image4']; ?>",

"<?php echo $product['image5']; ?>"

].filter(img => img && img !== "null");

let selectedSize = null;

function selectSize(size){

    selectedSize = size;

    document.getElementById("selectedSize").innerText =
    "Selected: " + size;

    let buttons =
    document.querySelectorAll(".sizes button");

    buttons.forEach(btn => {

        btn.classList.remove("active-size");

        if(btn.innerText == size){
            btn.classList.add("active-size");
        }

    });
}

/* ================= ADD TO CART ================= */

function addToCart(){

    let category =
    "<?php echo strtolower($category); ?>";

    let noSizeCategories =
    ["beauty","electronics","home","toys","books"];

    if(!selectedSize &&
       !noSizeCategories.includes(category)){

        alert("Please select size!");
        return;
    }

    let cart =
    JSON.parse(localStorage.getItem("cart")) || [];

    let productId = <?php echo $id; ?>;

    /* CHECK PRODUCT ALREADY EXISTS */

    let existingProduct =
    cart.find(item =>
        item.id == productId &&
        item.size == (selectedSize || "N/A")
    );

    if(existingProduct){

        existingProduct.qty += 1;

    }else{

        cart.push({

            id: productId,

            size: selectedSize || "N/A",

            qty: 1

        });
    }

    localStorage.setItem(
        "cart",
        JSON.stringify(cart)
    );

    alert("Product added to cart 🛒");

    console.log(cart);
}

/* ================= BUY NOW ================= */

function buyNow(){

    let address =
    document.getElementById("address").value;

    let category =
    "<?php echo strtolower($category); ?>";

    let noSizeCategories =
    ["beauty","electronics","home","toys","books"];

    if(address == ""){

        alert("Please enter address!");
        return;
    }

    if(!selectedSize &&
       !noSizeCategories.includes(category)){

        alert("Please select size!");
        return;
    }

    let buyNowProduct = {

        id: <?php echo $id; ?>,

        size: selectedSize || "N/A",

        qty: 1,

        address: address

    };

    localStorage.setItem(
        "buyNow",
        JSON.stringify(buyNowProduct)
    );

    window.location.href = "checkout.php?type=buyNow";
}

/* ================= WISHLIST ================= */

function addToWishlist(){

    fetch("wishlist.php", {

        method: "POST",

        headers:{
            "Content-Type":
            "application/x-www-form-urlencoded"
        },

        body:"product_id=<?php echo $id ?>"
    })

    .then(res => res.text())

    .then(data => {

        alert("Added to Wishlist ❤️");

    });
}

</script>

<script>

let currentImage = 0;

let mainImage =
document.getElementById("mainImage");

let dotsContainer =
document.getElementById("dots");

/* CREATE DOTS */

productImages.forEach((img,index)=>{

    let dot =
    document.createElement("div");

    dot.classList.add("dot");

    if(index === 0){
        dot.classList.add("active");
    }

    dot.onclick = () => {
        showImage(index);
    };

    dotsContainer.appendChild(dot);

});

/* SHOW IMAGE */

function showImage(index){

    currentImage = index;

    mainImage.src =
    productImages[index];

    document
    .querySelectorAll(".dot")
    .forEach(dot =>
        dot.classList.remove("active")
    );

    document
    .querySelectorAll(".dot")[index]
    .classList.add("active");
}

/* NEXT IMAGE */

function nextImage(){

    currentImage++;

    if(currentImage >= productImages.length){
        currentImage = 0;
    }

    showImage(currentImage);
}

/* PREVIOUS IMAGE */

function prevImage(){

    currentImage--;

    if(currentImage < 0){
        currentImage =
        productImages.length - 1;
    }

    showImage(currentImage);
}

</script>

</body>
</html>