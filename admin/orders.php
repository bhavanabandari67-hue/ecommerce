<?php
session_start();

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}
?>
<?php
$conn = mysqli_connect("localhost", "root", "", "ecommerce1");

$result = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>
    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
        }
        table {
            width: 90%;
            margin: 40px auto;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: green;
            color: white;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">Orders</h2>

<table>
<tr>
    <th>Name</th>
    <th>Phone</th>
    <th>Address</th>
    <th>Products</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['phone']; ?></td>
    <td><?php echo $row['address']; ?></td>
    <td><?php echo $row['products']; ?></td>
</tr>
<?php } ?>

</table>

</body>
</html>