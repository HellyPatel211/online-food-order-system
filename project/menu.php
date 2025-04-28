<?php
include 'db.php';

// Check if 'cat_id' is set in URL and filter accordingly
if (isset($_GET['cat_id'])) {
    $cat_id = $_GET['cat_id'];
    $sql = "SELECT * FROM item_data WHERE cat_id = '$cat_id'";
} else {
    // Show all items if no category is selected
    $sql = "SELECT * FROM item_data";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Food Order - Menu</title>
    <link rel="stylesheet" href="menu.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <header>
        <h1>Delicious Items Menu</h1>
    </header>

    <div class="menu-container">
        <?php
        if ($result->num_rows > 0) {
            while ($item = $result->fetch_assoc()) {
                echo '<div class="item">';
                echo '<img src="' . $item['image'] . '" alt="' . $item['item_name'] . '">';
                echo '<div class="item-details">';
                echo '<div class="item-name">' . $item['item_name'] . '</div>';
                echo '<div class="item-description">' . $item['description'] . '</div>';
                echo '<div class="item-price">₹' . $item['price'] . '</div>';
                echo '</div>';

                // Add to Cart Button
                echo '<form method="POST" action="cart.php">';
                echo '<input type="hidden" name="item_id" value="' . $item['item_id'] . '">';
                echo '<input type="hidden" name="price" value="' . $item['price'] . '">';
                echo '<button type="submit" class="add-to-cart-btn">Add to Cart</button>';
                echo '</form>';

                echo '</div>';
            }
        } else {
            echo "<p>No items available in this category.</p>";
        }
        $conn->close();
        ?>
    </div>

    <?php include 'footer.html'; ?>
</body>
</html>
