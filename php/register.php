<?php
// Include the database connection file
include("connect.php");

// Main code to handle user registration
if (isset($_POST['register'])) {
    // Retrieve form data
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    //check if fullname consists of number
    if (preg_match('/\d/', $fullname)) {
        echo'<script>alert("Full name cannot contain numbers."); window.location.href="register.php";</script>';
        exit();
    }
    // Validate username format (alphanumeric only)
    if (!preg_match('/^[a-zA-Z0-9@#\-_$%^&+=!]{6,}$/', $username)) {
        echo '<script>alert("Username must be at least 6 characters long and can include special characters."); window.location.href="register.php";</script>';
        exit();
    }

    // Validate password format (minimum 6 characters, allow special characters)
    if (!preg_match('/^[a-zA-Z0-9@#\-_$%^&+=!]{6,}$/', $password)) {
        echo '<script>alert("Password must be at least 6 characters long and can include special characters (@#-$%^&+=!)."); window.location.href="register.php";</script>';
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Invalid Email"); window.location.href="register.php";</script>';
        exit();
    }

    // Check if a user with the same email already exists
    $checkQuery = "SELECT * FROM user WHERE email = '$email'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // If email already exists, show an alert
        echo '<script>alert("Email already exists. Please choose another one."); window.location.href="register.php";</script>';
    } else {
        // Insert new user details into the user table without hashing the password
        $query = "INSERT INTO `user` (`fullname`, `email`, `username`, `password`, `usertype`) 
                  VALUES ('$fullname', '$email', '$username', '$password', 'user');";

        // Check if the insertion is successful
        if (mysqli_query($conn, $query)) {
            echo '<script>alert("Registration successful!"); window.location.href="login.php";</script>';
        } else {
            echo '<script>alert("Signup unsuccessful. Please try again later.");</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="../css/register.css">
</head>
<body>
    <br><br>
    <form method="post" action="">
        <legend>Create an Account</legend>
        <input type="text" name="fullname" id="fullname" placeholder="Enter your full name" required>
        <input type="text" name="username" id="username" placeholder="Enter your username" required>
        <input type="email" name="email" id="email" placeholder="Enter your email" required>
        <input type="password" name="password" id="password" placeholder="Enter your password" required>
        <br>
        <button type="submit" value="register" name="register" class="button">Signup</button>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </form>
    
</body>
</html>
