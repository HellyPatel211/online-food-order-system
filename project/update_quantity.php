<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cart_id'], $_POST['action'])) {
    $cart_id = $_POST['cart_id'];
    $action = $_POST['action'];

    // Get current quantity and price
    $sql = "SELECT quantity, price FROM cart WHERE cart_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();

    if ($item) {
        $quantity = $item['quantity'];
        $price = $item['price'];

        if ($action === 'increase') {
            $quantity++;
        } elseif ($action === 'decrease') {
            $quantity--;
        }

        if ($quantity <= 0) {
            // Remove item if quantity is 0 or less
            $delete_sql = "DELETE FROM cart WHERE cart_id = ?";
            $stmt = $conn->prepare($delete_sql);
            $stmt->bind_param("i", $cart_id);
            $stmt->execute();
        } else {
            $total_price = $quantity * $price;

            $update_sql = "UPDATE cart SET quantity = ?, total_price = ? WHERE cart_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("idi", $quantity, $total_price, $cart_id);
            $stmt->execute();
        }
    }
}

header("Location: cart.php");
exit();
