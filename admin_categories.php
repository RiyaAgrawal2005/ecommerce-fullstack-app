<?php
include 'db.php';

// DELETE CATEGORY
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM categories WHERE id='$id'");
    header("Location: admin_categories.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Categories</title>
    <link rel="stylesheet" href="admin_cat.css">
</head>
<body>

<div class="container">

    <!-- HEADER -->
    <div class="top-bar">
        <h2>📂 Manage Categories</h2>
        <a href="admin_dashboard.php" class="home-btn">🏠 Home</a>
    </div>

    <!-- ADD CATEGORY -->
    <div class="form-box">
        <h3>Add Category</h3>

        <form action="add_category.php" method="POST">
    
    <input type="text" name="name" placeholder="Category Name" required>

    <input type="text" id="imgInput" name="image" placeholder="Paste Image URL" required>

    <!-- IMAGE PREVIEW -->
    <div class="preview-box">
        <img id="previewImg" src="https://via.placeholder.com/80">
    </div>

    <button type="submit">Add Category</button>

</form>
    </div>

    <!-- CATEGORY LIST -->
    <div class="list-box">
        <h3>All Categories</h3>

        <div class="category-grid">

        <?php
        $cats = mysqli_query($conn, "SELECT * FROM categories");

        while($c = mysqli_fetch_assoc($cats)){
        ?>

        <div class="card">

            <!-- <img src="<?php echo $c['image']; ?>"
                 onerror="this.src='https://via.placeholder.com/80';"> -->

            <img 
    src="<?php echo trim($c['image']); ?>" 
    onerror="this.onerror=null; this.src='https://via.placeholder.com/70';">     

            <h4><?php echo $c['name']; ?></h4>

            <a href="?delete=<?php echo $c['id']; ?>" 
               class="delete-btn"
               onclick="return confirm('Delete this category?')">
               ❌ Delete
            </a>

        </div>

        <?php } ?>

        </div>
    </div>

</div>
<script>
let img = document.getElementById("previewImg");

img.onerror = function(){
    this.src = "https://via.placeholder.com/80";
};

document.getElementById("imgInput").addEventListener("input", function(){
    let url = this.value.trim();

    if(url !== ""){
        img.src = url;
    } else {
        img.src = "https://via.placeholder.com/80";
    }
});
</script>
</body>
</html>