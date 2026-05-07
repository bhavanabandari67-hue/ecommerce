<?php
$conn = mysqli_connect("localhost", "root", "", "ecommerce1");

// Check if ID is coming
if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Delete query
    $delete = "DELETE FROM products WHERE id='$id'";

    if(mysqli_query($conn, $delete)){
        // Redirect back to manage page
        header("Location: manage_products.php");
        exit();
    } else {
        echo "Error deleting product";
    }

} else {
    echo "No ID received";
}
?>