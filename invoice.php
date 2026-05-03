<?php
include 'db.php';

$order_id = $_GET['order_id'];

$order = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM orders WHERE id='$order_id'")
);

$items = mysqli_query($conn, "
    SELECT oi.*, p.name, p.discount_price 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id='$order_id'
");
?>

<h2>Invoice - Order #<?php echo $order_id; ?></h2>

<p>Total: ₹<?php echo $order['total']; ?></p>

<hr>

<?php while($item = mysqli_fetch_assoc($items)){ ?>
    <p>
        <?php echo $item['name']; ?>  
        (Qty: <?php echo $item['qty']; ?>)  
        - ₹<?php echo $item['discount_price']; ?>
    </p>
<?php } ?>

<hr>

<button onclick="window.print()">Download PDF</button>