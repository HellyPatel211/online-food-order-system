<?php
include 'db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cat_name = $_POST['cat_name'];
    $description = $_POST['description'];

    // Image upload handling
    $target_dir = "uploads/"; // Folder where images will be stored
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create folder if it doesn't exist
    }

    $target_file = $target_dir . basename($_FILES["category_image"]["name"]);

    // Move uploaded file
    if (!move_uploaded_file($_FILES["category_image"]["tmp_name"], $target_file)) {
        echo "<script>alert('Failed to upload image. Please try again.');</script>";
        exit();
    }

    // Insert query using prepared statements (more secure)
    $sql = "INSERT INTO category_data (cat_name, description, category_image) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $cat_name, $description, $target_file);

    if ($stmt->execute()) {
        echo "<script>alert('Category added successfully'); window.location.href='admincat.php';</script>";
    } else {
        echo "Error: " . $conn->error;
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
    <title>Add Category</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 500px;
            margin: 20px auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: calc(100% - 22px); /* Adjust for padding and border */
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box; /* Important for width calculation */
            font-size: 16px;
        }

        textarea {
            resize: vertical; /* Allow vertical resizing */
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Navigation and footer styling (adjust as needed) */
        nav, footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 10px 0;
        }
        nav a {
          color:white;
          text-decoration: none;
          padding: 5px 10px;
        }
    </style>
</head>
<body>
    <?php include 'admin_nav.html'; ?>
    <div class="form-container">
        <h2>Add New Category</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Category Name:</label>
            <input type="text" name="cat_name" required>
            <label>Description:</label>
            <textarea name="description" required></textarea>
            <label>Image:</label>
            <input type="file" name="category_image" required>
            <input type="submit" value="Add Category">
        </form>
    </div>
    <?php include 'admin_footer.html'; ?>
</body>
</html>