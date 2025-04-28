<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Check if the order is still pending
    $check_sql = "SELECT order_status FROM orders WHERE order_id = ? AND user_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if ($order && $order['order_status'] == 'pending') {
        // Update order status to canceled
        $cancel_sql = "UPDATE orders SET order_status = 'canceled' WHERE order_id = ?";
        $stmt = $conn->prepare($cancel_sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        echo "<script>alert('Order has been canceled.'); window.location.href = 'profile.php';</script>";
    } else {
        echo "<script>alert('You cannot cancel this order.'); window.location.href = 'profile.php';</script>";
    }
} else {
    header("Location: profile.php");
    exit();
}
?>
