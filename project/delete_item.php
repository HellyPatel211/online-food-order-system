<?php
include 'db.php';

$item_id = $_GET['item_id'];  // item_id from URL

// Step 1: Delete related orders first
$deleteOrders = $conn->prepare("DELETE FROM order_items WHERE item_id = ?");
$deleteOrders->bind_param("i", $item_id);
$deleteOrders->execute();

// Step 2: Now delete the item
$deleteItem = $conn->prepare("DELETE FROM item_data WHERE item_id = ?");
$deleteItem->bind_param("i", $item_id);

if ($deleteItem->execute()) {
    echo "<script>alert('Item deleted successfully!'); window.location.href='admin_item.php';</script>";
} else {
    echo "<script>alert('Failed to delete item.');</script>";
}
?>
