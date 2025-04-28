<?php
include 'db.php';

// Fetch all categories for the dropdown
$categories = [];
$cat_query = "SELECT * FROM category_data";
$cat_result = $conn->query($cat_query);
if ($cat_result->num_rows > 0) {
    while ($row = $cat_result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Fetch item details for the given item_id
if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
    $sql = "SELECT * FROM item_data WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $cat_id = $_POST['cat_id'];

    if ($_FILES["image"]["name"]) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    } else {
        $target_file = $_POST['existing_image'];
    }

    $update_sql = "UPDATE item_data SET item_name = ?, price = ?, description = ?, image = ?, cat_id = ? WHERE item_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sdssii", $item_name, $price, $description, $target_file, $cat_id, $item_id);

    if ($stmt->execute()) {
        echo "<script>alert('Item updated successfully!'); window.location.href = 'admin_item.php';</script>";
    } else {
        echo "<script>alert('Failed to update item.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Item</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
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

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        label {
            font-weight: 600;
            margin-top: 15px;
            display: block;
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

        img {
            margin-top: 10px;
            max-width: 100px;
            border-radius: 6px;
            border: 1px solid #ccc;
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

        .form-section {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php include 'admin_nav.html'; ?>

<div class="container">
    <h1>Update Item</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="item_id" value="<?= $item['item_id']; ?>">

        <div class="form-section">
            <label>Item Name:</label>
            <input type="text" name="item_name" value="<?= $item['item_name']; ?>" required>
        </div>

        <div class="form-section">
            <label>Price:</label>
            <input type="number" step="0.01" name="price" value="<?= $item['price']; ?>" required>
        </div>

        <div class="form-section">
            <label>Description:</label>
            <textarea name="description" required><?= $item['description']; ?></textarea>
        </div>

        <div class="form-section">
            <label>Category:</label>
            <select name="cat_id" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['cat_id']; ?>" <?= ($item['cat_id'] == $cat['cat_id']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($cat['cat_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-section">
            <label>Current Image:</label><br>
            <img src="<?= $item['image']; ?>" alt="Item Image">
        </div>

        <div class="form-section">
            <label>New Image (optional):</label>
            <input type="file" name="image">
            <input type="hidden" name="existing_image" value="<?= $item['image']; ?>">
        </div>

        <button type="submit">Update Item</button>
    </form>
</div>

<?php include 'admin_footer.html'; ?>
</body>
</html>
