<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user'];

$result = mysqli_query($conn, "
    SELECT p.* FROM products p
    JOIN wishlist w ON p.id = w.product_id
    WHERE w.user_id='$user_id'
    ORDER BY w.id DESC
");

$totalWishlist = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Wishlist</title>

<link rel="preconnect" href="https://fonts.googleapis.com">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background:#f5f7fb;
    color:#222;
}

/* ================= HEADER ================= */

.header{
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

.logo{
    display:flex;
    align-items:center;
    gap:12px;
}

.logo h2{
    font-size:24px;
    font-weight:700;
}

.nav{
    display:flex;
    align-items:center;
    gap:18px;
}

.nav a{
    text-decoration:none;
    color:white;
    font-weight:500;
    padding:10px 16px;
    border-radius:10px;
    transition:0.3s;
}

.nav a:hover{
    background:rgba(255,255,255,0.15);
}

/* ================= PAGE HEADER ================= */

.page-top{
    padding:35px 40px 10px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:15px;
}

.page-top h1{
    font-size:34px;
    font-weight:700;
    color:#111827;
}

.page-top p{
    color:#6b7280;
    margin-top:8px;
}

.search-box{
    position:relative;
}

.search-box input{
    width:320px;
    padding:14px 16px 14px 45px;
    border:none;
    border-radius:14px;
    background:white;
    box-shadow:0 3px 12px rgba(0,0,0,0.08);
    font-size:15px;
    outline:none;
}

.search-icon{
    position:absolute;
    top:14px;
    left:15px;
    color:#777;
}

/* ================= STATS ================= */

.stats{
    padding:10px 40px 25px;
}

.stat-card{
    background:white;
    padding:24px;
    border-radius:20px;
    box-shadow:0 5px 18px rgba(0,0,0,0.07);
    display:flex;
    align-items:center;
    justify-content:space-between;
}

.stat-left h3{
    color:#6b7280;
    font-size:15px;
    margin-bottom:8px;
}

.stat-left h2{
    font-size:34px;
    color:#111827;
}

.stat-icon{
    width:70px;
    height:70px;
    border-radius:18px;
    background:#f3e8ff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:30px;
}

/* ================= PRODUCTS ================= */

.products{
    padding:0 40px 40px;
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(250px,1fr));
    gap:25px;
}

/* ================= CARD ================= */

.card{
    background:white;
    border-radius:22px;
    overflow:hidden;
    transition:0.35s;
    cursor:pointer;
    box-shadow:0 5px 18px rgba(0,0,0,0.08);
    position:relative;
}

.card:hover{
    transform:translateY(-8px);
    box-shadow:0 12px 28px rgba(0,0,0,0.14);
}

/* IMAGE */

.img-box{
    position:relative;
    width:100%;
    height:260px;
    overflow:hidden;
    background:#f8fafc;
}

.img-box img{
    width:100%;
    height:100%;
    object-fit:cover;
    transition:0.4s;
}

.card:hover img{
    transform:scale(1.06);
}

/* HEART */

.wishlist-icon{
    position:absolute;
    top:14px;
    right:14px;
    width:42px;
    height:42px;
    border-radius:50%;
    background:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:18px;
    box-shadow:0 5px 14px rgba(0,0,0,0.12);
}

/* CONTENT */

.content{
    padding:18px;
}

.category{
    font-size:12px;
    color:#7c3aed;
    font-weight:600;
    margin-bottom:8px;
    text-transform:uppercase;
    letter-spacing:1px;
}

.card h3{
    font-size:17px;
    color:#111827;
    margin-bottom:10px;
    height:45px;
    overflow:hidden;
}

.desc{
    font-size:13px;
    color:#6b7280;
    line-height:1.5;
    height:40px;
    overflow:hidden;
}

/* PRICE */

.price-row{
    margin-top:16px;
    display:flex;
    align-items:center;
    justify-content:space-between;
}

.price{
    font-size:20px;
    font-weight:700;
    color:#111827;
}

.old-price{
    font-size:13px;
    text-decoration:line-through;
    color:#9ca3af;
    margin-left:6px;
}

/* RATING */

.rating{
    margin-top:12px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.stars{
    color:#f59e0b;
    font-size:14px;
}

.delivery{
    font-size:12px;
    color:#16a34a;
    font-weight:600;
}

/* BUTTONS */

.buttons{
    display:flex;
    gap:10px;
    margin-top:18px;
}

.btn{
    flex:1;
    border:none;
    padding:11px;
    border-radius:12px;
    cursor:pointer;
    font-weight:600;
    transition:0.3s;
}

.cart-btn{
    background:#111827;
    color:white;
}

.cart-btn:hover{
    background:#000;
}

.remove-btn{
    background:#fee2e2;
    color:#dc2626;
}

.remove-btn:hover{
    background:#fecaca;
}

/* EMPTY */

.empty{
    width:100%;
    background:white;
    border-radius:20px;
    padding:60px 20px;
    text-align:center;
    box-shadow:0 5px 18px rgba(0,0,0,0.07);
}

.empty h2{
    margin-top:20px;
    color:#111827;
}

.empty p{
    margin-top:10px;
    color:#6b7280;
}

.empty a{
    display:inline-block;
    margin-top:22px;
    background:#7c3aed;
    color:white;
    text-decoration:none;
    padding:14px 24px;
    border-radius:14px;
    font-weight:600;
}

/* RESPONSIVE */

@media(max-width:768px){

    .header{
        padding:18px 20px;
        flex-direction:column;
        gap:15px;
    }

    .nav{
        flex-wrap:wrap;
        justify-content:center;
    }

    .page-top{
        padding:25px 20px 10px;
        flex-direction:column;
        align-items:flex-start;
    }

    .search-box input{
        width:100%;
    }

    .stats{
        padding:10px 20px 20px;
    }

    .products{
        padding:0 20px 30px;
        grid-template-columns:1fr;
    }
}

</style>
</head>

<body>

<!-- ================= HEADER ================= -->

<div class="header">

    <div class="logo">
        <h2>❤️ My Wishlist</h2>
    </div>

    <div class="nav">
        <a href="user_dashboard.php">🏠 Home</a>
        <a href="orders.php">📦 Orders</a>
        <a href="cart.php">🛒 Cart</a>
        <a href="profile.php">👤 Profile</a>
    </div>

</div>

<!-- ================= PAGE TOP ================= -->

<div class="page-top">

    <div>
        <h1>Saved Items</h1>
        <p>Your favourite products in one place</p>
    </div>

    <div class="search-box">
        <span class="search-icon">🔍</span>
        <input type="text" id="searchInput" placeholder="Search wishlist products...">
    </div>

</div>

<!-- ================= STATS ================= -->

<div class="stats">

    <div class="stat-card">

        <div class="stat-left">
            <h3>Total Wishlist Items</h3>
            <h2><?php echo $totalWishlist; ?></h2>
        </div>

        <div class="stat-icon">
            ❤️
        </div>

    </div>

</div>

<!-- ================= PRODUCTS ================= -->

<div class="products" id="wishlistContainer">

<?php if($totalWishlist > 0){ ?>

<?php while($row = mysqli_fetch_assoc($result)){ 

$discount = 0;

if($row['price'] > 0){
    $discount = round((($row['price'] - $row['discount_price']) / $row['price']) * 100);
}
?>

<div class="card product-card"
data-name="<?php echo strtolower($row['name']); ?>"
onclick="window.location.href='product_detail.php?id=<?php echo $row['id']; ?>'">

    <!-- IMAGE -->
    <div class="img-box">

        <img src="<?php echo $row['image']; ?>">

        <div class="wishlist-icon">
            ❤️
        </div>

    </div>

    <!-- CONTENT -->
    <div class="content">

        <p class="category">
            <?php echo ucfirst($row['category']); ?>
        </p>

        <h3>
            <?php echo $row['name']; ?>
        </h3>

        <p class="desc">
            <?php echo substr($row['description'],0,70); ?>...
        </p>

        <!-- PRICE -->
        <div class="price-row">

            <div>
                <span class="price">
                    ₹<?php echo $row['discount_price']; ?>
                </span>

                <span class="old-price">
                    ₹<?php echo $row['price']; ?>
                </span>
            </div>

            <div style="color:#16a34a;font-weight:700;font-size:13px;">
                <?php echo $discount; ?>% OFF
            </div>

        </div>

        <!-- RATING -->
        <div class="rating">

            <div class="stars">
                ⭐ 4.5
            </div>

            <div class="delivery">
                Free Delivery
            </div>

        </div>

        <!-- BUTTONS -->
        <div class="buttons">

            <button class="btn cart-btn">
                Add to Cart
            </button>

            <button class="btn remove-btn">
                Remove
            </button>

        </div>

    </div>

</div>

<?php } ?>

<?php } else { ?>

<div class="empty">

    <div style="font-size:70px;">💔</div>

    <h2>Your Wishlist is Empty</h2>

    <p>Save your favourite products to view them later.</p>

    <a href="user_dashboard.php">
        Explore Products
    </a>

</div>

<?php } ?>

</div>

<script>

/* SEARCH */

document.getElementById("searchInput").addEventListener("keyup", function(){

    let value = this.value.toLowerCase();

    let cards = document.querySelectorAll(".product-card");

    cards.forEach(card => {

        let name = card.getAttribute("data-name");

        if(name.includes(value)){
            card.style.display = "block";
        }else{
            card.style.display = "none";
        }

    });

});

</script>

</body>
</html>