<?php
include '../includes/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['id'])) {
    echo "No product ID found!";
    exit();
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "Product not found!";
    exit();
}

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");

    if ($stmt->execute([$name, $price, $description, $id])) {
        $success = "Product updated successfully!";

        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
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
            font-family: Arial, sans-serif;
            background: #f4f7fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 420px;
            margin: 80px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0 18px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0 18px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: vertical;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 15px;
            cursor: pointer;
        }

        button:hover {
            background: #218838;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back:hover {
            background: #0056b3;
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
        <input type="text" name="name" value="<?= htmlspecialchars($row['name']); ?>" required>

        <label>Price:</label>
        <input type="text" name="price" value="<?= htmlspecialchars($row['price']); ?>" required>

        <label>Description:</label>
        <textarea name="description" required><?= htmlspecialchars($row['description']); ?></textarea>

        <button type="submit" name="update">Update Product</button>
    </form>

    <?php if (isset($success)) { ?>
        <div class="message"><?= $success; ?></div>
    <?php } ?>

    <?php if (isset($error)) { ?>
        <div class="error"><?= $error; ?></div>
    <?php } ?>

    <a href="manage_products.php" class="back">Back to Manage Products</a>
</div>

</body>
</html>