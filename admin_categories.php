<?php
include 'db.php';

/* DELETE CATEGORY */
if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    mysqli_query($conn, "DELETE FROM categories WHERE id='$id'");

    header("Location: admin_categories.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
}

body{
    background:#f4f7fb;
}

/* HEADER */

.header{
    background:linear-gradient(90deg,#667eea,#764ba2);
    color:white;
    padding:18px 30px;

    display:flex;
    justify-content:space-between;
    align-items:center;
}

.header h1{
    font-size:24px;
}

.home-btn{
    text-decoration:none;
    background:white;
    color:#667eea;
    padding:10px 18px;
    border-radius:8px;
    font-weight:bold;
    transition:0.3s;
}

.home-btn:hover{
    background:#ececff;
}

/* MAIN */

.container{
    width:95%;
    margin:25px auto;
}

/* GRID */

.main-grid{
    display:grid;
    grid-template-columns:320px 1fr;
    gap:25px;
}

/* CARD */

.card{
    background:white;
    border-radius:16px;
    padding:22px;
    box-shadow:0 5px 18px rgba(0,0,0,0.08);
}

/* FORM */

.form-title{
    margin-bottom:20px;
    font-size:20px;
    color:#111;
}

.form-group{
    margin-bottom:16px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
    color:#444;
}

input[type="text"],
input[type="file"]{
    width:100%;
    padding:13px;
    border:1px solid #ddd;
    border-radius:10px;
    outline:none;
    font-size:14px;
}

input:focus{
    border-color:#667eea;
}

/* IMAGE PREVIEW */

.preview-box{
    margin-top:15px;
    text-align:center;
}

.preview-box img{
    width:120px;
    height:120px;
    object-fit:cover;
    border-radius:14px;
    border:2px dashed #ccc;
    padding:4px;
}

/* BUTTON */

.submit-btn{
    width:100%;
    padding:14px;
    border:none;
    border-radius:10px;
    background:#667eea;
    color:white;
    font-size:15px;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

.submit-btn:hover{
    background:#5a67d8;
}

/* SEARCH */

.search-box{
    margin-bottom:20px;
}

.search-box input{
    width:100%;
    padding:14px;
    border:none;
    border-radius:12px;
    background:white;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
    outline:none;
}

/* CATEGORY GRID */

.category-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
    gap:20px;
}

/* CATEGORY CARD */

.category-card{
    background:white;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 4px 14px rgba(0,0,0,0.08);
    transition:0.3s;
}

.category-card:hover{
    transform:translateY(-5px);
}

.category-card img{
    width:100%;
    height:180px;
    object-fit:cover;
}

.category-content{
    padding:16px;
}

.category-content h3{
    margin-bottom:14px;
    color:#111;
}

.delete-btn{
    display:inline-block;
    text-decoration:none;
    background:#dc3545;
    color:white;
    padding:10px 14px;
    border-radius:8px;
    font-size:14px;
    font-weight:bold;
    transition:0.3s;
}

.delete-btn:hover{
    background:#c82333;
}

/* EMPTY */

.empty{
    text-align:center;
    padding:50px;
    color:#666;
}

/* RESPONSIVE */

@media(max-width:900px){

    .main-grid{
        grid-template-columns:1fr;
    }

}

</style>
</head>

<body>

<!-- HEADER -->

<div class="header">

    <h1>📂 Categories Management</h1>

    <a href="admin_dashboard.php" class="home-btn">
        🏠 Dashboard
    </a>

</div>

<div class="container">

    <div class="main-grid">

        <!-- LEFT SIDE FORM -->

        <div class="card">

            <h2 class="form-title">Add New Category</h2>

            <form action="add_category.php"
                  method="POST"
                  enctype="multipart/form-data">

                <div class="form-group">
                    <label>Category Name</label>

                    <input type="text"
                           name="name"
                           placeholder="Enter category name"
                           required>
                </div>

                <div class="form-group">
                    <label>Upload Image</label>

                    <input type="file"
                           name="image"
                           id="imageInput"
                           accept="image/*"
                           required>
                </div>

                <!-- PREVIEW -->

                <div class="preview-box">

                    <img id="previewImg"
                    src="https://via.placeholder.com/120">

                </div>

                <br>

                <button type="submit" class="submit-btn">
                    + Add Category
                </button>

            </form>

        </div>

        <!-- RIGHT SIDE -->

        <div>

            <!-- SEARCH -->

            <div class="search-box">

                <input type="text"
                       id="search"
                       placeholder="Search categories...">

            </div>

            <!-- CATEGORY GRID -->

            <div class="category-grid" id="categoryGrid">

                <?php
                $cats = mysqli_query(
                    $conn,
                    "SELECT * FROM categories ORDER BY id DESC"
                );

                if(mysqli_num_rows($cats) > 0){

                    while($c = mysqli_fetch_assoc($cats)){
                ?>

                <div class="category-card">

                    <img
                    src="<?php echo $c['image']; ?>"
                    onerror="this.src='https://via.placeholder.com/250';">

                    <div class="category-content">

                        <h3>
                            <?php echo $c['name']; ?>
                        </h3>

                        <a href="?delete=<?php echo $c['id']; ?>"
                           class="delete-btn"
                           onclick="return confirm('Delete this category?')">

                           ❌ Delete

                        </a>

                    </div>

                </div>

                <?php
                    }

                } else {
                ?>

                <div class="empty">
                    No categories added yet.
                </div>

                <?php } ?>

            </div>

        </div>

    </div>

</div>

<script>

/* IMAGE PREVIEW */

document.getElementById("imageInput")
.addEventListener("change", function(e){

    let file = e.target.files[0];

    if(file){

        let reader = new FileReader();

        reader.onload = function(event){

            document.getElementById("previewImg").src =
            event.target.result;

        }

        reader.readAsDataURL(file);
    }

});

/* SEARCH */

document.getElementById("search")
.addEventListener("keyup", function(){

    let value = this.value.toLowerCase();

    let cards = document.querySelectorAll(".category-card");

    cards.forEach(card => {

        let text = card.innerText.toLowerCase();

        card.style.display =
        text.includes(value)
        ? "block"
        : "none";

    });

});

</script>

</body>
</html>