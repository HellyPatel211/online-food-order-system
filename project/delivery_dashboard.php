<?php
session_start();
include 'db.php';

if (!isset($_SESSION['delivery_id'])) {
    header("Location: login.php");
    exit();
}

$delivery_id = $_SESSION['delivery_id'];
$delivery_name = $_SESSION['delivery_name'];

// Fetch pending orders
$pending_orders = $conn->query("
    SELECT o.*, u.address 
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    WHERE o.order_status = 'pending'
");

// Fetch confirmed orders
$confirmed_orders = $conn->query("
    SELECT o.*, u.address 
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    WHERE o.delivery_boy_id = $delivery_id AND o.order_status = 'confirmed'
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Dashboard | Foodie Finds</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .navbar {
            display:flex;
            justify-content:space-between;
            align-items:center;
            background-color:#4a5568;
            color:white;
            padding: 0px 20px;
        }

        .navbar .site-name a {
            color: white;
            text-decoration: none;
            font-size: 1.5em;
            font-weight: bold;
        }

        .navbar .nav-link a {
            color: white;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h1 {
            color: #4CAF50;
            font-size: 24px;
        }

        h2 {
            color: #333;
            font-size: 20px;
            margin-top: 20px;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 5px;
            display: inline-block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: #fff;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 5px;
        }

        .confirm-btn {
            background-color: #28a745;
        }

        .deliver-btn {
            background-color: #007bff;
        }

        .logout-btn {
            padding: 10px 15px;
            display: block;
            width: 100px;
            margin: 20px auto;
            text-align: center;
        }

        .footer {
            text-align: center;
            background: #4a5568;
            color: white;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100vw;
        }

    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <div class="site-name">
            <a href="delivery_dashboard.php">Foodie Finds</a>
        </div>
        <div class="nav-link">
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="container">
        <h1>🚚 Welcome, <?= $delivery_name; ?></h1>

        <h2>📦 Pending Orders</h2>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Amount</th>
                <th>User ID</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
            <?php while ($order = $pending_orders->fetch_assoc()): ?>
                <tr>
                    <td><?= $order['order_id']; ?></td>
                    <td>₹<?= $order['total_amount']; ?></td>
                    <td><?= $order['user_id']; ?></td>
                    <td><?= $order['address']; ?></td>
                    <td>
                        <a href="confirm_order.php?order_id=<?= $order['order_id']; ?>" class="btn confirm-btn">Confirm</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <h2>🚀 Confirmed Orders</h2>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Amount</th>
                <th>User ID</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
            <?php while ($order = $confirmed_orders->fetch_assoc()): ?>
                <tr>
                    <td><?= $order['order_id']; ?></td>
                    <td>₹<?= $order['total_amount']; ?></td>
                    <td><?= $order['user_id']; ?></td>
                    <td><?= $order['address']; ?></td>
                    <td>
                        <a href="mark_delivered.php?order_id=<?= $order['order_id']; ?>" class="btn deliver-btn">Mark as Delivered</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <div class="footer">
        © 2025 Foodie Finds - Delivery Dashboard
    </div>

</body>
</html>
