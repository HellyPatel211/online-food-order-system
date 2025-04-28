<?php
include 'db.php';

if (isset($_GET['cat_id'])) {
    $cat_id = $_GET['cat_id'];
    $sql = "SELECT * FROM category_data WHERE cat_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cat_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cat_id = $_POST['cat_id'];
    $cat_name = $_POST['cat_name'];
    $description = $_POST['description'];

    if ($_FILES["category_image"]["name"]) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["category_image"]["name"]);
        move_uploaded_file($_FILES["category_image"]["tmp_name"], $target_file);
    } else {
        $target_file = $_POST['existing_image'];
    }

    $update_sql = "UPDATE category_data SET cat_name = ?, description = ?, category_image = ? WHERE cat_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssi", $cat_name, $description, $target_file, $cat_id);

    if ($stmt->execute()) {
        echo "<script>alert('Category updated successfully!'); window.location.href = 'admincat.php';</script>";
    } else {
        echo "<script>alert('Failed to update category.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Category</title>
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
        textarea,
        input[type="file"] {
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
    <h1>Update Category</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="cat_id" value="<?= $category['cat_id']; ?>">

        <div class="form-section">
            <label>Category Name:</label>
            <input type="text" name="cat_name" value="<?= $category['cat_name']; ?>" required>
        </div>

        <div class="form-section">
            <label>Description:</label>
            <textarea name="description" required><?= $category['description']; ?></textarea>
        </div>

        <div class="form-section">
            <label>Current Image:</label><br>
            <img src="<?= $category['category_image']; ?>" alt="Category Image">
        </div>

        <div class="form-section">
            <label>New Image (optional):</label>
            <input type="file" name="category_image">
            <input type="hidden" name="existing_image" value="<?= $category['category_image']; ?>">
        </div>

        <button type="submit">Update Category</button>
    </form>
</div>
<?php include 'admin_footer.html'; ?>
</body>
</html>
