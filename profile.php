<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user'];

$user = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'")
);
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile</title>

<style>

body{ font-family: Arial; background:#f5f5f5; margin:0; }

/* TOP BAR */
.topbar{
    display:flex;
    justify-content:space-between;
    padding:10px 20px;
    background:white;
}

.search{ width:60%; padding:8px; }
.icon{ cursor:pointer; margin-left:15px; }

/* LAYOUT */
.container{
    display:flex;
    margin:20px;
}

/* LEFT MENU */
.sidebar{
    width:220px;
    background:white;
    padding:15px;
}

.sidebar div{
    padding:10px;
    cursor:pointer;
}

.sidebar div:hover{
    background:#eee;
}

/* RIGHT CONTENT */
.content{
    flex:1;
    background:white;
    padding:20px;
    margin-left:20px;
}

/* ADDRESS */
textarea{
    width:100%;
    height:80px;
}

button{
    padding:8px 12px;
    margin-top:10px;
    cursor:pointer;
}

</style>
</head>

<body>

<!-- TOP BAR -->
<div class="topbar">
    <input class="search" placeholder="Search...">

    <div>
        <span class="icon" onclick="location.href='user_dashboard.php'">Home</span>
        <span class="icon" onclick="location.href='wishlist_page.php'">❤️</span>
        <span class="icon" onclick="location.href='cart.php'">🛒</span>
    </div>
</div>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div onclick="showTab('profile')">👤 Profile</div>
        <div onclick="showTab('address')">📍 Address</div>
        <div onclick="location.href='orders.php'">📦 Orders</div>
        <div onclick="location.href='wishlist_page.php'">❤️ Wishlist</div>
        <div onclick="showTab('help')">❓ Help Center</div>
        <div onclick="location.href='logout.php'">Logout</div>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <!-- PROFILE TAB -->
        <div id="profileTab">
            <h2>My Profile</h2>

            <p><b>Name:</b> <?php echo $user['name']; ?></p>
            <p><b>Email:</b> <?php echo $user['email']; ?></p>
            <p><b>Phone:</b> <?php echo $user['phone']; ?></p>
            <p><b>Gender:</b> <?php echo $user['gender']; ?></p>
        </div>

        <!-- ADDRESS TAB -->
        <div id="addressTab" style="display:none;">
            <h2>My Address</h2>

            <form action="save_address.php" method="POST">
                <textarea name="address" placeholder="Enter address"><?php echo $user['address']; ?></textarea>
                <br>
                <button type="submit">
                    <?php echo $user['address'] ? "Change Address" : "Save Address"; ?>
                </button>
            </form>
        </div>

        <!-- HELP TAB -->
        <div id="helpTab" style="display:none;">
            <h2>Help Center</h2>
            <p>Contact: support@myshop.com</p>
        </div>

    </div>

</div>

<script>
function showTab(tab){

    document.getElementById("profileTab").style.display = "none";
    document.getElementById("addressTab").style.display = "none";
    document.getElementById("helpTab").style.display = "none";

    if(tab == "profile") document.getElementById("profileTab").style.display = "block";
    if(tab == "address") document.getElementById("addressTab").style.display = "block";
    if(tab == "help") document.getElementById("helpTab").style.display = "block";
}
</script>

</body>
</html>