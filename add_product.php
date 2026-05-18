








<?php
include 'db.php';

$name = $_POST['name'];
$description = $_POST['description'];
// $category = $_POST['category'];
$category = implode(",", $_POST['category']);
$price = $_POST['price'];
$discount_price = $_POST['discount_price'];
$stock = $_POST['stock'];
$rating = $_POST['rating'];
$image2 = $_POST['image2'];
$image3 = $_POST['image3'];
$image4 = $_POST['image4'];
$image5 = $_POST['image5'];


// IMAGE UPLOAD
// $imageName = $_FILES['image']['name'];
// $tmpName = $_FILES['image']['tmp_name'];

// $folder = "uploads/" . time() . "_" . $imageName;

// if(!file_exists("uploads")){
//     mkdir("uploads");
// }

// move_uploaded_file($tmpName, $folder);




if(!file_exists("uploads")){
    mkdir("uploads");
}

/* MAIN IMAGE */

$image1 = "";

if($_FILES['image']['name'] != ""){

    $image1 =
    "uploads/" . time() . "_" .
    $_FILES['image']['name'];

    move_uploaded_file(
        $_FILES['image']['tmp_name'],
        $image1
    );
}

/* IMAGE 2 */

$image2 = "";

if($_FILES['image2']['name'] != ""){

    $image2 =
    "uploads/" . time() . "_" .
    $_FILES['image2']['name'];

    move_uploaded_file(
        $_FILES['image2']['tmp_name'],
        $image2
    );
}

/* IMAGE 3 */

$image3 = "";

if($_FILES['image3']['name'] != ""){

    $image3 =
    "uploads/" . time() . "_" .
    $_FILES['image3']['name'];

    move_uploaded_file(
        $_FILES['image3']['tmp_name'],
        $image3
    );
}

/* IMAGE 4 */

$image4 = "";

if($_FILES['image4']['name'] != ""){

    $image4 =
    "uploads/" . time() . "_" .
    $_FILES['image4']['name'];

    move_uploaded_file(
        $_FILES['image4']['tmp_name'],
        $image4
    );
}

/* IMAGE 5 */

$image5 = "";

if($_FILES['image5']['name'] != ""){

    $image5 =
    "uploads/" . time() . "_" .
    $_FILES['image5']['name'];

    move_uploaded_file(
        $_FILES['image5']['tmp_name'],
        $image5
    );
}




// INSERT PRODUCT
// mysqli_query($conn, "
// INSERT INTO products
// (name, description, category, price, discount_price, stock, rating, image)
// VALUES
// ('$name','$description','$category','$price','$discount_price','$stock','$rating','$folder')
// ");

mysqli_query($conn, "
INSERT INTO products
(
name,
description,
category,
price,
discount_price,
stock,
rating,
image,
image2,
image3,
image4,
image5
)

VALUES
(
'$name',
'$description',
'$category',
'$price',
'$discount_price',
'$stock',
'$rating',
'$image1',
'$image2',
'$image3',
'$image4',
'$image5'
)
");

echo "
<script>
alert('✅ Product Added Successfully');
window.location.href='admin.php';
</script>
";
?>