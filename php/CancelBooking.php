<?php
include("connect.php");
session_start();
if (!isset($_SESSION["username"])) {
    header("location:login.php");
    exit(); // Ensure script stops after redirect
}
$username = $_SESSION["username"];

// Check if the required data is set
if (isset($_POST['seat']) && isset($_POST['username'])) {
    $seat = $_POST['seat'];

    // Query to check if the booking exists
    $checkQuery = "
        SELECT seat, username
        FROM ticket
        WHERE seat = '$seat' AND username = '$username'
    ";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Query to delete the booked seat from booked_seats table
        $deleteQuery = "
            DELETE FROM ticket
            WHERE seat = '$seat' AND username = '$username'
        ";
        if (mysqli_query($conn, $deleteQuery)) {
            // Successfully deleted
            header("Location: user.php?message=Booking cancelled successfully.");
            exit(); // Ensure script stops after redirect
        } else {
            echo "Error cancelling booking: " . mysqli_error($conn);
        }
    } else {
        echo "No booking found to cancel.";
    }
} else {
    echo "Invalid request.";
}
?>
