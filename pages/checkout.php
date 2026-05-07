<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";
$error = "";

if (isset($_POST['place_order'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $products = "";

    // Get cart items from database
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($cart_items as $cart_item) {
        $product_id = $cart_item['product_id'];
        $quantity = $cart_item['quantity'];

        $stmt = $conn->prepare("SELECT name FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $products .= $product['name'] . " (Qty: " . $quantity . "), ";
        }
    }

    if ($products == "") {
        $error = "Your cart is empty!";
    } else {
        $stmt = $conn->prepare("INSERT INTO orders (name, phone, address, products) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([$name, $phone, $address, $products]);

        if ($result) {
            $message = "Order placed successfully!";

            // Clear cart after order
            $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $stmt->execute([$user_id]);
        } else {
            $error = "Error placing order!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>

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
        }

        h2 {
            text-align: center;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }

        button {
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

        .shop-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background: green;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>Checkout</h2>

    <form method="POST">
        <input type="text" name="name" placeholder="Enter your name" required>
        <input type="text" name="phone" placeholder="Enter phone number" required>
        <textarea name="address" placeholder="Enter address" required></textarea>

        <button type="submit" name="place_order">Place Order</button>
    </form>

    <?php if ($message != "") { ?>
        <div class="message">
            <?php echo $message; ?><br><br>
            <a href="../index.php" class="shop-btn">Back to Shop</a>
        </div>
    <?php } ?>

    <?php if ($error != "") { ?>
        <div class="error"><?php echo $error; ?></div>
    <?php } ?>

</div>

</body>
</html>