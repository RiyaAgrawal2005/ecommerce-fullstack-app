<!-- 
<?php
session_start();

// ❗ Force logout if user comes back
if(isset($_SESSION['user'])){
    session_unset();
    session_destroy();
}

include 'db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>ShopEasy</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>ShopEasy</h1>

    <nav>
        <a href="index.php">Home</a>

        <?php if(isset($_SESSION['user'])){ ?>
            <a href="cart.php">Cart 🛒</a>
            <a href="user_dashboard.php">My Account</a>
            <a href="logout.php">Logout</a>
        <?php } else { ?>
            <a href="login.php">Login</a>
        <?php } ?>
    </nav>
</header>

<section class="products">

<?php
$result = mysqli_query($conn, "SELECT * FROM products");

while($row = mysqli_fetch_assoc($result)){
?>
<div class="card">
    <img src="<?php echo $row['image']; ?>">

    <h3><?php echo $row['name']; ?></h3>

    <p class="desc">
        <?php echo substr($row['description'], 0, 60); ?>...
    </p>

    <p class="price">
        ₹<?php echo $row['discount_price']; ?>
        <span class="old-price">₹<?php echo $row['price']; ?></span>
    </p>

    <p class="rating">⭐ <?php echo $row['rating']; ?></p>

    <p class="stock">
        <?php echo ($row['stock'] > 0) ? "In Stock" : "Out of Stock"; ?>
    </p>

    <button onclick="handleCart(<?php echo $row['id']; ?>)">
        Add to Cart
    </button>

    <button onclick="handleWishlist(<?php echo $row['id']; ?>)">
        ❤️ Wishlist
    </button>

    <button onclick="viewProduct(<?php echo $row['id']; ?>)">
        View Details
    </button>
</div>
<?php } ?>

</section>

<script>
function handleCart(id){
    let isLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;

    if(!isLoggedIn){
        alert("Please login first");
        window.location.href = "login.php";
    } else {
        window.location.href = "add_to_cart.php?id=" + id;
    }
}

function handleWishlist(id){
    let isLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;

    if(!isLoggedIn){
        alert("Please login first");
        window.location.href = "login.php";
    } else {
        window.location.href = "add_to_wishlist.php?id=" + id;
    }
}

function viewProduct(id){
    window.location.href = "product.php?id=" + id;
}
</script>

</body>
</html> -->









<!DOCTYPE html>
<html>
<head>
    <title>ShopEasy</title>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI';
            background: #f8f9fa;
        }

        /* HEADER */
        header {
            position: sticky;
            top: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 50px;
            background: #111;
            color: white;
            z-index: 1000;
        }

        header h2 {
            margin: 0;
        }

        nav a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-size: 15px;
            transition: 0.3s;
        }

        nav a:hover {
            color: #f39c12;
        }

        .nav-btn {
            padding: 8px 15px;
            border-radius: 5px;
            background: #f39c12;
        }

        /* HERO */
        .hero {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 80px 60px;
            background: linear-gradient(to right, #ffffff, #e6ecf0);
        }

        .hero-text {
            max-width: 50%;
        }

        .hero-text h1 {
            font-size: 50px;
            margin-bottom: 10px;
        }

        .hero-text p {
            color: gray;
            margin-bottom: 20px;
        }

        .hero-text button {
            padding: 12px 25px;
            margin-right: 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-primary {
            background: black;
            color: white;
        }

        .btn-secondary {
            background: #f39c12;
            color: white;
        }

        .hero img {
            width: 420px;
            border-radius: 10px;
        }

        /* SECTION TITLE */
        .section-title {
            text-align: center;
            margin: 40px 0 10px;
            font-size: 28px;
        }

        /* PRODUCTS */
        .products {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            padding: 30px 60px;
        }

        .card {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ddd;
            text-align: center;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-8px);
        }

        .card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 8px;
        }

        .price {
            color: green;
            font-weight: bold;
        }

        .old-price {
            text-decoration: line-through;
            color: gray;
            font-size: 14px;
        }

        .btn {
            margin-top: 10px;
            padding: 10px;
            background: black;
            color: white;
            border: none;
            width: 100%;
            cursor: pointer;
            border-radius: 5px;
        }

        /* CONTACT */
        .contact {
            padding: 50px;
            background: #f4f4f4;
            text-align: center;
        }

        .contact input, .contact textarea {
            width: 300px;
            padding: 10px;
            margin: 10px;
        }

        /* FOOTER */
        footer {
            background: #111;
            color: white;
            text-align: center;
            padding: 25px;
            margin-top: 40px;
        }

        footer a {
            color: #f39c12;
            margin: 0 10px;
            text-decoration: none;
        }
    </style>
</head>

<body>

<!-- HEADER -->
<header>
    <h2>ShopEasy</h2>

    <nav>
        <a href="#">Home</a>
        <a href="#products">Products</a>
        <a href="#contact">Contact</a>

        <?php if(isset($_SESSION['user'])){ ?>
            <a href="user_dashboard.php">Dashboard</a>
            <a href="logout.php" class="nav-btn">Logout</a>
        <?php } else { ?>
            <a href="login.html">Login</a>
            <a href="signup.html" class="nav-btn">Signup</a>
        <?php } ?>
    </nav>
</header>

<!-- HERO -->
<section class="hero">
    <div class="hero-text">
        <h1>Shop Smart, Live Better</h1>
        <p>Discover amazing products at unbeatable prices.</p>

        <button class="btn-primary" onclick="scrollProducts()">Shop Now</button>
        <button class="btn-secondary" onclick="goLogin()">Get Started</button>
    </div>

    <img src="data:image/jpeg;base64,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">
</section>

<!-- PRODUCTS -->
<h2 class="section-title">🔥 Trending Products</h2>

<section class="products" id="products">

<?php
$result = mysqli_query($conn, "SELECT * FROM products LIMIT 6");

while($row = mysqli_fetch_assoc($result)){
?>
<div class="card">
    <img src="<?php echo $row['image']; ?>">

    <h3><?php echo $row['name']; ?></h3>

    <p><?php echo substr($row['description'],0,50); ?>...</p>

    <p class="price">
        ₹<?php echo $row['discount_price']; ?>
        <span class="old-price">₹<?php echo $row['price']; ?></span>
    </p>

    <button class="btn" onclick="handleBuy()">Buy Now</button>
</div>
<?php } ?>

</section>

<!-- CONTACT -->
<section class="contact" id="contact">
    <h2>Contact Us</h2>

    <form action="contact.php" method="POST">
        <input type="text" name="name" placeholder="Your Name" required><br>
        <input type="email" name="email" placeholder="Your Email" required><br>
        <textarea name="message" placeholder="Your Message"></textarea><br>
        <button class="btn">Send Message</button>
    </form>
</section>

<!-- FOOTER -->
<footer>
    <p>© 2026 ShopEasy</p>
    <p>
        <a href="#">Home</a> |
        <a href="#products">Products</a> |
        <a href="#contact">Contact</a>
    </p>
</footer>

<script>
function scrollProducts(){
    document.getElementById("products").scrollIntoView({behavior:"smooth"});
}

function goLogin(){
    window.location.href = "login.html";
}

function handleBuy(){
    alert("Please login to continue");
    window.location.href = "login.html";
}
</script>

</body>
</html>