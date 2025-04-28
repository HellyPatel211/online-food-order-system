<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // User type (user, admin, delivery)

    if ($role == "user") {
        $sql = "SELECT * FROM users WHERE email_id = ? AND password = ?";
    } elseif ($role == "admin") {
        $sql = "SELECT * FROM admin WHERE email = ? AND password = ?";
    } elseif ($role == "delivery") {
        $sql = "SELECT * FROM delivery_boy WHERE email = ? AND password = ?";
    } else {
        echo "<script>alert('Invalid role selected!'); window.location.href='login.php';</script>";
        exit();
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if ($role == "user") {
            $_SESSION['user_id'] = $user['user_id'];
            header("Location: profile.php");
        } elseif ($role == "admin") {
            $_SESSION['admin_id'] = $user['id'];
            header("Location: admin.php");
        } elseif ($role == "delivery") {
            $_SESSION['delivery_id'] = $user['id'];
            $_SESSION['delivery_name'] = $user['name'];
            header("Location: delivery_dashboard.php");
        }
        exit();
    } else {
        echo "<script>alert('Invalid credentials!'); window.location.href='login.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div class="nav">
        <h1>Foodie Finds</h1>
    </div>
    <div class="back">
        <div class="container">
            <form method="POST">
                <p class="tagline">India's Best Food Delivery App<br>Login or Sign Up Below</p>
                <label>Select Role:</label>
                <select name="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                    <option value="delivery">Delivery Person<option>
                </select>

                <div class="input-container">
               
                    <input type="email" name="email" class="input-field" placeholder="Enter Email" required>
                </div>

                <div class="input-container">
                    <input type="password" name="password" class="input-field" placeholder="Enter Password" required>
                </div>
                
                <button type="submit" class="btn">Login</button>
                <button type="button" class="btn" onclick="newpage()">New Register</button>
            </form>
        </div>
    </div>
    <div class="footer">
        <p class="footer-text">
            By continuing, you agree to our Terms of Service.<br>
            Thank you for choosing us! <b>Welcome to Foodie Finds! 🍽️</b>
        </p>
    </div>
    <script>
        function newpage() {
            window.location.href = "reg.html";
        }
    </script>
</body>

</html>