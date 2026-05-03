<?php
session_start();
include 'db.php';

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

<style>
body{
    margin:0;
    font-family: Arial, sans-serif;
    background:#f1f3f6;
}

/* HEADER */
.header{
    background:#fff;
    padding:15px;
    font-size:18px;
    font-weight:bold;
    text-align:center;
    box-shadow:0 1px 5px rgba(0,0,0,0.1);
}

/* CARD */
.order-card{
    background:#fff;
    margin:10px;
    padding:12px;
    border-radius:8px;
}

/* TOP */
.top{
    display:flex;
    justify-content:space-between;
    font-size:13px;
    color:#555;
}

/* STATUS */
.status{
    font-weight:600;
}
.pending{color:#ff9800;}
.shipped{color:#2196f3;}
.delivered{color:#4caf50;}
.cancelled{color:#f44336;}

/* PRODUCT ROW */
.product{
    display:flex;
    gap:12px;
    margin-top:10px;
}

.product img{
    width:75px;
    height:75px;
    object-fit:contain;
    background:#fafafa;
    border-radius:6px;
}

.info{
    flex:1;
}

.info p{
    margin:2px 0;
    font-size:13px;
}

/* PRICE */
.price{
    font-weight:bold;
}

/* TIMELINE DOT STYLE */
/* .timeline{
    display:flex;
    align-items:center;
    margin-top:10px;
    font-size:12px;
    color:#777;
} */
.timeline {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    position: relative;
}

/* STEP */
.step {
    position: relative;
    flex: 1;
    text-align: center;
}

/* DOTTED LINE BETWEEN STEPS */
.step::after {
    content: "";
    position: absolute;
    top: 7px;
    left: 50%;
    width: 100%;
    height: 2px;

    background: repeating-linear-gradient(
        to right,
        #ccc 0px,
        #ccc 6px,
        transparent 6px,
        transparent 12px
    );

    z-index: 0;
}

/* LAST STEP NO LINE */
.step:last-child::after {
    display: none;
}

/* DONE → ONLY COLOR CHANGE (STILL DOTTED) */
.step.done::after {
    background: repeating-linear-gradient(
        to right,
        #4caf50 0px,
        #4caf50 6px,
        transparent 6px,
        transparent 12px
    );
}

/* DOT */
.circle {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #ccc;
    margin: auto;
    position: relative;
    z-index: 2;
}

/* DONE DOT */
.step.done .circle {
    background: #4caf50;
}
.modal {
    position: fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background: rgba(0,0,0,0.6);
    display:flex;
    justify-content:center;
    align-items:center;
    z-index:1000;
}

/* MODAL BOX */
.modal-box{
    background:#fff;
    width:350px;
    max-height:80vh;
    overflow-y:auto;
    padding:20px;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,0.2);
    position:relative;
    animation:fadeIn 0.3s ease;
}

/* CLOSE BUTTON */
.close{
    position:absolute;
    top:10px;
    right:12px;
    font-size:20px;
    cursor:pointer;
}

/* ANIMATION */
@keyframes fadeIn{
    from{opacity:0; transform:translateY(20px);}
    to{opacity:1; transform:translateY(0);}
}

.v-timeline{
    margin-top:15px;
    border-left:2px dashed #ccc;
    padding-left:20px;
}

/* STEP */
.v-step{
    margin-bottom:20px;
    position:relative;
}

/* DOT */
.v-step::before{
    content:"";
    position:absolute;
    left:-10px;
    top:5px;
    width:12px;
    height:12px;
    border-radius:50%;
    background: #ccc;
}

/* DONE */
.v-step.done::before{
    background: #4caf50;
}

/* TEXT */
.v-step p{
    margin:0;
    font-size:14px;
    font-weight:500;
}

.v-step small{
    color: #0c0c0c;
    font-size:11px;
}
.btn{
    padding:8px;
    border:none;
    border-radius:6px;
    font-size:13px;
    cursor:pointer;
    transition:0.3s;
}

.btn:hover{
    opacity:0.9;
}




.dot{
    width:8px;
    height:8px;
    border-radius:50%;
    margin:0 5px;
}

.done{background: #4caf50;}
.wait{background: #ccc;}

/* ADDRESS + PAYMENT */
.extra{
    margin-top:8px;
    font-size:12px;
    color:#555;
}

/* BUTTONS */
.actions{
    margin-top:10px;
    display:flex;
    gap:8px;
}

.btn{
    flex:1;
    padding:7px;
    border:none;
    border-radius:5px;
    font-size:12px;
    cursor:pointer;
}
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
.cancel{background:#ffe5e5; color:#e53935;}
.track{background:#e3f2fd; color:#1976d2;}
.invoice{background:#ede7f6; color:#6a1b9a;}
</style>

</head>
<body>

<!-- <div class="header">🧾 My Orders</div> -->
<header>
    <h1>🧾 My Orders</h1>
    <nav>
        <a href="user_dashboard.php">🏠 Home</a>
    </nav>
</header>

<?php while($order = mysqli_fetch_assoc($orders)){ ?>

<div class="order-card">

    <!-- TOP -->
    <div class="top">
        <div>
            <b>#<?php echo $order['id']; ?></b><br>
            <?php echo date("d M Y", strtotime($order['created_at'])); ?>
        </div>

        <div class="status <?php echo strtolower($order['status']); ?>">
            <p>Status: <?php echo $order['status']; ?></p>
            <!-- <?php echo $order['status']; ?> -->
        </div>
    </div>

    <!-- ITEMS -->
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

        <div class="info">
            <p><b><?php echo $item['name']; ?></b></p>
            <p>Size: <?php echo $item['size']; ?> | Qty: <?php echo $item['qty']; ?></p>
            <p class="price">₹<?php echo $item['discount_price']; ?></p>
        </div>
    </div>

    <?php } ?>

    <!-- TIMELINE -->
    
    <!-- <div class="timeline"> -->

<?php
$status = $order['status'];

$steps = ["Placed", "Packed", "Shipped", "Delivered"];

$currentStep = 0;

if($status == "Pending") $currentStep = 1;
elseif($status == "Packed") $currentStep = 2;
elseif($status == "Shipped") $currentStep = 3;
elseif($status == "Delivered") $currentStep = 4;
?>

<div class="timeline">

    <!-- PROGRESS LINE -->
    <div class="progress" style="width: <?php echo (($currentStep-1)/3)*100; ?>%;"></div>

    <?php for($i=0; $i<count($steps); $i++){ ?>
        <div class="step <?php echo ($i < $currentStep) ? 'done' : ''; ?>">
            <div class="circle"></div>
            <p><?php echo $steps[$i]; ?></p>
        </div>
    <?php } ?>

</div>

<!-- </div> -->

    <!-- EXTRA -->
    <div class="extra">
        📍 <?php echo $order['city']; ?> - <?php echo $order['pincode']; ?><br>
        💳 <?php echo $order['payment_method']; ?>
    </div>

    <!-- ACTIONS -->
    <div class="actions">

        <?php if($order['status']=="Pending"){ ?>
        <form method="POST" action="cancel_order.php" style="flex:1;">
            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
            <button class="btn cancel">Cancel</button>
        </form>
        <?php } ?>

        <!-- <button class="btn track">Track</button> -->
        <button class="btn track" 
onclick="openTrack('<?php echo $order['status']; ?>','<?php echo $order['id']; ?>')">
Track
</button>

        <a href="invoice.php?order_id=<?php echo $order['id']; ?>" style="flex:1;">
            <button class="btn invoice">Invoice</button>
        </a>

    </div>

</div>

<?php } ?>
<script>

// function openTrack(status, orderId){

//     let steps = ["Order Placed", "Shipped", "Out for Delivery", "Delivered"];

//     let html = "";

//     let now = new Date();
//     steps.forEach((step, index) => {

//         let done = false;



//     let time = new Date(now.getTime() - (steps.length-index)*3600000);    

//         if(status == "Pending" && index === 0) done = true;
//         if(status == "Shipped" && index <= 2) done = true;
//         if(status == "Delivered") done = true;



//     html += `
//         <div class="v-step ${done ? 'done' : ''}">
//             <p>${step}</p>
//             <small>${time.toLocaleString()}</small>
//         </div>
//     `;
// });

   

//     document.getElementById("trackContent").innerHTML = html;

//     // ACTION BUTTONS
//     let actionHTML = "";

//     if(status !== "Delivered"){
//         actionHTML = `
//             <form method="POST" action="cancel_order.php">
//                 <input type="hidden" name="order_id" value="${orderId}">
//                 <button class="btn cancel">Cancel Order</button>
//             </form>
//         `;
//     } else {
//         actionHTML = `
//             <button class="btn track">Return</button>
//             <button class="btn invoice">Exchange</button>
//         `;
//     }

//     document.getElementById("trackActions").innerHTML = actionHTML;

//     document.getElementById("trackModal").style.display = "block";
// }


function openTrack(status, orderId){

    let steps = ["Order Placed", "Packed", "Shipped", "Out for Delivery", "Delivered"];

    let stepCount = 0;

    if(status == "Pending") stepCount = 1;
    else if(status == "Packed") stepCount = 2;
    else if(status == "Shipped") stepCount = 4;
    else if(status == "Delivered") stepCount = 5;

    let html = "";
    let now = new Date();

    for(let i = 0; i < stepCount; i++){

        let time = new Date(now.getTime() - (stepCount-i)*3600000);

        html += `
            <div class="v-step done">
                <p>${steps[i]}</p>
                <small>${time.toLocaleString()}</small>
            </div>
        `;
    }

    document.getElementById("trackContent").innerHTML = html;

    // ACTION BUTTONS
    let actionHTML = "";

    if(status !== "Delivered"){
        actionHTML = `
            <form method="POST" action="cancel_order.php">
                <input type="hidden" name="order_id" value="${orderId}">
                <button class="btn cancel">Cancel Order</button>
            </form>
        `;
    } else {
        actionHTML = `
            <button class="btn track">Return</button>
            <button class="btn invoice">Exchange</button>
        `;
    }

    document.getElementById("trackActions").innerHTML = actionHTML;

    document.getElementById("trackModal").style.display = "flex";
}

function closeTrack(){
    document.getElementById("trackModal").style.display = "none";
}
</script>

<div id="trackModal" style="display:none;" class="modal">
    <div class="modal-box">

        <span onclick="closeTrack()" class="close">&times;</span>

        <h3>Track Order</h3>

        <div class="v-timeline" id="trackContent"></div>

        <div id="trackActions"></div>

    </div>
</div>


</body>
</html>