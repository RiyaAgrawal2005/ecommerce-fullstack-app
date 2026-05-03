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
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Wishlist</title>

<style>
header {
    background: linear-gradient(90deg, #ff7e5f, #feb47b);
    color: white;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

nav a {
    color: white;
    margin-left: 20px;
    text-decoration: none;
    font-weight: bold;
}

.products{
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
}

/* CARD */
.card{
    height: 320px;              /* FIXED HEIGHT */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 10px;
    border-radius: 10px;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    cursor: pointer;
}

/* IMAGE */
.img-box{
    position: relative;
    height: 250px;     
    width: 250px;         /* SAME IMAGE HEIGHT */
    overflow: hidden;
}

.img-box img{
    width: 100%;
    height: 100%;
    object-fit: cover;          /* VERY IMPORTANT */
    border-radius: 10px;
}

/* HEART ICON */
.wishlist-icon{
    position: absolute;
    top: 8px;
    right: 8px;
    background: white;
    padding: 5px;
    border-radius: 50%;
}

/* TEXT CONTROL */
.card h3{
    font-size: 14px;
    height: 36px;               /* FIX NAME HEIGHT */
    overflow: hidden;
}

.desc{
    font-size: 12px;
    color: gray;
    height: 32px;               /* FIX DESC HEIGHT */
    overflow: hidden;
}

.price{
    font-weight: bold;
}

.old-price{
    text-decoration: line-through;
    color: gray;
    font-size: 12px;
}

.rating{
    font-size: 13px;
    color: orange;
}
</style>

</head>
<body>

<!-- <h2>❤️ My Wishlist</h2> -->
<header>
    <h1>❤️ My Wishlist</h1>
    <nav>
        <a href="user_dashboard.php">🏠 Home</a>
    </nav>
</header>
<div class="products">

<?php while($row = mysqli_fetch_assoc($result)){ ?>
<div class="card product-card"
onclick="window.location.href='product_detail.php?id=<?php echo $row['id']; ?>'">

    <div class="img-box">

        <img src="<?php echo $row['image']; ?>">

        <!-- ❤️ Heart Icon -->
        <span class="wishlist-icon">❤️</span>

    </div>

    <h3><?php echo substr($row['name'],0,25); ?>...</h3>

    <p class="desc">
        <?php echo substr($row['description'],0,40); ?>...
    </p>

    <p class="price">
        ₹<?php echo $row['discount_price']; ?>
        <span class="old-price">₹<?php echo $row['price']; ?></span>
    </p>

    <p class="rating">⭐ 4.2</p>

</div>

<?php } ?>
</div>
</body>
</html>