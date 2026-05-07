<?php
$conn = mysqli_connect("localhost", "root", "", "ecommerce1");

// Get ID safely
if(isset($_GET['id'])){
    $id = $_GET['id'];
} else {
    echo "No ID found";
    exit();
}

// Fetch product data
$query = "SELECT * FROM products WHERE id='$id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if(!$row){
    echo "Product not found";
    exit();
}

// Update product
if(isset($_POST['update'])){
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $update = "UPDATE products SET 
        name='$name',
        price='$price',
        description='$description'
        WHERE id='$id'";

    if(mysqli_query($conn, $update)){
        $success = "Product updated successfully!";
    } else {
        $error = "Error updating product!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>

    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
        }

        .container {
            width: 400px;
            margin: 80px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background: green;
            color: white;
            border: none;
            cursor: pointer;
        }

        .message {
            margin-top: 15px;
            padding: 10px;
            background: #d4edda;
            color: #155724;
            text-align: center;
            border-radius: 5px;
        }

        .error {
            margin-top: 15px;
            padding: 10px;
            background: #f8d7da;
            color: #721c24;
            text-align: center;
            border-radius: 5px;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>Edit Product</h2>

    <form method="POST">

        <label>Name:</label>
        <input type="text" name="name" value="<?= $row['name']; ?>">

        <label>Price:</label>
        <input type="text" name="price" value="<?= $row['price']; ?>">

        <label>Description:</label>
        <input type="text" name="description" value="<?= $row['description']; ?>">

        <input type="submit" name="update" value="Update Product">

        <?php if(isset($success)) { ?>
            <div class="message"><?= $success; ?></div>
        <?php } ?>

        <?php if(isset($error)) { ?>
            <div class="error"><?= $error; ?></div>
        <?php } ?>

    </form>
</div>

</body>
</html>