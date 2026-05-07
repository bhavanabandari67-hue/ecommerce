<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['user_id'];

/* Add to cart */
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = 1;

    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cart_item) {
        $new_quantity = $cart_item['quantity'] + 1;
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$new_quantity, $user_id, $product_id]);
    } else {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $product_id, $quantity]);
    }

    header("Location: cart.php");
    exit();
}

/* Update quantity */
if (isset($_POST['update_quantity'])) {
    $cart_id = $_POST['cart_id'];
    $quantity = (int)$_POST['quantity'];

    if ($quantity > 0) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$quantity, $cart_id, $user_id]);
    }

    header("Location: cart.php");
    exit();
}

/* Remove item */
if (isset($_POST['remove_from_cart'])) {
    $cart_id = $_POST['cart_id'];

    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$cart_id, $user_id]);

    header("Location: cart.php");
    exit();
}

/* Fetch cart items */
$stmt = $conn->prepare("
    SELECT 
        cart.id AS cart_id,
        cart.quantity,
        products.name,
        products.price,
        products.image
    FROM cart
    JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_cost = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .cart-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 25px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #333;
            color: white;
        }

        img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        input[type="number"] {
            width: 60px;
            padding: 5px;
        }

        button {
            padding: 7px 12px;
            border: none;
            background: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .remove-btn {
            background: #dc3545;
        }

        .remove-btn:hover {
            background: #b02a37;
        }

        .total {
            text-align: right;
            font-size: 22px;
            font-weight: bold;
            margin-top: 20px;
        }

        .cart-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
        }

        .cart-actions a {
            background: green;
            color: white;
            padding: 12px 18px;
            border-radius: 6px;
            text-decoration: none;
        }

        .empty {
            text-align: center;
            font-size: 18px;
            color: #777;
        }
    </style>
</head>

<body>

<div class="cart-container">
    <h2>Your Cart</h2>

    <?php if (empty($cart_items)): ?>

        <p class="empty">Your cart is empty.</p>

        <div class="cart-actions">
            <a href="../index.php">Back to Shop</a>
        </div>

    <?php else: ?>

        <table>
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>

            <?php foreach ($cart_items as $item): ?>
                <?php 
                    $item_total = $item['price'] * $item['quantity'];
                    $total_cost += $item_total;
                ?>

                <tr>
                    <td>
                        <?php if (!empty($item['image'])): ?>
                            <img src="../images/<?= htmlspecialchars($item['image']); ?>" alt="Product">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>

                    <td><?= htmlspecialchars($item['name']); ?></td>

                    <td>$<?= number_format($item['price'], 2); ?></td>

                    <td>
                        <form method="POST">
                            <input type="hidden" name="cart_id" value="<?= $item['cart_id']; ?>">
                            <input type="number" name="quantity" value="<?= $item['quantity']; ?>" min="1">
                            <button type="submit" name="update_quantity">Update</button>
                        </form>
                    </td>

                    <td>$<?= number_format($item_total, 2); ?></td>

                    <td>
                        <form method="POST">
                            <input type="hidden" name="cart_id" value="<?= $item['cart_id']; ?>">
                            <button type="submit" name="remove_from_cart" class="remove-btn">Remove</button>
                        </form>
                    </td>
                </tr>

            <?php endforeach; ?>
        </table>

        <div class="total">
            Total: $<?= number_format($total_cost, 2); ?>
        </div>

        <div class="cart-actions">
            <a href="../index.php">Back to Shop</a>
            <a href="checkout.php">Proceed to Checkout</a>
        </div>

    <?php endif; ?>

</div>

</body>
</html>