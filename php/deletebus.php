<?php
include("connect.php");

if (isset($_POST['bus_id'])) {
    $bus_id = $_POST['bus_id'];

    // Delete the corresponding booked seats first
    $deleteSeatsQuery = "DELETE FROM ticket WHERE bus_id = '$bus_id'";
    if (mysqli_query($conn, $deleteSeatsQuery)) {
        // Now delete the bus
        $deleteBusQuery = "DELETE FROM bus_info WHERE bus_id = '$bus_id'";
        if (mysqli_query($conn, $deleteBusQuery)) {
            header("Location: viewbus.php?message=Bus and its booked seats deleted successfully.");
            exit();
        } else {
            echo "Error deleting bus: " . mysqli_error($conn);
        }
    } else {
        echo "Error deleting booked seats: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
?>
