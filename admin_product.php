



























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
            width: 450px;
            margin: 10px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        textarea{
            resize: none;
            height: 90px;
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
            margin-top: 10px;
        }

        button:hover {
            background: #0056b3;
        }

        img {
            width: 100%;
            margin-top: 10px;
            border-radius: 6px;
            display: none;
            height: 250px;
            object-fit: cover;
            border: 1px solid #ddd;
        }

        .file-box{
            border: 2px dashed #bbb;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            background: #fafafa;
        }

        .file-box input{
            border: none;
        }
    </style>
</head>
<body>

<div class="container">

    <h2>🛒 Add Product</h2>

    <!-- IMPORTANT -->
    <form action="add_product.php" method="POST" enctype="multipart/form-data">

        <input type="text" name="name" placeholder="Product Name" required>

        <textarea name="description" placeholder="Product Description" required></textarea>

       


<label><b>Select Categories</b></label>

<select name="category[]" multiple required size="6">
    <!-- <option value="">Select Category</option> -->

    <?php while($c = mysqli_fetch_assoc($cats)) { ?>

        <option value="<?php echo $c['name']; ?>">

            <?php echo ucfirst($c['name']); ?>

        </option>

    <?php } ?>

</select>

<p style="color:gray;font-size:13px;">
Hold CTRL key to select multiple categories
</p>


        <input type="number" name="price" placeholder="Original Price" required>

        <input type="number" name="discount_price" placeholder="Discount Price" required>

        <input type="number" name="stock" placeholder="Stock Quantity" required>

        <input type="number" step="0.1" name="rating" placeholder="Rating (1-5)" value="4">

        <!-- FILE UPLOAD -->
        <div class="file-box">
            <label><b>Upload Product Image</b></label><br><br>

            <input 
                type="file" 
                name="image" 
                id="imageInput"
                accept="image/*"
                required
            ><br><br>

    <!-- OPTIONAL IMAGES -->

    <!-- <p>Image 2 (Optional)</p> -->

    <input 
        type="file"
        name="image2"
        accept="image/*"
    ><br><br>

    <!-- <p>Image 3 (Optional)</p> -->

    <input 
        type="file"
        name="image3"
        accept="image/*"
    ><br><br>

    <!-- <p>Image 4 (Optional)</p> -->

    <input 
        type="file"
        name="image4"
        accept="image/*"
    ><br><br>

    <!-- <p>Image 5 (Optional)</p> -->

    <input 
        type="file"
        name="image5"
        accept="image/*"
    >

            
        </div>

        <!-- IMAGE PREVIEW -->
        <img id="preview">

        <button type="submit">Add Product</button>

    </form>
</div>

<script>

document.getElementById("imageInput")
.addEventListener("change", function(e){

    let file = e.target.files[0];

    let preview = document.getElementById("preview");

    if(file){

        preview.style.display = "block";

        preview.src = URL.createObjectURL(file);

    }

});

</script>

</body>
</html>