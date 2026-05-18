<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

/* UPDATE STOCK */

if(isset($_POST['update'])){

    $id = $_POST['id'];
    $stock = $_POST['stock'];

    mysqli_query(
        $conn,
        "UPDATE products SET stock='$stock' WHERE id='$id'"
    );

}

/* FETCH PRODUCTS */

$products = mysqli_query(
    $conn,
    "SELECT * FROM products ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html>
<head>
<title>Inventory Management</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
}

body{
    background:#f4f7fb;
}

/* HEADER */

.header{
    background:linear-gradient(90deg,#667eea,#764ba2);
    color:white;
    padding:18px 30px;

    display:flex;
    justify-content:space-between;
    align-items:center;
}

.header h1{
    font-size:24px;
}

.home-btn{
    text-decoration:none;
    background:white;
    color:#667eea;
    padding:10px 18px;
    border-radius:8px;
    font-weight:bold;
    transition:0.3s;
}

.home-btn:hover{
    background:#ececff;
}

/* CONTAINER */

.container{
    width:95%;
    margin:25px auto;
}

/* TOP BAR */

.top-bar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
    gap:15px;
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
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
    outline:none;
    font-size:14px;
}

/* TABLE */

.table-box{
    background:white;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 5px 18px rgba(0,0,0,0.08);
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#111827;
    color:white;
    padding:16px;
    text-align:left;
    font-size:14px;
}

td{
    padding:16px;
    border-bottom:1px solid #eee;
    vertical-align:middle;
}

tr:hover{
    background:#fafafa;
}

/* PRODUCT */

.product-box{
    display:flex;
    align-items:center;
    gap:12px;
}

.product-box img{
    width:65px;
    height:65px;
    object-fit:cover;
    border-radius:10px;
    border:1px solid #ddd;
}

.product-name{
    font-weight:600;
    color:#111;
    margin-bottom:5px;
}

/* PRICE */

.price{
    font-weight:bold;
    color:#111;
}

/* STOCK */

.stock-input{
    width:90px;
    padding:10px;
    border:1px solid #ddd;
    border-radius:8px;
    outline:none;
    text-align:center;
}

.stock-input:focus{
    border-color:#667eea;
}

/* STATUS BADGES */

.badge{
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
    display:inline-block;
}

.in-stock{
    background:#d4edda;
    color:#155724;
}

.low-stock{
    background:#fff3cd;
    color:#856404;
}

.out-stock{
    background:#f8d7da;
    color:#721c24;
}

/* BUTTON */

.save-btn{
    padding:10px 16px;
    border:none;
    border-radius:8px;
    background:#667eea;
    color:white;
    cursor:pointer;
    font-weight:bold;
    transition:0.3s;
}

.save-btn:hover{
    background:#5a67d8;
}

/* SUMMARY CARDS */

.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-bottom:25px;
}

.card{
    background:white;
    padding:22px;
    border-radius:16px;
    box-shadow:0 4px 14px rgba(0,0,0,0.08);
}

.card h3{
    color:#666;
    margin-bottom:10px;
    font-size:15px;
}

.card p{
    font-size:28px;
    font-weight:bold;
    color:#111;
}

/* RESPONSIVE */

@media(max-width:900px){

    table{
        display:block;
        overflow-x:auto;
    }

}

</style>
</head>

<body>

<!-- HEADER -->

<div class="header">

    <h1>📦 Inventory Management</h1>

    <a href="admin_dashboard.php" class="home-btn">
        🏠 Dashboard
    </a>

</div>

<div class="container">

<?php

$totalProducts = mysqli_num_rows($products);

$lowStock = mysqli_num_rows(
    mysqli_query($conn,
    "SELECT * FROM products WHERE stock < 5 AND stock > 0")
);

$outStock = mysqli_num_rows(
    mysqli_query($conn,
    "SELECT * FROM products WHERE stock = 0")
);

?>

<!-- SUMMARY -->

<div class="cards">

    <div class="card">
        <h3>Total Products</h3>
        <p><?php echo $totalProducts; ?></p>
    </div>

    <div class="card">
        <h3>Low Stock</h3>
        <p><?php echo $lowStock; ?></p>
    </div>

    <div class="card">
        <h3>Out Of Stock</h3>
        <p><?php echo $outStock; ?></p>
    </div>

</div>

<!-- SEARCH -->

<div class="top-bar">

    <div class="search-box">

        <input type="text"
               id="search"
               placeholder="Search products...">

    </div>

</div>

<!-- TABLE -->

<div class="table-box">

<table id="inventoryTable">

<tr>
    <th>ID</th>
    <th>Product</th>
    <th>Price</th>
    <th>Status</th>
    <th>Stock</th>
    <th>Update</th>
</tr>

<?php while($p = mysqli_fetch_assoc($products)){ ?>

<tr>

    <td>
        #<?php echo $p['id']; ?>
    </td>

    <td>

        <div class="product-box">

            <img src="<?php echo $p['image']; ?>">

            <div>

                <div class="product-name">
                    <?php echo $p['name']; ?>
                </div>

                <small>
                    <?php echo $p['category']; ?>
                </small>

            </div>

        </div>

    </td>

    <td class="price">
        ₹<?php echo $p['discount_price']; ?>
    </td>

    <td>

        <?php

        if($p['stock'] == 0){

            echo "<span class='badge out-stock'>
                    Out of Stock
                  </span>";

        }

        elseif($p['stock'] < 5){

            echo "<span class='badge low-stock'>
                    Low Stock
                  </span>";

        }

        else{

            echo "<span class='badge in-stock'>
                    In Stock
                  </span>";

        }

        ?>

    </td>

    <td>

        <form method="POST">

            <input type="hidden"
                   name="id"
                   value="<?php echo $p['id']; ?>">

            <input type="number"
                   name="stock"
                   class="stock-input"
                   value="<?php echo $p['stock']; ?>">

    </td>

    <td>

            <button class="save-btn"
                    name="update">

                    Save

            </button>

        </form>

    </td>

</tr>

<?php } ?>

</table>

</div>

</div>

<script>

/* SEARCH */

document.getElementById("search")
.addEventListener("keyup", function(){

    let value = this.value.toLowerCase();

    let rows = document.querySelectorAll("#inventoryTable tr");

    rows.forEach((row,index)=>{

        if(index === 0) return;

        row.style.display =
        row.innerText.toLowerCase().includes(value)
        ? ""
        : "none";

    });

});

</script>

</body>
</html>