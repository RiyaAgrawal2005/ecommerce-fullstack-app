<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="auth.css">

    <style>
        body { font-family: Arial; background:#f4f6f9; }

        .container {
            width: 90%;
            
            margin: 30px auto;
            
            background:white;
            padding:20px;
            border-radius:10px;
        }

        h2 { text-align:center; }

        .top-bar {
            display:flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .top-bar input {
            /* margin-top: 10px; */
            padding:8px;
            width:250px;
        }

        .top-bar a {
            padding:10px 15px;
            background:#28a745;
            color:white;
            
            text-decoration:none;
            border-radius:5px;
        }

        table {
            width:100%;
            border-collapse: collapse;
        }

        table, th, td {
            border:1px solid #ddd;
        }

        th, td {
            padding:10px;
            text-align:center;
        }

        img { width:60px; }

        .edit { background:#ffc107; padding:5px 10px; color:black; }
        .delete { background:#dc3545; padding:5px 10px; color:white; }
        header {
    background: linear-gradient(90deg, #ff7e5f, #feb47b);
    color: white;
    padding: 5px 20px;
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
    </style>
</head>
<body>

<div class="container">
    <!-- <h2>🛍️ Admin Dashboard</h2> -->
    <header>
    <h1>🧾 Products</h1>
    <nav>
        <a href="admin_dashboard.php">🏠 Home</a>
    </nav>
</header>

    <div class="top-bar">
        <input type="text" id="search" placeholder="Search product...">
        <a href="admin_product.php">+ Add Product</a>
    </div>

    <table id="productTable">
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>

        <?php
        $result = mysqli_query($conn, "SELECT * FROM products");

        while($row = mysqli_fetch_assoc($result)){
        ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><img src="<?php echo $row['image']; ?>"></td>
            <td><?php echo $row['name']; ?></td>
            <td>₹<?php echo $row['price']; ?></td>
            <td><?php echo $row['stock']; ?></td>

            <td>
                <a class="edit" href="edit_product.php?id=<?php echo $row['id']; ?>">Edit</a>
                <a class="delete" href="delete_product.php?id=<?php echo $row['id']; ?>">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

<script>
// Search filter
document.getElementById("search").addEventListener("keyup", function() {
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll("#productTable tr");

    rows.forEach((row, index) => {
        if(index === 0) return;
        row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
    });
});
</script>

</body>
</html>