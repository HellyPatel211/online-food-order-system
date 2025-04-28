<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];


// Add item to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['item_id'])) {
    $item_id = $_POST['item_id'];
    $price = $_POST['price'];

    // Check if item already exists in the cart
    $check_sql = "SELECT * FROM cart WHERE user_id = ? AND item_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $user_id, $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update quantity and total price
        $update_sql = "UPDATE cart 
                       SET quantity = quantity + 1, 
                           total_price = total_price + ?
                       WHERE user_id = ? AND item_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("dii", $price, $user_id, $item_id);
        $stmt->execute();
    } else {
        // Add item to cart
        $insert_sql = "INSERT INTO cart (user_id, item_id, price, quantity, total_price) 
                       VALUES (?, ?, ?, 1, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("iidd", $user_id, $item_id, $price, $price);
        $stmt->execute();
    }
    header("Location: cart.php");
    exit();
}

// Fetch cart items
$sql = "SELECT c.cart_id, i.item_name, i.image, c.price, c.quantity, c.total_price 
        FROM cart c
        JOIN item_data i ON c.item_id = i.item_id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Remove item from cart
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    $delete_sql = "DELETE FROM cart WHERE cart_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $remove_id);
    $stmt->execute();
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart - Foodie Finds</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
    html, body {
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
        flex: 1;
    }
</style>

</head>
<body>
<div class="wrapper">
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h2 class="text-center mb-4">🛒 My Cart</h2>

        <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Food Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                while ($item = $result->fetch_assoc()):
                    $total += $item['total_price'];
                ?>
                <tr>
                    <td><img src="<?php echo $item['image']; ?>" alt="<?php echo $item['item_name']; ?>" width="70"></td>
                    <td><?php echo $item['item_name']; ?></td>
                    <td>₹<?php echo $item['price']; ?></td>
                    <td>
                        <form action="update_quantity.php" method="POST" style="display: inline-block;">
                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                            <input type="hidden" name="action" value="decrease">
                            <button type="submit" class="btn btn-outline-secondary btn-sm">−</button>
                        </form>
                        <?php echo $item['quantity']; ?>
                        <form action="update_quantity.php" method="POST" style="display: inline-block;">
                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                            <input type="hidden" name="action" value="increase">
                            <button type="submit" class="btn btn-outline-secondary btn-sm">+</button>
                        </form>
                    </td>
                    <td>₹<?php echo $item['total_price']; ?></td>
                    <td>
                        <a href="?remove=<?php echo $item['cart_id']; ?>" class="btn btn-danger btn-sm">
                            Remove
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <p class="text-end fw-bold">Total: ₹<?php echo $total; ?></p>

        <form id="orderForm" method="POST" action="order.php">
            <button type="button" class="btn btn-success w-100" onclick="confirmOrder()">Proceed to Checkout</button>
        </form>

        <?php else: ?>
        <p class="text-center">Your cart is empty. Add some delicious items!</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.html'; ?>

    <script>
        function confirmOrder() {
            if (confirm("Are you sure you want to place this order?")) {
                document.getElementById('orderForm').submit();
            }
        }
    </script>
    </div>
</body>
</html>
