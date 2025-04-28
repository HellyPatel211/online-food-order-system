<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<link rel="stylesheet" href="navbar.css">
</head>

<body>
    <nav class="navbar">
        <div class="navbar-left">
            <span class="site-name"><a href="home.php">Foodie Finds </a></span>
        </div>

        <div class="navbar-right">
            <a href="category.php" class="nav-link">Food Categories</a>
            <a href="menu.php" class="nav-link">Food Menu</a>
            <a href="cart.php" class="nav-link">My Cart</a>
            <!-- <a href="logout.php" class="nav-link">logout</a> -->
            <?php
            // session_start();
            if (isset($_SESSION['user_id']) || isset($_SESSION['admin_id']) || isset($_SESSION['delivery_id'])) {
                // User is logged in, show Logout button
                echo '<a href="logout.php" class="nav-link">Logout</a>';
            } else {
                // User is not logged in, show Login button
                echo '<a href="login.php" class="nav-link">Login</a>';
            }
            ?>

            <a href="profile.php" class="nav-link profile-logo">
          
                <img src="pro.png" alt="Profile" class="profile-icon">
            </a>
        </div>
    </nav>
    
</body>

</html>