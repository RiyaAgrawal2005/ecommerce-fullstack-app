<?php
include 'db.php';

$cats = mysqli_query($conn, "SELECT * FROM categories");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="auth.css">

    <style>
        body {
            background: #f4f6f9;
            font-family: Arial;
        }

        .container {
            width: 400px;
            margin: 50px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
        }

        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        img {
            width: 100%;
            margin-top: 10px;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2> Add Product</h2>

    <form action="add_product.php" method="POST">

        <input type="text" name="name" placeholder="Product Name" required>

        <textarea name="description" placeholder="Product Description" required></textarea>
<!-- 
        <select name="category" required>
            <option value="">Select Category</option>
            <option value="Electronics">Electronics</option>
            <option value="Fashion">Fashion</option>
            <option value="Beauty">Beauty</option>
            <option value="Shoes">Shoes</option>
            <option value="Books">Books</option>
        </select> -->
        <select name="category" required>
    <option value="">Select Category</option>

    <?php while($c = mysqli_fetch_assoc($cats)) { ?>
        <option value="<?php echo $c['name']; ?>">
            <?php echo ucfirst($c['name']); ?>
        </option>
    <?php } ?>

</select>

        <input type="number" name="price" placeholder="Original Price" required>

        <input type="number" name="discount_price" placeholder="Discount Price" required>

        <input type="number" name="stock" placeholder="Stock Quantity" required>

        <input type="number" step="0.1" name="rating" placeholder="Rating (1-5)" value="4">

        <input type="text" name="image" id="imageInput" placeholder="Image URL" required>

        <!-- Image Preview -->
        <img id="preview" src="" style="display:none;">

        <button type="submit">Add Product</button>
    </form>
</div>

<script>
document.getElementById("imageInput").addEventListener("input", function(){
    let url = this.value;
    let preview = document.getElementById("preview");

    if(url){
        preview.style.display = "block";
        preview.src = url;
    } else {
        preview.style.display = "none";
    }
});
</script>

</body>
</html>