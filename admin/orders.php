<?php
include '../includes/db.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {

    $stmt = $conn->query("SELECT * FROM orders");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    die("Error: " . $e->getMessage());

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Orders</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 85%;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #28a745;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        .no-orders {
            text-align: center;
            color: #666;
            padding: 20px;
        }

    </style>

</head>

<body>

<div class="container">

    <h2>Customer Orders</h2>

    <?php if(count($orders) > 0) { ?>

    <table>

        <tr>

            <?php foreach(array_keys($orders[0]) as $column) { ?>

                <th><?= htmlspecialchars($column); ?></th>

            <?php } ?>

        </tr>

        <?php foreach($orders as $order) { ?>

            <tr>

                <?php foreach($order as $value) { ?>

                    <td><?= htmlspecialchars($value); ?></td>

                <?php } ?>

            </tr>

        <?php } ?>

    </table>

    <?php } else { ?>

        <div class="no-orders">
            No orders found.
        </div>

    <?php } ?>

    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>

</div>

</body>
</html>