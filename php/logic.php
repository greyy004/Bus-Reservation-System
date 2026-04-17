<?php
include("connect.php");
session_start();

// Ensure the required session variables are set
if (!isset($_SESSION['username']) || !isset($_SESSION['bus_id']) || !isset($_SESSION['selectedSeats'])) {
    header("Location: user.php?error=invalid_access"); // Redirect if access is invalid
    exit();
}

$selectedSeats = $_SESSION['selectedSeats']; // Array of selected seats
$bus_id = $_SESSION['bus_id'];
$username = $_SESSION['username'];
$price = $_SESSION['price'] ?? 0; // Total price

// Calculate price per seat
$pricePerSeat = $price / count($selectedSeats);

// Insert each selected seat into the database
$errors = [];
foreach ($selectedSeats as $seat) {
    $insertQuery = "INSERT INTO ticket (seat, username, bus_id, price) 
                    VALUES ('$seat', '$username', $bus_id, $pricePerSeat)";

    if (!mysqli_query($conn, $insertQuery)) {
        // Log the error for debugging
        error_log("Error booking seat $seat: " . mysqli_error($conn));
        $errors[] = $seat; // Track the seat that failed to book
    }
}

if (empty($errors)) {
    // All seats were successfully booked, redirect to the view booked tickets page
    header("Location: ViewBookedTickets.php?booking=success");
    exit();
} else {
    // Some or all seats failed to book, redirect with an error message
    $failedSeats = implode(", ", $errors);
    header("Location: user.php?error=booking_failed&failed_seats=$failedSeats");
    exit();
}
?>
