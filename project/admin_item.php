<?php
include 'db.php'; // Include database connection

// Fetch all categories
$sql = "SELECT * FROM item_data";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Items</title>
    <style>
        body {
            background-color: #f7fafc;
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            height: 100%;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
        }

        table {
            width: 80%;
            margin: auto;
            margin-top: 10px;
            background-color: #ffffff;
            border-collapse: collapse;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        .btn {
            display: inline-block;
            /* makes buttons line up side by side */
            padding: 5px 10px;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            margin: 2px;
            /* small space between them */
        }

        .edit-btn {
            background-color: #007bff;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .add-btn {
            /* color:black; */
            background-color: green;

        }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php include 'admin_nav.html'; ?>
        <main>
            <h1>Manage items</h1>
            <a href="add_item.php" class="btn add-btn">➕ Add New item</a>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['item_id']; ?></td>
                        <td><?= $row['item_name']; ?></td>
                        <td><?= $row['price']; ?></td>
                        <td><?= $row['description']; ?></td>
                        <td><img src="<?= $row['image']; ?>" width="50"></td>
                        <td>
                            <a href="update_item.php?item_id=<?= $row['item_id']; ?>" class="btn edit-btn">Update</a>
                            <a href="delete_item.php?item_id=<?= $row['item_id']; ?>" class="btn delete-btn"
                                onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </main>
        <?php include 'admin_footer.html'; ?>
    </div>
</body>

</html>