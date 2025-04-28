<?php
include 'db.php'; // Include database connection

// Fetch all orders with user and order item details
$sql = "SELECT orders.order_id, users.full_name AS customer_name, 
               GROUP_CONCAT(item_data.item_name SEPARATOR ', ') AS items, 
               GROUP_CONCAT(order_items.quantity SEPARATOR ', ') AS quantities, 
               orders.total_amount, orders.order_status
        FROM orders
        JOIN users ON orders.user_id = users.user_id
        JOIN order_items ON orders.order_id = order_items.order_id
        JOIN item_data ON order_items.item_id = item_data.item_id
        GROUP BY orders.order_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <style>
        body {
            background-color: #f7fafc;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .wrapper{
            display:flex;
            flex-direction:column;
            min-height:100vh;
        }
        main{
            flex: 1;
        }
        table {
            width: 90%;
            margin: auto;
            background-color: #ffffff;
            border-collapse: collapse;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 10px;
            border: 1px solid #e2e8f0;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .pending { background-color: #f4c542; }
        .confirmed { background-color: #42c542; color: white; }
        .canceled { background-color: #e53e3e; color: white; }
        .delivered { background-color: #4299e1; color: white; }
    </style>
</head>
<body>
<div class="wrapper">
<?php include 'admin_nav.html'; ?>
<main>
    <h1>Manage Orders</h1>
    
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Items</th>
                <th>Quantities</th>
                <th>Total Price</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['order_id'] . "</td>";
                    echo "<td>" . $row['customer_name'] . "</td>";
                    echo "<td>" . $row['items'] . "</td>";
                    echo "<td>" . $row['quantities'] . "</td>";
                    echo "<td>₹" . number_format($row['total_amount'], 2) . "</td>";
                    
                    // Order Status with Styling
                    $status_class = strtolower($row['order_status']);
                    echo "<td><span class='status $status_class'>" . ucfirst($row['order_status']) . "</span></td>";
                    
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No orders found</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
        </main>
    <?php include 'admin_footer.html'; ?>

</div>
</body>
</html>
