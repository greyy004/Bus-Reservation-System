<?php
include("connect.php");

session_start();

if (isset($_POST['log'])) {
    // Capture form inputs
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to select the user based on email
    $query = "SELECT * FROM user WHERE email='$email'";
    $run = mysqli_query($conn, $query);

    // Check if a user was found
    if ($run && mysqli_num_rows($run) > 0) {
        $row = mysqli_fetch_assoc($run);

        // Check if the provided password matches the stored password
        if ($row['password'] === $password) {
            // Store user data in session
            $_SESSION["username"] = $row['username']; // Assuming "username" exists in the database
            $_SESSION["email"] = $row['email'];

            // Redirect based on user type
            if ($row['usertype'] === "user") {
                header("location: user.php");
                exit();
            } elseif ($row['usertype'] === "admin") {
                header("location: admin.php");
                exit();
            }
        } else {
            echo '<script>alert("Incorrect Password");</script>'; // Password does not match
        }
    } else {
        echo '<script>alert("User Not Found");</script>'; // Email does not exist
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <title>Login</title>
</head>
<body>
    <form method="post">
        <legend>Login Form</legend>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Enter your email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" required>
        <br><br>
        <button type="submit" name="log" value="log" class="button">Login</button>
        <p>Don't have an account? <a href="register.php">Signup here</a>.</p>
    </form>
</body>
</html>
