<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

// UPDATE STOCK
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $stock = $_POST['stock'];

    mysqli_query($conn, "UPDATE products SET stock='$stock' WHERE id='$id'");
}

// FETCH PRODUCTS
$products = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
<title>Inventory</title>

<style>
table{
    width:100%;
    border-collapse:collapse;
}
td,th{
    padding:10px;
    border-bottom:1px solid #ddd;
}
input{
    width:60px;
}
button{
    padding:5px 10px;
}
</style>

</head>
<body>

<h2>📦 Inventory Management</h2>

<table>
<tr>
    <th>Product</th>
    <th>Price</th>
    <th>Stock</th>
    <th>Update</th>
</tr>

<?php while($p = mysqli_fetch_assoc($products)){ ?>
<tr>
    <td><?php echo $p['name']; ?></td>
    <td>₹<?php echo $p['discount_price']; ?></td>

    <td>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
            <input type="number" name="stock" value="<?php echo $p['stock']; ?>">
    </td>

    <td>
            <button name="update">Save</button>
        </form>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>