<?php
include 'db.php';

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
$product = mysqli_fetch_assoc($result);
?>

<form method="POST">
    <input type="text" name="name" value="<?php echo $product['name']; ?>">
    <input type="number" name="price" value="<?php echo $product['price']; ?>">
    <input type="number" name="stock" value="<?php echo $product['stock']; ?>">
    <input type="text" name="image" value="<?php echo $product['image']; ?>">
    <button name="update">Update</button>
</form>

<?php
if(isset($_POST['update'])){
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image = $_POST['image'];

    mysqli_query($conn, "UPDATE products SET 
        name='$name', price='$price', stock='$stock', image='$image'
        WHERE id=$id");

    header("Location: admin.php");
}
?>