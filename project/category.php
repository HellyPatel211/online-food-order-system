<?php
include 'db.php';
// Fetch all categories
$sql = "SELECT * FROM category_data";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Food Order System</title>
    <link rel="stylesheet" href="cate.css">
</head>

<body>
<?php include 'navbar.php'; ?>

    <h1 class="food">Food Categories</h1>
    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<a href="menu.php?cat_id=' . $row["cat_id"] . '" class="category-link"> 
                        <div class="category-card">
                            <img src="' . $row["category_image"] . '" alt="' . $row["cat_name"] . '"> 
                            <h2>' . $row["cat_name"] . '</h2>
                            <p>' . $row["description"] . '</p>
                        </div>
                      </a>';
            }
        } else {
            echo "<p>No categories available.</p>";
        }
        $conn->close();
        ?>
    </div>
    
    <?php include 'footer.html'; ?>
</body>

</html>
