<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user'];

$user = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'")
);

/* TOTAL ORDERS */
$totalOrders = mysqli_fetch_row(
    mysqli_query($conn,
    "SELECT COUNT(*) FROM orders WHERE user_id='$user_id'")
)[0];

/* TOTAL SPENT */
$totalSpent = mysqli_fetch_row(
    mysqli_query($conn,
    "SELECT SUM(total) FROM orders 
    WHERE user_id='$user_id' 
    AND status='Delivered'")
)[0] ?? 0;

/* WISHLIST */
$wishlistCount = 0;

$wishlistCheck = mysqli_query($conn,
"SHOW TABLES LIKE 'wishlist'");

if(mysqli_num_rows($wishlistCheck) > 0){

    $wishlistCount = mysqli_fetch_row(
        mysqli_query($conn,
        "SELECT COUNT(*) FROM wishlist WHERE user_id='$user_id'")
    )[0];
}

/* RECENT ORDER */

$recentOrder = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT * FROM orders 
    WHERE user_id='$user_id'
    ORDER BY id DESC LIMIT 1")
);
?>

<!DOCTYPE html>
<html>
<head>

<title>My Profile</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial;
}

body{
    background:#f3f6fb;
}

/* MAIN LAYOUT */

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
    font-size:28px;
    font-weight:bold;
    margin-bottom:30px;
}

/* PROFILE CARD */

.profile-card{
    text-align:center;
    background:rgba(255,255,255,0.08);
    padding:22px;
    border-radius:18px;
    margin-bottom:30px;
}

.profile-card img{
    width:95px;
    height:95px;
    border-radius:50%;
    border:4px solid #8b5cf6;
    margin-bottom:12px;
}

.profile-card h3{
    font-size:22px;
}

.profile-card p{
    font-size:14px;
    color:#d1d5db;
    margin-top:6px;
}

/* MENU */

.menu{
    list-style:none;
}

.menu li{
    margin-bottom:12px;
}

.menu li a{
    display:block;
    padding:14px;
    color:white;
    text-decoration:none;
    border-radius:12px;
    transition:0.3s;
}

.menu li a:hover,
.menu li.active a{
    background:#8b5cf6;
}

/* MAIN CONTENT */

.main{
    flex:1;
    padding:30px;
}

/* TOPBAR */

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
    gap:20px;
}

.search-box{
    flex:1;
}

.search-box input{
    width:100%;
    padding:15px;
    border:none;
    border-radius:14px;
    background:white;
    box-shadow:0 3px 10px rgba(0,0,0,0.05);
}

.actions{
    display:flex;
    gap:12px;
}

.actions a{
    text-decoration:none;
    background:white;
    padding:12px 16px;
    border-radius:12px;
    color:#111;
    font-weight:bold;
    box-shadow:0 3px 10px rgba(0,0,0,0.05);
}

/* COVER */

.cover{
    background:linear-gradient(135deg,#7c3aed,#4f46e5);
    border-radius:22px;
    padding:35px;
    color:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
    flex-wrap:wrap;
    gap:20px;
}

.cover-left{
    display:flex;
    align-items:center;
    gap:20px;
}

.cover-left img{
    width:110px;
    height:110px;
    border-radius:50%;
    border:5px solid rgba(255,255,255,0.3);
}

.cover h1{
    font-size:34px;
}

.cover p{
    margin-top:6px;
    opacity:0.9;
}

.edit-btn{
    background:white;
    color:#7c3aed;
    padding:14px 20px;
    border-radius:12px;
    text-decoration:none;
    font-weight:bold;
}

/* STATS */

.stats{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:22px;
    margin-bottom:30px;
}

.stat-card{
    background:white;
    border-radius:18px;
    padding:25px;
    box-shadow:0 4px 14px rgba(0,0,0,0.05);
}

.stat-card p{
    color:#666;
}

.stat-card h2{
    margin-top:10px;
    color:#7c3aed;
    font-size:30px;
}

/* CONTENT GRID */

.grid{
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:25px;
}

.box{
    background:white;
    border-radius:18px;
    padding:25px;
    box-shadow:0 4px 14px rgba(0,0,0,0.05);
}

/* TITLES */

.box-title{
    font-size:22px;
    margin-bottom:20px;
}

/* DETAILS */

.detail-row{
    display:flex;
    justify-content:space-between;
    padding:15px 0;
    border-bottom:1px solid #eee;
    gap:20px;
}

.detail-row:last-child{
    border-bottom:none;
}

.label{
    color:#666;
    font-weight:bold;
}

.value{
    color:#111;
}

/* ADDRESS */

textarea{
    width:100%;
    height:120px;
    border:1px solid #ddd;
    border-radius:12px;
    padding:15px;
    resize:none;
    margin-top:15px;
    font-size:15px;
}

button{
    margin-top:15px;
    padding:14px 20px;
    border:none;
    background:#7c3aed;
    color:white;
    border-radius:12px;
    cursor:pointer;
    font-weight:bold;
    font-size:15px;
}

/* BADGES */

.badge{
    display:inline-block;
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:bold;
}

.pending{
    background:#fff3cd;
    color:#856404;
}

.shipped{
    background:#d1ecf1;
    color:#0c5460;
}

.delivered{
    background:#d4edda;
    color:#155724;
}

.cancelled{
    background:#f8d7da;
    color:#721c24;
}

/* HELP */

.help-box p{
    margin-bottom:12px;
    color:#555;
}

/* RESPONSIVE */

@media(max-width:950px){

    .dashboard{
        flex-direction:column;
    }

    .sidebar{
        width:100%;
        height:auto;
        position:relative;
    }

    .grid{
        grid-template-columns:1fr;
    }

    .cover{
        flex-direction:column;
        align-items:flex-start;
    }

    .topbar{
        flex-direction:column;
        align-items:stretch;
    }
}

</style>
</head>

<body>

<div class="dashboard">

    <!-- SIDEBAR -->

    <div class="sidebar">

        <div class="logo">🛍 ShopEasy</div>

        <div class="profile-card">

            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['name']); ?>&background=8b5cf6&color=fff">

            <h3><?php echo $user['name']; ?></h3>

            <p><?php echo $user['email']; ?></p>

        </div>

        <ul class="menu">

            <li>
                <a href="user_dashboard.php">🏠 Dashboard</a>
            </li>

            <li class="active">
                <a href="#">👤 My Profile</a>
            </li>

            <li>
                <a href="orders.php">📦 Orders</a>
            </li>

            <li>
                <a href="wishlist_page.php">❤️ Wishlist</a>
            </li>

            <li>
                <a href="cart.php">🛒 Cart</a>
            </li>

            <li>
                <a href="#">💳 Payments</a>
            </li>

            <li>
                <a href="#">🔔 Notifications</a>
            </li>

            <li>
                <a href="#">⚙ Settings</a>
            </li>

            <li>
    <a href="privacy.php">🔒 Privacy Policy</a>
</li>

<li>
    <a href="terms.php">📄 Terms & Conditions</a>
</li>

<li>
    <a href="refund.php">↩ Refund Policy</a>
</li>

<li>
    <a href="contact.php">📞 Contact Us</a>
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

            <div class="search-box">

                <input type="text" placeholder="Search products, orders...">

            </div>

            <div class="actions">

                <a href="wishlist_page.php">❤️ Wishlist</a>

                <a href="cart.php">🛒 Cart</a>

            </div>

        </div>

        <!-- COVER -->

        <div class="cover">

            <div class="cover-left">

                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['name']); ?>&background=ffffff&color=7c3aed">

                <div>

                    <h1>
                        <?php echo $user['name']; ?>
                    </h1>

                    <p>
                        Premium Customer • ShopEasy Member
                    </p>

                </div>

            </div>

            <a href="#" class="edit-btn">
                ✏ Edit Profile
            </a>

        </div>

        <!-- STATS -->

        <div class="stats">

            <div class="stat-card">

                <p>Total Orders</p>

                <h2><?php echo $totalOrders; ?></h2>

            </div>

            <div class="stat-card">

                <p>Total Spent</p>

                <h2>₹<?php echo $totalSpent; ?></h2>

            </div>

            <div class="stat-card">

                <p>Wishlist Items</p>

                <h2><?php echo $wishlistCount; ?></h2>

            </div>

        </div>

        <!-- GRID -->

        <div class="grid">

            <!-- LEFT -->

            <div>

                <!-- PROFILE DETAILS -->

                <div class="box">

                    <h2 class="box-title">
                        👤 Personal Information
                    </h2>

                    <div class="detail-row">

                        <div class="label">Full Name</div>

                        <div class="value">
                            <?php echo $user['name']; ?>
                        </div>

                    </div>

                    <div class="detail-row">

                        <div class="label">Email Address</div>

                        <div class="value">
                            <?php echo $user['email']; ?>
                        </div>

                    </div>

                    <div class="detail-row">

                        <div class="label">Phone Number</div>

                        <div class="value">
                            <?php echo $user['phone'] ?: 'Not Added'; ?>
                        </div>

                    </div>

                    <div class="detail-row">

                        <div class="label">Gender</div>

                        <div class="value">
                            <?php echo $user['gender'] ?: 'Not Selected'; ?>
                        </div>

                    </div>

                    <div class="detail-row">

                        <div class="label">Account Status</div>

                        <div class="value">
                            Active ✅
                        </div>

                    </div>

                </div>

                <!-- ADDRESS -->

                <div class="box" style="margin-top:25px;">

                    <h2 class="box-title">
                        📍 Saved Address
                    </h2>

                    <form action="save_address.php" method="POST">

                        <textarea 
                        name="address"
                        placeholder="Enter your address"><?php echo $user['address']; ?></textarea>

                        <button type="submit">

                            <?php
                            echo !empty($user['address'])
                            ? "Update Address"
                            : "Save Address";
                            ?>

                        </button>

                    </form>

                </div>

            </div>

            <!-- RIGHT -->

            <div>

                <!-- RECENT ORDER -->

                <div class="box">

                    <h2 class="box-title">
                        📦 Recent Order
                    </h2>

                    <?php if($recentOrder){ ?>

                    <div class="detail-row">

                        <div class="label">Order ID</div>

                        <div class="value">
                            #<?php echo $recentOrder['id']; ?>
                        </div>

                    </div>

                    <div class="detail-row">

                        <div class="label">Amount</div>

                        <div class="value">
                            ₹<?php echo $recentOrder['total']; ?>
                        </div>

                    </div>

                    <div class="detail-row">

                        <div class="label">Payment</div>

                        <div class="value">
                            <?php echo $recentOrder['payment_method']; ?>
                        </div>

                    </div>

                    <div class="detail-row">

                        <div class="label">Status</div>

                        <div class="value">

                            <span class="badge <?php echo strtolower($recentOrder['status']); ?>">

                                <?php echo $recentOrder['status']; ?>

                            </span>

                        </div>

                    </div>

                    <?php } else { ?>

                    <p>No orders yet.</p>

                    <?php } ?>

                </div>

                <!-- HELP -->

                <div class="box help-box" style="margin-top:25px;">

                    <h2 class="box-title">
                        ❓ Help & Support
                    </h2>

                    <p>
                        📧 support@shopeasy.com
                    </p>

                    <p>
                        📞 +91 9876543210
                    </p>

                    <p>
                        🕒 Mon - Sat : 9AM - 8PM
                    </p>

                    <button>
                        Contact Support
                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>