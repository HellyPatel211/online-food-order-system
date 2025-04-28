<?php
include 'db.php'; // Database connection

$category_query = "SELECT cat_id, cat_name FROM category_data";
$category_result = $conn->query($category_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST['item_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $cat_id = $_POST['cat_id'];

    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        echo "<script>alert('Failed to upload image. Please try again.');</script>";
        exit();
    }

    $sql = "INSERT INTO item_data (item_name, price, description, image, cat_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdssi", $item_name, $price, $description, $target_file, $cat_id);

    if ($stmt->execute()) {
        echo "<script>alert('Item added successfully!'); window.location.href='admin_item.php';</script>";
    } else {
        echo "<script>alert('Failed to add item.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Item</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            color: #555;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        input[type="file"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 15px;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        button[type="submit"] {
            margin-top: 25px;
            background-color: #007bff;
            color: white;
            padding: 12px;
            width: 100%;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

  
    </style>
</head>
<body>

<?php include 'admin_nav.html'; ?>

<div class="container">
    <h2>Add New Item</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Category:</label>
        <select name="cat_id" required>
            <option value="">Select Category</option>
            <?php while ($row = $category_result->fetch_assoc()): ?>
                <option value="<?php echo $row['cat_id']; ?>"><?php echo htmlspecialchars($row['cat_name']); ?></option>
            <?php endwhile; ?>
        </select>

        <label>Item Name:</label>
        <input type="text" name="item_name" required>

        <label>Price:</label>
        <input type="number" step="0.01" name="price" required>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <label>Image:</label>
        <input type="file" name="image" required>

        <button type="submit">Add Item</button>
    </form>
</div>

<?php include 'admin_footer.html'; ?>

</body>
</html>
 