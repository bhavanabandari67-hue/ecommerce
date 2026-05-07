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

$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");

if ($stmt->execute([$id])) {
    header("Location: manage_products.php?deleted=success");
    exit();
} else {
    echo "Error deleting product!";
}
?>