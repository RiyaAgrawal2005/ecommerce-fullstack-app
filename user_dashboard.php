<!-- only img is display in left and below it tabs -  -->
<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user'];

/* Fetch user data */
$user = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'")
);

/* Fetch orders */
$orders = mysqli_query(
    $conn,
    "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="dashboard">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <!-- <h2>User Panel</h2> -->
        <div class="profile-box">
    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['name']); ?>&background=7c3aed&color=fff" class="profile-img">

    <h3><?php echo $user['name']; ?></h3>
    <!-- <p><?php echo $user['email']; ?></p> -->
</div>

        <ul>
            <li class="active">🏠 Home</li>
        
            <li><a href="profile.php">📦 Profile</a></li>
            <li><a href="orders.php">📦 My Orders</a></li>
         
            <li><a href="wishlist_page.php">❤️ Wishlist</a></li>
            <li><a href="cart.php">🛒 Cart</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- MAIN -->
    <div class="main">

 
<input type="text" id="search" placeholder="Search products..." onkeyup="searchProduct()">


<!-- <div class="categories">

    <div class="cat active" onclick="filterCategory(event,'all')">All</div>

    <div class="cat" onclick="filterCategory(event,'fashion')">👗<br>Fashion</div>
    <div class="cat" onclick="filterCategory(event,'shoes')">👟<br>Shoes</div>
    <div class="cat" onclick="filterCategory(event,'bags')">👜<br>Bags</div>
    <div class="cat" onclick="filterCategory(event,'beauty')">💄<br>Beauty</div>
    <div class="cat" onclick="filterCategory(event,'electronics')">📱<br>Electronics</div>
    <div class="cat" onclick="filterCategory(event,'home')">🏠<br>Home</div>
    <div class="cat" onclick="filterCategory(event,'mens')">👔<br>Mens</div>
<div class="cat" onclick="filterCategory(event,'womens')">👗<br>Womens</div>
<div class="cat" onclick="filterCategory(event,'kids')">🧒<br>Kids</div>
<div class="cat" onclick="filterCategory(event,'jewellery')">💍<br>Jewellery</div>
<div class="cat" onclick="filterCategory(event,'toys')">🧸<br>Toys</div>
<div class="cat" onclick="filterCategory(event,'books')">📚<br>Books</div>

</div> -->

<div class="categories">

    <!-- ALL -->
    <!-- <div class="cat active" onclick="filterCategory(event,'all')">
        <div class="circle">All</div>
    </div> -->

    <div class="cat active" onclick="filterCategory(event,'all')">
    <div class="circle">
        <img src="data:image/png;base64,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">
    </div>
    <p>All</p>
</div>

    <?php
    $cats = mysqli_query($conn, "SELECT * FROM categories");

    while($c = mysqli_fetch_assoc($cats)){
    ?>

    <div class="cat" onclick="filterCategory(event,'<?php echo strtolower($c['name']); ?>')">

 


     <div class="circle">
    <?php if(!empty($c['image'])) { ?>
        <img 
            src="<?php echo trim($c['image']); ?>" 
            onerror="this.onerror=null; this.src='https://via.placeholder.com/70';">
    <?php } else { ?>
        <img src="https://via.placeholder.com/70">
    <?php } ?>
</div>



<p class="name"><?php echo ucfirst($c['name']); ?></p>
</div>

    <?php } ?>

</div>


<h3>🛍 Explore Products</h3>

<div class="products">
    <p id="noResult" style="text-align:center; display:none; color:gray;">
    No products found 😢
</p>

<?php
$products = mysqli_query($conn, "SELECT * FROM products");

while($p = mysqli_fetch_assoc($products)){
?>

<!-- <div class="card product-card" onclick="openModal(<?php echo htmlspecialchars(json_encode($p)); ?>)"> -->
   





<div class="card product-card" 
     data-category="<?php echo strtolower($p['category']); ?>"
     
    onclick="window.location.href='product_detail.php?id=<?php echo $p['id']; ?>'">

    <img src="<?php echo $p['image']; ?>">

    <p class="category"><?php echo ucfirst($p['category']); ?></p>

    <h3><?php echo substr($p['name'],0,25); ?>...</h3>

    <p class="desc">
        <?php echo substr($p['description'],0,40); ?>...
    </p>

    <p class="price">
        ₹<?php echo $p['discount_price']; ?>
        <span class="old-price">₹<?php echo $p['price']; ?></span>
    </p>

   <?php 
$discount = 0;
if($p['price'] > 0){
    $discount = round((($p['price'] - $p['discount_price'])/$p['price']) * 100);
}
?>

<span class="badge"><?php echo $discount; ?>% OFF</span>
<p class="delivery">Free Delivery</p>

</div>
   <?php } ?>





</div>

        <!-- ORDERS -->
       

    </div>
</div>
<script>

function searchProduct(){
    let input = document.getElementById("search").value.toLowerCase();
    let cards = document.querySelectorAll(".product-card");
    let found = false;

    cards.forEach(card => {
        let name = card.querySelector("h3").innerText.toLowerCase();

        if(name.includes(input)){
            card.style.display = "block";
            found = true;
        } else {
            card.style.display = "none";
        }
    });

    document.getElementById("noResult").style.display = found ? "none" : "block";
}


let currentProduct = null;

function openModal(product){
    currentProduct = product;

    document.getElementById("productModal").style.display = "block";

    document.getElementById("mImg").src = product.image;
    document.getElementById("mName").innerText = product.name;
    document.getElementById("mDesc").innerText = product.description;
    document.getElementById("mPrice").innerText = "₹" + product.discount_price;

    // sizes
    let sizeHTML = "";

    if(product.category.toLowerCase() === "fashion"){
        ["S","M","L","XL"].forEach(s => {
            sizeHTML += `<button onclick="selectSizeModal('${s}')">${s}</button>`;
        });
    } 
    else if(product.category.toLowerCase() === "shoes"){
        ["6","7","8","9","10"].forEach(s => {
            sizeHTML += `<button onclick="selectSizeModal('${s}')">${s}</button>`;
        });
    } 
    else {
        sizeHTML = "<p>No size required</p>";
    }

    document.getElementById("mSizes").innerHTML = sizeHTML;
}

let selectedSizeModal = null;

function selectSizeModal(size){
    selectedSizeModal = size;
    alert("Selected: " + size);
}

function addToCartModal(){
    if(!selectedSizeModal){
        alert("Select size first");
        return;
    }

    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    cart.push({
        id: currentProduct.id,
        size: selectedSizeModal,
        qty: 1
    });

    localStorage.setItem("cart", JSON.stringify(cart));

    alert("Added to cart");
}

function buyNowModal(){
    if(!selectedSizeModal){
        alert("Select size first");
        return;
    }

    localStorage.setItem("buyNow", JSON.stringify({
        id: currentProduct.id,
        size: selectedSizeModal,
        qty: 1
    }));

    window.location.href = "product_details.php";
}

// let selectedSizes = {};
// function selectSize(productId, size){
//     // store in memory (not localStorage)
//     selectedSizes[productId] = size;

//     document.getElementById("selected_" + productId).innerText =
//         "✔ Selected: " + size;

//     let buttons = document.querySelectorAll("#sizes_" + productId + " button");

//     buttons.forEach(btn => {
//         btn.classList.remove("active-size");
//         if(btn.innerText.trim() == size){
//             btn.classList.add("active-size");
//         }
//     });
// }


// function openModal(product){
//     document.getElementById("productModal").style.display = "block";

//     document.getElementById("mImg").src = product.image;
//     document.getElementById("mName").innerText = product.name;
//     document.getElementById("mDesc").innerText = product.description;
//     document.getElementById("mPrice").innerText = "₹" + product.discount_price;
// }

// function addToCart(id){
//     console.log("Add to cart clicked", id);
//     let size = getSize(id);
//     if(!size) return;

//     let cart = JSON.parse(localStorage.getItem("cart")) || [];

//     cart.push({
//         id: id,
//         size: size,
//         qty: 1
//     });

//     localStorage.setItem("cart", JSON.stringify(cart));

//     alert("Added to Cart 🛒");
    
//     window.location.href = "cart.php";
// }

// function buyNow(id){
//     let size = getSize(id);
//     if(!size) return;

//     let product = {
//         id: id,
//         size: size,
//         qty: 1
//     };

//     localStorage.setItem("buyNow", JSON.stringify(product));

//     window.location.href = "product_details.php";
// }


function filterCategory(e, category){

    let cards = document.querySelectorAll(".product-card");
    let cats = document.querySelectorAll(".cat");

    // remove active
    cats.forEach(c => c.classList.remove("active"));

    // add active to clicked
    e.currentTarget.classList.add("active");

    // filter products
    cards.forEach(card => {

        let productCategory = card.getAttribute("data-category");

        if(category === "all" || productCategory === category){
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }

    });
}








function closeModal(){
    document.getElementById("productModal").style.display = "none";
}


function addToWishlist(id){
    fetch("wishlist.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "product_id=" + id
    })
    .then(res => res.text())
    .then(() => alert("Added to Wishlist ❤️"));
}


</script>


<div id="productModal" class="modal">
    <div class="modal-content">

        <span onclick="closeModal()" class="close">&times;</span>

        <img id="mImg">

        <h2 id="mName"></h2>
        <p id="mDesc"></p>

        <h3 id="mPrice"></h3>

        <!-- SIZE -->
        <div id="mSizes"></div>

        <!-- BUTTONS -->
        <button onclick="addToCartModal()">🛒 Add to Cart</button>
        <button onclick="buyNowModal()">⚡ Buy Now</button>

    </div>
</div>
</body>
</html>