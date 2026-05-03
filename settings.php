<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

$id = $_SESSION['admin'];

if(isset($_POST['update'])){
    $name = $_POST['name'];
    $email = $_POST['email'];

    mysqli_query($conn, "
        UPDATE admins 
        SET name='$name', email='$email'
        WHERE id='$id'
    ");
}

$admin = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM admins WHERE id='$id'")
);
?>

<!DOCTYPE html>
<html>
<head>
<title>Settings</title>

<style>
.box{
    width:40%;
    margin:40px auto;
    background:#fff;
    padding:20px;
    border-radius:10px;
}
input{
    width:100%;
    padding:10px;
    margin:10px 0;
}
button{
    padding:10px;
    background:green;
    color:white;
    border:none;
}
</style>

</head>
<body>

<div class="box">

<h2>⚙ Admin Settings</h2>

<form method="POST">

<input type="text" name="name" value="<?php echo $admin['name']; ?>">
<input type="email" name="email" value="<?php echo $admin['email']; ?>">

<button name="update">Update</button>

</form>

</div>

</body>
</html>