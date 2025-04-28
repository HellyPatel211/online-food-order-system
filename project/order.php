<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
$user_id = $_SESSION['user_id'];


// Check if cart has items
$sql = "SELECT * FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Insert order into 'orders' table
    $insert_order = "INSERT INTO orders (user_id, total_amount) VALUES (?, 0)";
    $stmt = $conn->prepare($insert_order);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $order_id = $conn->insert_id;

    $total_order_price = 0;

    // Insert cart items into 'order_items' table
    while ($item = $result->fetch_assoc()) {
        $item_id = $item['item_id'];
        $price = $item['price'];
        $quantity = $item['quantity'];
        $total_price = $price * $quantity;

        $insert_order_items = "INSERT INTO order_items (order_id, item_id, price, quantity, total_price)
                               VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_order_items);
        $stmt->bind_param("iidid", $order_id, $item_id, $price, $quantity, $total_price);
        $stmt->execute();

        $total_order_price += $total_price;
    }

    // Update total price in 'orders' table
    $update_order = "UPDATE orders SET total_amount = ? WHERE order_id = ?";
    $stmt = $conn->prepare($update_order);
    $stmt->bind_param("di", $total_order_price, $order_id);
    $stmt->execute();

    // Clear cart after successful order placement
    $delete_cart = "DELETE FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($delete_cart);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Show success message
    echo "<script>
            alert('Your order is successfully placed!Thanks for order');
            window.location.href = 'menu.php'; 
          </script>";
} else {
    echo "<script>
            alert('Your cart is empty!');
            window.location.href = 'cart.php';
          </script>";
}
?>
