<?php
include '../includes/db.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$successMessage = "";

if (isset($_POST['add_product'])) {

    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $image = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];

    move_uploaded_file($tmp_name, "../images/$image");

    $stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $price, $description, $image]);

    $successMessage = "Product added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        input[type="file"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .message {
            color: green;
            text-align: center;
            margin-top: 25px;
            font-size: 22px;
            font-weight: bold;
            background-color: #e8ffe8;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid green;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            text-decoration: none;
            color: #4CAF50;
            font-size: 16px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

    </style>
</head>

<body>

    <div class="container">

        <h2>Add Product</h2>

        <form method="POST" enctype="multipart/form-data">

            <label>Product Name:</label>
            <input type="text" name="name" required>

            <label>Price:</label>
            <input type="number" step="0.01" name="price" required>

            <label>Description:</label>
            <textarea name="description" required></textarea>

            <label>Product Image:</label>
            <input type="file" name="image" required>

            <button type="submit" name="add_product">
                Add Product
            </button>

        </form>

        <?php if (!empty($successMessage)) { ?>
            <div class="message">
                <?php echo $successMessage; ?>
            </div>
        <?php } ?>

        <div class="back-link">
            <a href="manage_products.php">
                Back to Manage Products
            </a>
        </div>

    </div>

</body>
</html>