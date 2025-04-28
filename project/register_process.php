<?php

include 'db.php';

// Registration Process (register_process.php)
if ($_SERVER["REQUEST_METHOD"] == "POST" ) {  //Check if form is submitted and user_id is set

    // Create connection
    // $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $address = $_POST['address'];

    // Password confirmation
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit(); //Stop further execution
    }

    // Check if email already exists (Important for UNIQUE constraint)
    $check_email_query = "SELECT email_id FROM users WHERE email_id = '$email'";
    $result = $conn->query($check_email_query);

    if ($result->num_rows > 0) {
        echo "Email already exists. Please use a different email.";
        exit();
    }

    // Skip hashing password and store plain password
    // Prepare and execute the SQL query
    $stmt = $conn->prepare("INSERT INTO users (full_name, email_id, phone_no, password, address) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fullname, $email, $phone_number, $password, $address); // Use prepared statements!

    if ($stmt->execute()) {
        echo "Registration successful";
        // Redirect or display a success message
        header("Location: home.php?status=success");
 // Example: redirect to a success page
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>