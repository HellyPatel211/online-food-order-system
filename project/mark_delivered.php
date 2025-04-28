<?php
session_start();
include 'db.php';

if (!isset($_SESSION['delivery_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $delivery_id = $_SESSION['delivery_id'];

    // Update order status to "delivered"
    $update_sql = "UPDATE orders SET order_status = 'delivered' WHERE order_id = ? AND delivery_boy_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $order_id, $delivery_id);
    $stmt->execute();

    echo "<script>alert('Order marked as delivered!'); window.location.href = 'delivery_dashboard.php';</script>";
} else {
    header("Location: delivery_dashboard.php");
    exit();
}
?>
