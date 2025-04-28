<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$user_sql = "SELECT full_name, email_id, phone_no FROM users WHERE user_id = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Fetch user orders
$order_sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_id DESC";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$order_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile | Foodie Finds</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            margin-top: 50px;
            flex:1;
        }

        .profile-section {
            display: flex;
            align-items: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .profile-section img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 20px;
        }

        .details p {
            margin-bottom: 5px;
        }

        .section-title {
            margin-top: 30px;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .orders-list {
            list-style: none;
            padding: 0;
        }

        .orders-list li {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #c82333;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h1 class="text-center">Customer Profile</h1>

        <div class="profile-section">
            <img src="pro.png" alt="Profile Picture">
            <div class="details">
                <p><b>Name:</b> <?= $user['full_name']; ?></p>
                <p><b>Email:</b> <?= $user['email_id']; ?></p>
                <p><b>Phone:</b> <?= $user['phone_no']; ?></p>
            </div>
        </div>

        <h2 class="section-title">Order History</h2>
        <ul class="orders-list">
            <?php while ($order = $order_result->fetch_assoc()): ?>
                <li>
                    <span>
                        Order #<?= $order['order_id']; ?> - ₹<?= $order['total_amount']; ?> -
                        <b><?= ucfirst($order['order_status']); ?></b>
                    </span>
                    <?php if ($order['order_status'] == 'pending'): ?>
                        <a href="cancel_order.php?order_id=<?= $order['order_id']; ?>" class="btn btn-danger btn-sm">Cancel</a>
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>

    <?php include 'footer.html'; ?>
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
</body>

</html>