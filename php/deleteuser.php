<?php
include("connect.php");
session_start();

if (!isset($_SESSION["username"])) {
    header("location:login.php");
    exit(); // Ensure script stops after redirect
}

// Check if the required data is set
if (isset($_POST['user_id'])) { // Expecting 'user_id' from form
    $user_id = $_POST['user_id'];

    // Query to get the username associated with the user_id
    $getUsernameQuery = "SELECT username FROM user WHERE user_id = '$user_id'";
    $usernameResult = mysqli_query($conn, $getUsernameQuery);
    $usernameRow = mysqli_fetch_assoc($usernameResult);

    if ($usernameRow) {
        $username = $usernameRow['username'];

        // First, delete the user's booked seats using the username
        $deleteSeatsQuery = "DELETE FROM ticket WHERE username = '$username'";
        if (mysqli_query($conn, $deleteSeatsQuery)) {
            // Proceed to delete the user after deleting booked seats
            $deleteUserQuery = "DELETE FROM user WHERE user_id = '$user_id'";
            if (mysqli_query($conn, $deleteUserQuery)) {
                // Successfully deleted user and their booked seats
                header("Location: viewuser.php?message=User and their booked seats deleted successfully.");
                exit(); // Ensure script stops after redirect
            } else {
                echo "Error deleting user: " . mysqli_error($conn);
            }
        } else {
            echo "Error deleting booked seats: " . mysqli_error($conn);
        }
    } else {
        echo "No users found.";
    }
} else {
    echo "Invalid request.";
}
?>
