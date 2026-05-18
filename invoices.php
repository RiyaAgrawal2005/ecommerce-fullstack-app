<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

/* FETCH ORDERS */

$orders = mysqli_query($conn, "
    SELECT o.*, u.name, u.email
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    ORDER BY o.id DESC
");

?>

<!DOCTYPE html>
<html>
<head>
<title>Invoices</title>

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

/* SEARCH */

.search-box{
    margin-bottom:20px;
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

/* INVOICE */

.invoice-card{
    background:white;
    border-radius:18px;
    padding:25px;
    margin-bottom:25px;
    box-shadow:0 5px 18px rgba(0,0,0,0.08);
}

/* TOP */

.invoice-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:15px;

    border-bottom:1px solid #eee;
    padding-bottom:18px;
    margin-bottom:20px;
}

.invoice-id{
    font-size:22px;
    font-weight:bold;
    color:#111;
}

.invoice-date{
    color:#777;
    margin-top:5px;
}

/* STATUS */

.badge{
    padding:8px 14px;
    border-radius:20px;
    font-size:13px;
    font-weight:bold;
    display:inline-block;
}

.pending{
    background:#fff3cd;
    color:#856404;
}

.packed{
    background:#d1ecf1;
    color:#0c5460;
}

.shipped{
    background:#d4edda;
    color:#155724;
}

.delivered{
    background:#d4edda;
    color:#155724;
}

.cancelled{
    background:#f8d7da;
    color:#721c24;
}

/* GRID */

.grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
    margin-bottom:20px;
}

/* BOX */

.box{
    background:#fafafa;
    padding:18px;
    border-radius:12px;
}

.box h3{
    margin-bottom:14px;
    color:#111;
}

.box p{
    margin-bottom:8px;
    color:#555;
    line-height:1.5;
}

/* PRODUCTS */

.product-table{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
}

.product-table th{
    background:#111827;
    color:white;
    padding:14px;
    text-align:left;
    font-size:14px;
}

.product-table td{
    padding:14px;
    border-bottom:1px solid #eee;
}

.product-box{
    display:flex;
    align-items:center;
    gap:12px;
}

.product-box img{
    width:60px;
    height:60px;
    object-fit:cover;
    border-radius:10px;
    border:1px solid #ddd;
}

/* TOTAL */

.total-box{
    margin-top:20px;
    text-align:right;
}

.total-box h2{
    color:#111;
}

/* BUTTONS */

.actions{
    display:flex;
    gap:12px;
    justify-content:flex-end;
    margin-top:20px;
    flex-wrap:wrap;
}

.btn{
    padding:12px 18px;
    border:none;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
    transition:0.3s;
}

.print-btn{
    background:#667eea;
    color:white;
}

.print-btn:hover{
    background:#5a67d8;
}

/* RESPONSIVE */

@media(max-width:900px){

    .grid{
        grid-template-columns:1fr;
    }

    .product-table{
        display:block;
        overflow-x:auto;
    }

}

</style>
</head>

<body>

<!-- HEADER -->

<div class="header">

    <h1>🧾 Invoice Management</h1>

    <a href="admin_dashboard.php" class="home-btn">
        🏠 Dashboard
    </a>

</div>

<div class="container">

<!-- SEARCH -->

<div class="search-box">

    <input type="text"
           id="search"
           placeholder="Search invoices...">

</div>

<!-- INVOICES -->

<div id="invoiceContainer">

<?php while($o = mysqli_fetch_assoc($orders)){ ?>

<div class="invoice-card">

    <!-- TOP -->

    <div class="invoice-top">

        <div>

            <div class="invoice-id">
                Invoice #<?php echo $o['id']; ?>
            </div>

            <div class="invoice-date">
                Order Date:
                <?php echo $o['created_at'] ?? date("d M Y"); ?>
            </div>

        </div>

        <div>

            <span class="badge <?php echo strtolower($o['status']); ?>">

                <?php echo $o['status']; ?>

            </span>

        </div>

    </div>

    <!-- CUSTOMER + PAYMENT -->

    <div class="grid">

        <!-- CUSTOMER -->

        <div class="box">

            <h3>👤 Customer Details</h3>

            <p>
                <b>Name:</b>
                <?php echo $o['customer_name'] ?? $o['name'] ?? 'Customer'; ?>
            </p>

            <p>
                <b>Email:</b>
                <?php echo $o['email'] ?? 'N/A'; ?>
            </p>

            <p>
                <b>Phone:</b>
                <?php echo $o['phone'] ?? 'N/A'; ?>
            </p>

            <p>
                <b>Address:</b>
                <?php echo $o['address'] ?? 'N/A'; ?>
            </p>

            <p>
                <b>City:</b>
                <?php echo $o['city'] ?? 'N/A'; ?>
            </p>

            <p>
                <b>Pincode:</b>
                <?php echo $o['pincode'] ?? 'N/A'; ?>
            </p>

        </div>

        <!-- PAYMENT -->

        <div class="box">

            <h3>💳 Payment Details</h3>

            <p>
                <b>Payment Method:</b>
                <?php echo $o['payment_method'] ?? 'COD'; ?>
            </p>

            <p>
                <b>Payment Status:</b>

                <span class="badge pending">

                    <?php echo $o['payment_status'] ?? 'Pending'; ?>

                </span>
            </p>

            <p>
                <b>Order Status:</b>

                <span class="badge <?php echo strtolower($o['status']); ?>">

                    <?php echo $o['status']; ?>

                </span>

            </p>

        </div>

    </div>

    <!-- PRODUCTS -->

    <h3 style="margin-bottom:15px;">🛍 Ordered Products</h3>

    <table class="product-table">

        <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Size</th>
            <th>Price</th>
        </tr>

<?php

$items = mysqli_query($conn, "

    SELECT oi.*, p.name, p.image, p.discount_price

    FROM order_items oi

    LEFT JOIN products p
    ON oi.product_id = p.id

    WHERE oi.order_id = ".$o['id']

);

while($item = mysqli_fetch_assoc($items)){

?>

<tr>

<td>

    <div class="product-box">

        <img src="<?php echo $item['image']; ?>">

        <div>
            <?php echo $item['name'] ?? 'Deleted Product'; ?>
        </div>

    </div>

</td>

<td>
    <?php echo $item['qty']; ?>
</td>

<td>
    <?php echo $item['size'] ?? 'N/A'; ?>
</td>

<td>
    ₹<?php echo $item['discount_price']; ?>
</td>

</tr>

<?php } ?>

    </table>

    <!-- TOTAL -->

    <div class="total-box">

        <h2>
            Total: ₹<?php echo $o['total']; ?>
        </h2>

    </div>

    <!-- BUTTONS -->

    <div class="actions">

        <button class="btn print-btn"
                onclick="window.print()">

            🖨 Print Invoice

        </button>

    </div>

</div>

<?php } ?>

</div>

</div>

<script>

/* SEARCH */

document.getElementById("search")
.addEventListener("keyup", function(){

    let value = this.value.toLowerCase();

    let invoices =
    document.querySelectorAll(".invoice-card");

    invoices.forEach(card => {

        let text = card.innerText.toLowerCase();

        card.style.display =
        text.includes(value)
        ? "block"
        : "none";

    });

});

</script>

</body>
</html>