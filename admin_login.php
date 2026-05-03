<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>

<form method="POST">
    <h2>Admin Login</h2>

    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>

    <button name="login">Login</button>
</form>

<?php
if(isset($_POST['login'])){
    if($_POST['username'] == "admin" && $_POST['password'] == "1234"){
        $_SESSION['admin'] = true;
        header("Location: admin_dashboard.php");
    } else {
        echo "Invalid Login";
    }
}
?>

</body>
</html>