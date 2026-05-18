<?php
include 'db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Products</title>

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
            padding:18px 30px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            color:white;
        }

        .header h1{
            font-size:24px;
        }

        .header a{
            text-decoration:none;
            background:white;
            color:#667eea;
            padding:10px 18px;
            border-radius:8px;
            font-weight:bold;
            transition:0.3s;
        }

        .header a:hover{
            background:#ececff;
        }

        /* CONTAINER */

        .container{
            width:95%;
            margin:25px auto;
        }

        /* TOP BAR */

        .top-bar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:20px;
            gap:15px;
        }

        .search-box{
            flex:1;
        }

        .search-box input{
            width:100%;
            padding:14px;
            border:none;
            border-radius:10px;
            background:white;
            box-shadow:0 2px 10px rgba(0,0,0,0.08);
            font-size:15px;
            outline:none;
        }

        .add-btn{
            background:#28a745;
            color:white;
            text-decoration:none;
            padding:14px 20px;
            border-radius:10px;
            font-weight:bold;
            transition:0.3s;
            white-space:nowrap;
        }

        .add-btn:hover{
            background:#218838;
        }

        /* TABLE */

        .table-box{
            background:white;
            border-radius:15px;
            overflow:hidden;
            box-shadow:0 5px 20px rgba(0,0,0,0.08);
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th{
            background:#111827;
            color:white;
            padding:16px;
            text-align:left;
            font-size:14px;
        }

        td{
            padding:16px;
            border-bottom:1px solid #eee;
            vertical-align:middle;
        }

        tr:hover{
            background:#fafafa;
        }

        /* PRODUCT */

        .product-box{
            display:flex;
            align-items:center;
            gap:12px;
        }

        .product-box img{
            width:65px;
            height:65px;
            object-fit:cover;
            border-radius:10px;
            border:1px solid #ddd;
        }

        .product-name{
            font-weight:600;
            color:#111;
        }

        /* STOCK BADGES */

        .stock{
            padding:6px 12px;
            border-radius:20px;
            font-size:13px;
            font-weight:bold;
            display:inline-block;
        }

        .in-stock{
            background:#d4edda;
            color:#155724;
        }

        .low-stock{
            background:#fff3cd;
            color:#856404;
        }

        .out-stock{
            background:#f8d7da;
            color:#721c24;
        }

        /* ACTION BUTTONS */

        .actions{
            display:flex;
            gap:10px;
        }

        .edit-btn{
            text-decoration:none;
            background:#ffc107;
            color:black;
            padding:8px 14px;
            border-radius:8px;
            font-size:13px;
            font-weight:bold;
            transition:0.3s;
        }

        .edit-btn:hover{
            background:#e0a800;
        }

        .delete-btn{
            text-decoration:none;
            background:#dc3545;
            color:white;
            padding:8px 14px;
            border-radius:8px;
            font-size:13px;
            font-weight:bold;
            transition:0.3s;
        }

        .delete-btn:hover{
            background:#c82333;
        }

        /* PRICE */

        .price{
            font-weight:bold;
            color:#111;
            font-size:15px;
        }

        /* RESPONSIVE */

        @media(max-width:900px){

            .top-bar{
                flex-direction:column;
                align-items:stretch;
            }

            table{
                display:block;
                overflow-x:auto;
            }

        }

    </style>
</head>

<body>

<!-- HEADER -->

<div class="header">
    <h1>🛍 Products Management</h1>

    <a href="admin_dashboard.php">🏠 Dashboard</a>
</div>

<div class="container">

    <!-- TOP BAR -->

    <div class="top-bar">

        <div class="search-box">
            <input type="text" id="search" placeholder="Search products...">
        </div>

        <a href="admin_product.php" class="add-btn">
            + Add Product
        </a>

    </div>

    <!-- TABLE -->

    <div class="table-box">

        <table id="productTable">

            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>

            <?php
            $result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");

            while($row = mysqli_fetch_assoc($result)){
            ?>

            <tr>

                <td>
                    #<?php echo $row['id']; ?>
                </td>

                <td>

                    <div class="product-box">

                        <img src="<?php echo $row['image']; ?>">

                        <div>
                            <div class="product-name">
                                <?php echo $row['name']; ?>
                            </div>

                            <small>
                                <?php echo $row['category']; ?>
                            </small>
                        </div>

                    </div>

                </td>

                <td class="price">
                    ₹<?php echo $row['price']; ?>
                </td>

                <td>

                    <?php
                    if($row['stock'] == 0){
                        echo "<span class='stock out-stock'>Out of Stock</span>";
                    }
                    elseif($row['stock'] < 5){
                        echo "<span class='stock low-stock'>Low Stock (" . $row['stock'] . ")</span>";
                    }
                    else{
                        echo "<span class='stock in-stock'>In Stock (" . $row['stock'] . ")</span>";
                    }
                    ?>

                </td>

                <td>

                    <div class="actions">

                        <a class="edit-btn"
                        href="edit_product.php?id=<?php echo $row['id']; ?>">
                            Edit
                        </a>

                        <a class="delete-btn"
                        onclick="return confirm('Delete this product?')"
                        href="delete_product.php?id=<?php echo $row['id']; ?>">
                            Delete
                        </a>

                    </div>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</div>

<script>

document.getElementById("search").addEventListener("keyup", function(){

    let value = this.value.toLowerCase();

    let rows = document.querySelectorAll("#productTable tr");

    rows.forEach((row,index)=>{

        if(index === 0) return;

        row.style.display =
        row.innerText.toLowerCase().includes(value)
        ? ""
        : "none";

    });

});

</script>

</body>
</html>