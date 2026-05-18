<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

$id = $_SESSION['admin'];

/* =========================
   UPDATE PROFILE
========================= */

if(isset($_POST['update'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    mysqli_query($conn,"
        UPDATE admins
        SET
        name='$name',
        email='$email',
        phone='$phone'
        WHERE id='$id'
    ");

    $success = "Profile Updated Successfully ✅";
}

/* =========================
   CHANGE PASSWORD
========================= */

if(isset($_POST['change_password'])){

    $current = $_POST['current_password'];
    $new = $_POST['new_password'];

    $check = mysqli_query($conn,
    "SELECT * FROM admins
     WHERE id='$id'
     AND password='$current'");

    if(mysqli_num_rows($check) > 0){

        mysqli_query($conn,"
            UPDATE admins
            SET password='$new'
            WHERE id='$id'
        ");

        $passwordSuccess = "Password Changed Successfully ✅";

    }else{
        $passwordError = "Current Password Incorrect ❌";
    }
}

/* =========================
   FETCH ADMIN
========================= */

$admin = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT * FROM admins WHERE id='$id'")
);
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Settings</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial;
}

body{
    background:#f4f6f9;
}

/* HEADER */

.header{
    background:linear-gradient(90deg,#667eea,#764ba2);
    padding:18px 30px;
    color:white;

    display:flex;
    justify-content:space-between;
    align-items:center;
}

.header h2{
    font-size:24px;
}

.home-btn{
    text-decoration:none;
    background:white;
    color:#667eea;
    padding:10px 16px;
    border-radius:8px;
    font-weight:bold;
}

/* CONTAINER */

.container{
    width:95%;
    max-width:1200px;
    margin:30px auto;

    display:grid;
    grid-template-columns:320px 1fr;
    gap:25px;
}

/* PROFILE CARD */

.profile-card{
    background:white;
    border-radius:18px;
    padding:30px;
    text-align:center;

    box-shadow:0 4px 15px rgba(0,0,0,0.08);
}

.profile-card img{
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
    margin-bottom:15px;
}

.profile-card h3{
    margin-bottom:5px;
}

.profile-card p{
    color:gray;
    font-size:14px;
    margin-bottom:10px;
}

.badge{
    display:inline-block;
    background:#dbeafe;
    color:#2563eb;
    padding:6px 14px;
    border-radius:20px;
    font-size:13px;
    font-weight:bold;
}

/* SETTINGS BOX */

.settings-box{
    background:white;
    border-radius:18px;
    padding:30px;

    box-shadow:0 4px 15px rgba(0,0,0,0.08);
}

.section-title{
    margin-bottom:20px;
    font-size:22px;
    color:#111827;
}

/* FORM */

.form-group{
    margin-bottom:18px;
}

label{
    display:block;
    margin-bottom:8px;
    font-weight:bold;
    color:#374151;
}

input{
    width:100%;
    padding:14px;
    border:1px solid #ddd;
    border-radius:10px;
    font-size:15px;
    outline:none;
}

input:focus{
    border-color:#667eea;
}

/* BUTTON */

button{
    background:linear-gradient(90deg,#667eea,#764ba2);
    color:white;
    border:none;
    padding:14px 22px;
    border-radius:10px;
    font-size:15px;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform:translateY(-2px);
}

/* ALERTS */

.success{
    background:#dcfce7;
    color:#166534;
    padding:12px;
    border-radius:10px;
    margin-bottom:18px;
}

.error{
    background:#fee2e2;
    color:#991b1b;
    padding:12px;
    border-radius:10px;
    margin-bottom:18px;
}

/* PASSWORD BOX */

.password-box{
    margin-top:40px;
    padding-top:30px;
    border-top:1px solid #eee;
}

/* RESPONSIVE */

@media(max-width:900px){

    .container{
        grid-template-columns:1fr;
    }

}

</style>
</head>
<body>

<!-- HEADER -->

<div class="header">

    <h2>⚙ Admin Settings</h2>

    <a href="admin_dashboard.php" class="home-btn">
        🏠 Dashboard
    </a>

</div>

<!-- CONTAINER -->

<div class="container">

    <!-- PROFILE CARD -->

    <div class="profile-card">

        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($admin['name']); ?>&background=667eea&color=fff&size=200">

        <h3><?php echo $admin['name']; ?></h3>

        <p><?php echo $admin['email']; ?></p>

        <span class="badge">
            Super Admin
        </span>

        <div style="margin-top:25px;text-align:left;">

            <p style="margin-bottom:10px;">
                📧 <b>Email:</b><br>
                <?php echo $admin['email']; ?>
            </p>

            <p style="margin-bottom:10px;">
                📱 <b>Phone:</b><br>
                <?php echo $admin['phone'] ?? 'Not Added'; ?>
            </p>

            <p>
                🛒 <b>Role:</b><br>
                Ecommerce Administrator
            </p>

        </div>

    </div>

    <!-- SETTINGS -->

    <div class="settings-box">

        <h2 class="section-title">
            👤 Profile Information
        </h2>

        <?php if(isset($success)){ ?>
            <div class="success">
                <?php echo $success; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <div class="form-group">
                <label>Full Name</label>

                <input
                type="text"
                name="name"
                value="<?php echo $admin['name']; ?>"
                required>
            </div>

            <div class="form-group">
                <label>Email Address</label>

                <input
                type="email"
                name="email"
                value="<?php echo $admin['email']; ?>"
                required>
            </div>

            <div class="form-group">
                <label>Phone Number</label>

                <input
                type="text"
                name="phone"
                value="<?php echo $admin['phone'] ?? ''; ?>"
                placeholder="Enter phone number">
            </div>

            <button name="update">
                Save Changes
            </button>

        </form>

        <!-- PASSWORD SECTION -->

        <div class="password-box">

            <h2 class="section-title">
                🔒 Change Password
            </h2>

            <?php if(isset($passwordSuccess)){ ?>
                <div class="success">
                    <?php echo $passwordSuccess; ?>
                </div>
            <?php } ?>

            <?php if(isset($passwordError)){ ?>
                <div class="error">
                    <?php echo $passwordError; ?>
                </div>
            <?php } ?>

            <form method="POST">

                <div class="form-group">
                    <label>Current Password</label>

                    <input
                    type="password"
                    name="current_password"
                    required>
                </div>

                <div class="form-group">
                    <label>New Password</label>

                    <input
                    type="password"
                    name="new_password"
                    required>
                </div>

                <button name="change_password">
                    Update Password
                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>