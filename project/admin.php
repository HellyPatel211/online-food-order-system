<?php
include 'db.php';

// Fetch total categories
$categoryQuery = "SELECT COUNT(*) as total_categories FROM category_data";
$categoryResult = $conn->query($categoryQuery);
$categoryCount = $categoryResult->fetch_assoc()['total_categories'];

// Fetch total food items
$foodQuery = "SELECT COUNT(*) as total_foods FROM item_data";
$foodResult = $conn->query($foodQuery);
$foodCount = $foodResult->fetch_assoc()['total_foods'];

// Fetch total orders
$orderQuery = "SELECT COUNT(*) as total_orders FROM orders";
$orderResult = $conn->query($orderQuery);
$orderCount = $orderResult->fetch_assoc()['total_orders'];

// Fetch total users
$userQuery = "SELECT COUNT(*) as total_users FROM users";
$userResult = $conn->query($userQuery);
$userCount = $userResult->fetch_assoc()['total_users'];

// Fetch total revenue
$revenueQuery = "SELECT SUM(total_amount) as total_revenue FROM orders WHERE order_status != 'canceled'";
$revenueResult = $conn->query($revenueQuery);
$revenue = $revenueResult->fetch_assoc()['total_revenue'];
$revenue = $revenue ? "₹" . number_format($revenue, 2) : "₹0.00";

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            background-color: #f3f4f6;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
        }
        .wrapper{
            display:flex;
            flex-direction:column;
            min-height:100vh;
        }
        main{
            flex: 1;
        }
        .dashboard {
            background-color: #e2e8f0;
            padding: 40px;
            border-radius: 8px;
            text-align: center;
        }
        .dashboard h1 {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 40px;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        .stat {
            margin: 20px;
        }
        .stat p {
            margin: 0;
        }
        .stat .number {
            font-size: 36px;
            font-weight: bold;
        }
        .stat .label {
            color: #718096;
        }
    </style>
</head>
<body>
<div class="wrapper">
<?php include 'admin_nav.html'; ?>
    <main>
        <!-- Main Content -->
        <div class="container">
            <div class="dashboard">
                <h1>Admin Dashboard</h1>
                <div class="stats">
                    <div class="stat">
                        <p class="number"><?php echo $categoryCount; ?></p>
                        <p class="label">Categories</p>
                    </div>
                    <div class="stat">
                        <p class="number"><?php echo $foodCount; ?></p>
                        <p class="label">Foods</p>
                    </div>
                    <div class="stat">
                        <p class="number"><?php echo $orderCount; ?></p>
                        <p class="label">Total Orders</p>
                    </div>
                    <div class="stat">
                        <p class="number"><?php echo $userCount; ?></p>
                        <p class="label">Total Users</p>
                    </div>
                    <div class="stat">
                        <p class="number"><?php echo $revenue; ?></p>
                        <p class="label">Revenue Generated</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php include 'admin_footer.html'; ?>
</div>
</body>
</html>
