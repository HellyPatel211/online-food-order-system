<?php
include 'db.php';

$cat_id = $_GET['cat_id'];

// Step 1: Find all items in this category
$itemQuery = $conn->prepare("SELECT item_id FROM item_data WHERE cat_id = ?");
$itemQuery->bind_param("i", $cat_id);
$itemQuery->execute();
$itemResult = $itemQuery->get_result();

// Step 2: Delete related order_items for each item
while ($item = $itemResult->fetch_assoc()) {
    $deleteOrders = $conn->prepare("DELETE FROM order_items WHERE item_id = ?");
    $deleteOrders->bind_param("i", $item['item_id']);
    $deleteOrders->execute();
}

// Step 3: Delete all items from this category
$deleteItems = $conn->prepare("DELETE FROM item_data WHERE cat_id = ?");
$deleteItems->bind_param("i", $cat_id);
$deleteItems->execute();

// Step 4: Finally delete the category
$deleteCategory = $conn->prepare("DELETE FROM category_data WHERE cat_id = ?");
$deleteCategory->bind_param("i", $cat_id);

if ($deleteCategory->execute()) {
    echo "<script>alert('Category deleted successfully!'); window.location.href='category.php';</script>";
} else {
    echo "<script>alert('Failed to delete category.');</script>";
}
?>
