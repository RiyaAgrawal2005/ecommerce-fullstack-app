<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user'];

$user = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'")
);

/* TOTAL ORDERS */
$total_orders = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) as total FROM orders WHERE user_id='$user_id'"
    )
);

/* WISHLIST ITEMS */
$total_wishlist = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) as total 
         FROM wishlist 
         WHERE user_id='$user_id'"
    )
);

/* CART ITEMS */
// $total_cart = mysqli_fetch_assoc(
//     mysqli_query(
//         $conn,
//         "SELECT COUNT(*) as total 
//          FROM cart 
//          WHERE user_id='$user_id'"
//     )
// );

/* CART ITEMS */
$total_cart = ['total' => 0];

/* TOTAL SPENT */
$total_spent = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT SUM(total) as amount 
         FROM orders 
         WHERE user_id='$user_id'
         AND status='Delivered'"
    )
);

?>


<?php
$products = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
<title>User Dashboard</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial;
}

body{
    background:#f4f7fb;
}

/* DASHBOARD */

.dashboard{
    display:flex;
    min-height:100vh;
}

/* SIDEBAR */

.sidebar{
    width:270px;
    background:#111827;
    color:white;
    padding:25px 20px;
    position:sticky;
    top:0;
    height:100vh;
}

.logo{
    font-size:26px;
    font-weight:bold;
    margin-bottom:30px;
}

/* PROFILE */

.profile-box{
    text-align:center;
    margin-bottom:30px;
}

.profile-img{
    width:90px;
    height:90px;
    border-radius:50%;
    margin-bottom:10px;
    border:4px solid #7c3aed;
}

.profile-box h3{
    font-size:20px;
}

.profile-box p{
    color:#cbd5e1;
    font-size:14px;
    margin-top:5px;
}

/* MENU */

.sidebar ul{
    list-style:none;
}

.sidebar ul li{
    margin-bottom:12px;
}

.sidebar ul li a{
    display:block;
    padding:14px;
    border-radius:10px;
    text-decoration:none;
    color:white;
    transition:0.3s;
    font-size:15px;
}

.sidebar ul li a:hover,
.sidebar ul li.active a{
    background:#7c3aed;
}

/* MAIN */

.main{
    flex:1;
    padding:25px;
}

/* TOPBAR */

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
    gap:20px;
}

.search-box{
    flex:1;
}

.search-box input{
    width:100%;
    padding:14px;
    border:none;
    border-radius:12px;
    background:white;
    box-shadow:0 2px 10px rgba(0,0,0,0.06);
    font-size:15px;
}

.welcome{
    font-size:22px;
    font-weight:bold;
}

/* STATS */

.stats{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-bottom:30px;
}

.stat-card{
    background:white;
    border-radius:16px;
    padding:25px;
    box-shadow:0 4px 15px rgba(0,0,0,0.05);
}

.stat-card h2{
    margin-top:10px;
    color:#7c3aed;
}

/* CATEGORIES */

.categories{
    display:flex;
    gap:18px;
    overflow-x:auto;
    margin-bottom:35px;
    padding-bottom:10px;
}

.cat{
    min-width:90px;
    text-align:center;
    cursor:pointer;
}

.circle{
    width:75px;
    height:75px;
    border-radius:50%;
    background:white;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:auto;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
    overflow:hidden;
    transition:0.3s;
}

.circle img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.cat.active .circle,
.cat:hover .circle{
    transform:scale(1.08);
    border:3px solid #7c3aed;
}

.cat p{
    margin-top:8px;
    font-size:14px;
    font-weight:600;
}

/* PRODUCTS */

.section-title{
    margin-bottom:20px;
    font-size:24px;
}

.products{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(240px,1fr));
    gap:22px;
}

/* CARD */

.card{
    background:white;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 4px 15px rgba(0,0,0,0.06);
    transition:0.3s;
    position:relative;
    cursor:pointer;
}

.card:hover{
    transform:translateY(-5px);
}

.card img{
    width:100%;
    height:230px;
    object-fit:cover;
}

/* INFO */

.card-body{
    padding:16px;
}

.category{
    color:#7c3aed;
    font-size:13px;
    font-weight:bold;
}

.card h3{
    margin:10px 0;
    font-size:18px;
}

.desc{
    color:#666;
    font-size:14px;
    height:40px;
    overflow:hidden;
}

.price{
    margin-top:12px;
    font-size:20px;
    font-weight:bold;
}

.old-price{
    text-decoration:line-through;
    color:#999;
    font-size:14px;
    margin-left:8px;
}

.badge{
    position:absolute;
    top:12px;
    left:12px;
    background:#ef4444;
    color:white;
    padding:6px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
}

.delivery{
    color:green;
    margin-top:8px;
    font-size:14px;
}

/* BUTTONS */

.card-buttons{
    display:flex;
    gap:10px;
    margin-top:15px;
}

.btn{
    flex:1;
    padding:10px;
    border:none;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
}

.cart-btn{
    background:#111827;
    color:white;
}

.buy-btn{
    background:#7c3aed;
    color:white;
}

/* RESPONSIVE */

@media(max-width:900px){

    .dashboard{
        flex-direction:column;
    }

    .sidebar{
        width:100%;
        height:auto;
        position:relative;
    }

    .products{
        grid-template-columns:repeat(auto-fill,minmax(180px,1fr));
    }

    .card img{
        height:180px;
    }
}

</style>
</head>

<body>

<div class="dashboard">

    <!-- SIDEBAR -->

    <div class="sidebar">

        <div class="logo">🛍 ShopEasy</div>

        <div class="profile-box">

            <img 
            src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['name']); ?>&background=7c3aed&color=fff" 
            class="profile-img">

            <h3><?php echo $user['name']; ?></h3>

            <p><?php echo $user['email']; ?></p>

        </div>

        <ul>
            <li class="active">
                <a href="#">🏠 Dashboard</a>
            </li>

            <li>
                <a href="profile.php">👤 My Profile</a>
            </li>

            <li>
                <a href="orders.php">📦 My Orders</a>
            </li>

            <li>
                <a href="wishlist_page.php">❤️ Wishlist</a>
            </li>

            <li>
                <a href="cart.php">🛒 Cart</a>
            </li>

            <li>
                <a href="logout.php">🚪 Logout</a>
            </li>
        </ul>

    </div>

    <!-- MAIN -->

    <div class="main">

        <!-- TOPBAR -->

        <div class="topbar">

            <div class="welcome">
                Welcome Back 👋
            </div>

            <div class="search-box">
                <input 
                type="text"
                id="search"
                placeholder="Search products..."
                onkeyup="searchProduct()">
            </div>

        </div>

        <!-- STATS -->

        <div class="stats">

    <div class="stat-card">
        <p>📦 Total Orders</p>

        <h2>
            <?php echo $total_orders['total']; ?>
        </h2>
    </div>

    <div class="stat-card">
        <p>❤️ Wishlist Items</p>

        <h2>
            <?php echo $total_wishlist['total']; ?>
        </h2>
    </div>

    <div class="stat-card">
        <p>🛒 Cart Items</p>
<!-- 
        <h2>
            <?php echo $total_cart['total']; ?>
        </h2> -->

        <h2 id="cartCount">0</h2>
    </div>

    <div class="stat-card">
        <p>💰 Total Spent</p>

        <h2>
            ₹<?php echo $total_spent['amount'] ?? 0; ?>
        </h2>
    </div>

</div>

        <!-- CATEGORY -->

        <div class="categories">

            <div class="cat active" onclick="filterCategory(event,'all')">

                <div class="circle">
                    <img src="https://cdn-icons-png.flaticon.com/512/3081/3081559.png">
                </div>

                <p>All</p>

            </div>

            <?php
            $cats = mysqli_query($conn, "SELECT * FROM categories");

            while($c = mysqli_fetch_assoc($cats)){
            ?>

            <div class="cat"
            onclick="filterCategory(event,'<?php echo strtolower($c['name']); ?>')">

                <div class="circle">

                    <img 
                    src="<?php echo trim($c['image']); ?>"
                    onerror="this.src='https://via.placeholder.com/70';">

                </div>

                <p><?php echo ucfirst($c['name']); ?></p>

            </div>

            <?php } ?>

        </div>

        <!-- PRODUCTS -->

        <h2 class="section-title">🔥 Trending Products</h2>

        <div class="products">

        <?php while($p = mysqli_fetch_assoc($products)){ ?>

        <?php
        $discount = 0;

        if($p['price'] > 0){
            $discount = round(
                (($p['price'] - $p['discount_price']) / $p['price']) * 100
            );
        }
        ?>

        <div class="card product-card"

        data-category="<?php echo strtolower($p['category']); ?>"

        onclick="window.location.href='product_detail.php?id=<?php echo $p['id']; ?>'">

            <span class="badge">
                <?php echo $discount; ?>% OFF
            </span>

            <img src="<?php echo $p['image']; ?>">

            <div class="card-body">

                <p class="category">
                    <?php echo ucfirst($p['category']); ?>
                </p>

                <h3>
                    <?php echo substr($p['name'],0,25); ?>
                </h3>

                <p class="desc">
                    <?php echo substr($p['description'],0,50); ?>...
                </p>

                <div class="price">

                    ₹<?php echo $p['discount_price']; ?>

                    <span class="old-price">
                        ₹<?php echo $p['price']; ?>
                    </span>

                </div>

                <p class="delivery">🚚 Free Delivery</p>

                <div class="card-buttons">

    <button 
    class="btn cart-btn"
    onclick="event.stopPropagation(); addToCart(<?php echo $p['id']; ?>)">
    
        Add Cart
    </button>

    <button 
    class="btn buy-btn"
    onclick="event.stopPropagation(); buyNow(<?php echo $p['id']; ?>)">
        Buy Now
    </button>

</div>

            </div>

        </div>

        <?php } ?>

        </div>

    </div>

</div>

<script>

function searchProduct(){

    let input =
    document.getElementById("search")
    .value
    .toLowerCase();

    let cards =
    document.querySelectorAll(".product-card");

    cards.forEach(card => {

        let name =
        card.querySelector("h3")
        .innerText
        .toLowerCase();

        if(name.includes(input)){
            card.style.display = "block";
        }else{
            card.style.display = "none";
        }

    });
}

function filterCategory(e, category){

    let cards =
    document.querySelectorAll(".product-card");

    let cats =
    document.querySelectorAll(".cat");

    cats.forEach(c =>
        c.classList.remove("active")
    );

    e.currentTarget.classList.add("active");

    cards.forEach(card => {

        let productCategory =
        card.getAttribute("data-category");

        if(category === "all" ||
        //    productCategory === category
           productCategory.includes(category)
        ){

            card.style.display = "block";

        }else{

            card.style.display = "none";
        }

    });
}



/* ================= CART COUNT ================= */

let cart =
JSON.parse(localStorage.getItem("cart")) || [];

let totalItems = 0;

cart.forEach(item => {

    totalItems += item.qty || 1;

});

document.getElementById("cartCount")
.innerText = totalItems;







function addToCart(productId){

    let cart =
    JSON.parse(localStorage.getItem("cart")) || [];

    /* CHECK PRODUCT ALREADY EXISTS */

    let existingProduct =
    cart.find(item =>
        item.id == productId &&
        item.size == "N/A"
    );

    if(existingProduct){

        existingProduct.qty += 1;

    }else{

        cart.push({

            id: productId,

            size: "N/A",

            qty: 1

        });
    }

    localStorage.setItem(
        "cart",
        JSON.stringify(cart)
    );

    alert("🛒 Product added to cart");

    location.reload();
}

function buyNow(productId){

    window.location.href =
    "product_detail.php?id=" + productId;

}

</script>

</body>
</html>