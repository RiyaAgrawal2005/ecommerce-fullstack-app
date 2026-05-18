<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user'];

$orders = mysqli_query($conn, "
    SELECT * FROM orders 
    WHERE user_id='$user_id'
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>My Orders</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{
    background:#f4f7fb;
}

/* ================= HEADER ================= */

.header{
    background:#fff;
    padding:18px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
    position:sticky;
    top:0;
    z-index:100;
}

.logo{
    font-size:24px;
    font-weight:bold;
    color:#6c47ff;
}

.nav{
    display:flex;
    align-items:center;
    gap:20px;
}

.nav a{
    text-decoration:none;
    color:#333;
    font-weight:600;
    transition:0.3s;
}

.nav a:hover{
    color:#6c47ff;
}

/* ================= PAGE ================= */

.page{
    width:95%;
    margin:25px auto;
}

.top-title{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.top-title h1{
    font-size:28px;
    color:#111;
}

.order-count{
    background:#fff;
    padding:10px 18px;
    border-radius:10px;
    font-weight:600;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

/* ================= ORDER CARD ================= */

.order-card{
    background:#fff;
    border-radius:18px;
    padding:22px;
    margin-bottom:25px;
    box-shadow:0 4px 20px rgba(0,0,0,0.06);
}

/* TOP */

.order-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:15px;
    margin-bottom:18px;
}

.order-id{
    font-size:18px;
    font-weight:bold;
    color:#111;
}

.order-date{
    color:#666;
    margin-top:5px;
    font-size:14px;
}

.total{
    font-size:20px;
    font-weight:bold;
    color:#6c47ff;
}

/* STATUS */

.status{
    padding:8px 15px;
    border-radius:30px;
    font-size:13px;
    font-weight:bold;
}

.pending{
    background:#fff4de;
    color:#ff9800;
}

.packed{
    background:#e8f0ff;
    color:#2962ff;
}

.shipped{
    background:#dff3ff;
    color:#0288d1;
}

.delivered{
    background:#e6f8ea;
    color:#1b8d3e;
}

.cancelled{
    background:#ffe4e4;
    color:#d32f2f;
}

/* ================= PRODUCTS ================= */

.products{
    margin-top:15px;
}

.product{
    display:flex;
    align-items:center;
    gap:18px;
    padding:15px 0;
    border-bottom:1px solid #eee;
}

.product:last-child{
    border-bottom:none;
}

.product img{
    width:90px;
    height:90px;
    object-fit:cover;
    border-radius:12px;
    background:#f8f8f8;
}

.product-info{
    flex:1;
}

.product-info h3{
    font-size:17px;
    margin-bottom:6px;
    color:#111;
}

.product-info p{
    color:#666;
    font-size:14px;
    margin:4px 0;
}

.price{
    font-weight:bold;
    color:#111;
    font-size:18px;
}

/* ================= EXTRA ================= */

.extra{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:15px;
    margin-top:20px;
}

.extra-box{
    background:#f7f9fc;
    padding:14px;
    border-radius:12px;
}

.extra-box h4{
    margin-bottom:8px;
    color:#444;
}

.extra-box p{
    color:#666;
    font-size:14px;
    line-height:1.5;
}

/* ================= TIMELINE ================= */

.timeline{
    margin-top:25px;
    position:relative;
    display:flex;
    justify-content:space-between;
}

.timeline::before{
    content:"";
    position:absolute;
    top:14px;
    left:0;
    width:100%;
    height:4px;
    background:#e0e0e0;
    z-index:0;
    border-radius:10px;
}

.progress{
    position:absolute;
    top:14px;
    left:0;
    height:4px;
    background:#4caf50;
    z-index:1;
    border-radius:10px;
}

.step{
    position:relative;
    z-index:2;
    text-align:center;
    flex:1;
}

.circle{
    width:28px;
    height:28px;
    border-radius:50%;
    background:#ccc;
    margin:auto;
    border:5px solid #fff;
}

.step.done .circle{
    background:#4caf50;
}

.step p{
    margin-top:10px;
    font-size:13px;
    font-weight:600;
    color:#555;
}

/* ================= ACTIONS ================= */

.actions{
    display:flex;
    gap:12px;
    margin-top:25px;
    flex-wrap:wrap;
}

.btn{
    padding:12px 18px;
    border:none;
    border-radius:10px;
    cursor:pointer;
    font-weight:600;
    transition:0.3s;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    justify-content:center;
}

.btn:hover{
    transform:translateY(-2px);
}

.track-btn{
    background:#e8f0ff;
    color:#2962ff;
}

.invoice-btn{
    background:#ede7f6;
    color:#6a1b9a;
}

.cancel-btn{
    background:#ffe4e4;
    color:#d32f2f;
}

/* ================= MODAL ================= */

.modal{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.55);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:1000;
}

.modal-box{
    width:400px;
    background:#fff;
    border-radius:20px;
    padding:25px;
    position:relative;
    animation:popup 0.3s ease;
}

@keyframes popup{
    from{
        transform:scale(0.8);
        opacity:0;
    }
    to{
        transform:scale(1);
        opacity:1;
    }
}

.close{
    position:absolute;
    top:15px;
    right:18px;
    font-size:24px;
    cursor:pointer;
}

.modal h2{
    margin-bottom:20px;
}

/* VERTICAL TIMELINE */

.v-timeline{
    border-left:2px dashed #ccc;
    padding-left:25px;
}

.v-step{
    position:relative;
    margin-bottom:25px;
}

.v-step::before{
    content:"";
    position:absolute;
    width:14px;
    height:14px;
    border-radius:50%;
    background:#ccc;
    left:-33px;
    top:4px;
}

.v-step.done::before{
    background:#4caf50;
}

.v-step p{
    font-weight:bold;
    margin-bottom:4px;
}

.v-step small{
    color:#777;
}

/* ================= EMPTY ================= */

.empty{
    background:#fff;
    padding:50px;
    border-radius:18px;
    text-align:center;
    box-shadow:0 4px 20px rgba(0,0,0,0.05);
}

.empty h2{
    margin-bottom:10px;
}

.shop-btn{
    display:inline-block;
    margin-top:20px;
    background:#6c47ff;
    color:#fff;
    padding:12px 22px;
    border-radius:10px;
    text-decoration:none;
    font-weight:bold;
}

/* ================= MOBILE ================= */

@media(max-width:768px){

    .header{
        padding:15px;
    }

    .nav{
        gap:12px;
        font-size:14px;
    }

    .product{
        flex-direction:column;
        align-items:flex-start;
    }

    .product img{
        width:100%;
        height:220px;
    }

    .actions{
        flex-direction:column;
    }

    .btn{
        width:100%;
    }

    .modal-box{
        width:92%;
    }

    .timeline{
        overflow-x:auto;
        gap:30px;
    }

}

</style>
</head>

<body>

<!-- HEADER -->

<div class="header">

    <div class="logo">ShopEasy</div>

    <div class="nav">
        <a href="user_dashboard.php">🏠 Home</a>
        <a href="wishlist_page.php">❤️ Wishlist</a>
        <a href="cart.php">🛒 Cart</a>
        <a href="profile.php">👤 Profile</a>
    </div>

</div>

<div class="page">

    <div class="top-title">
        <h1>My Orders</h1>

        <div class="order-count">
            Total Orders:
            <?php echo mysqli_num_rows($orders); ?>
        </div>
    </div>

<?php if(mysqli_num_rows($orders) > 0){ ?>

<?php while($order = mysqli_fetch_assoc($orders)){ ?>

<div class="order-card">

    <!-- TOP -->

    <div class="order-top">

        <div>
            <div class="order-id">
                Order #<?php echo $order['id']; ?>
            </div>

            <div class="order-date">
                <?php echo date("d M Y, h:i A", strtotime($order['created_at'])); ?>
            </div>
        </div>

        <div class="total">
            ₹<?php echo $order['total']; ?>
        </div>

        <div class="status <?php echo strtolower($order['status']); ?>">
            <?php echo $order['status']; ?>
        </div>

    </div>

    <!-- PRODUCTS -->

    <div class="products">

    <?php
    $items = mysqli_query($conn, "
        SELECT oi.*, p.name, p.image, p.discount_price 
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ".$order['id']
    );

    while($item = mysqli_fetch_assoc($items)){
    ?>

    <div class="product">

        <img src="<?php echo $item['image']; ?>">
        <!-- <img 
src="<?php echo './' . trim($item['image']); ?>" 
onerror="this.src='https://via.placeholder.com/90';"> -->

        <div class="product-info">

            <h3><?php echo $item['name']; ?></h3>

            <p>
                Size:
                <b><?php echo $item['size']; ?></b>
            </p>

            <p>
                Quantity:
                <b><?php echo $item['qty']; ?></b>
            </p>

            <div class="price">
                ₹<?php echo $item['discount_price']; ?>
            </div>

        </div>

    </div>

    <?php } ?>

    </div>

    <!-- TIMELINE -->

<?php

$status = $order['status'];

$currentStep = 1;

if($status == "Pending") $currentStep = 1;
elseif($status == "Packed") $currentStep = 2;
elseif($status == "Shipped") $currentStep = 3;
elseif($status == "Delivered") $currentStep = 4;

?>

<div class="timeline">

    <div class="progress"
    style="width: <?php echo (($currentStep-1)/3)*100; ?>%;">
    </div>

    <div class="step <?php echo ($currentStep >= 1) ? 'done' : ''; ?>">
        <div class="circle"></div>
        <p>Placed</p>
    </div>

    <div class="step <?php echo ($currentStep >= 2) ? 'done' : ''; ?>">
        <div class="circle"></div>
        <p>Packed</p>
    </div>

    <div class="step <?php echo ($currentStep >= 3) ? 'done' : ''; ?>">
        <div class="circle"></div>
        <p>Shipped</p>
    </div>

    <div class="step <?php echo ($currentStep >= 4) ? 'done' : ''; ?>">
        <div class="circle"></div>
        <p>Delivered</p>
    </div>

</div>

    <!-- EXTRA DETAILS -->

    <div class="extra">

        <div class="extra-box">
            <h4>📍 Delivery Address</h4>

            <p>
                <?php echo $order['address']; ?><br>
                <?php echo $order['city']; ?> -
                <?php echo $order['pincode']; ?>
            </p>
        </div>

        <div class="extra-box">
            <h4>💳 Payment Details</h4>

            <p>
                Method:
                <?php echo $order['payment_method']; ?><br>

                Payment Status:
                <?php echo $order['payment_status']; ?>
            </p>
        </div>

    </div>

    <!-- ACTIONS -->

    <div class="actions">

        <?php if($order['status'] == "Pending"){ ?>

        <form method="POST" action="cancel_order.php">

            <input type="hidden"
            name="order_id"
            value="<?php echo $order['id']; ?>">

            <button class="btn cancel-btn">
                Cancel Order
            </button>

        </form>

        <?php } ?>

        <button class="btn track-btn"
        onclick="openTrack(
        '<?php echo $order['status']; ?>',
        '<?php echo $order['id']; ?>'
        )">

        Track Order

        </button>

        <a class="btn invoice-btn"
        href="invoice.php?order_id=<?php echo $order['id']; ?>">

        Download Invoice

        </a>

    </div>

</div>

<?php } ?>

<?php } else { ?>

<div class="empty">

    <h2>No Orders Yet 😢</h2>

    <p>You have not placed any order.</p>

    <a href="user_dashboard.php" class="shop-btn">
        Continue Shopping
    </a>

</div>

<?php } ?>

</div>

<!-- TRACK MODAL -->

<div class="modal" id="trackModal">

    <div class="modal-box">

        <span class="close"
        onclick="closeTrack()">&times;</span>

        <h2>Track Order</h2>

        <div class="v-timeline" id="trackContent"></div>

    </div>

</div>

<script>

function openTrack(status, orderId){

    let steps = [
        "Order Placed",
        "Packed",
        "Shipped",
        "Out for Delivery",
        "Delivered"
    ];

    let stepCount = 1;

    if(status == "Pending") stepCount = 1;
    else if(status == "Packed") stepCount = 2;
    else if(status == "Shipped") stepCount = 4;
    else if(status == "Delivered") stepCount = 5;

    let html = "";

    let now = new Date();

    for(let i = 0; i < stepCount; i++){

        let time = new Date(
            now.getTime() - (stepCount-i)*3600000
        );

        html += `
            <div class="v-step done">
                <p>${steps[i]}</p>
                <small>${time.toLocaleString()}</small>
            </div>
        `;
    }

    document.getElementById("trackContent").innerHTML = html;

    document.getElementById("trackModal").style.display = "flex";
}

function closeTrack(){
    document.getElementById("trackModal").style.display = "none";
}

</script>

</body>
</html>