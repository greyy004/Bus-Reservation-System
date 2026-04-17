<?php
include("connect.php");
session_start();

if (!isset($_SESSION["username"])) {
    header("location:login.php");
}

$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/view.css">
    <title>Booked Tickets</title>
</head>

<body>
    <div class="wrapper">
        <!-- Header Section -->
        <div class="header">
            <div class="logo">
                <a href="homepage.html">BusEase</a>
            </div>
        </div>

        <!-- Navigation Bar -->
        <div class="nav">
            <div class="nav1">
                <h1>Booked Tickets</h1>
            </div>
            <div class="nav2">
                <ul>
                    <li><a href="user.php">User Panel</a></li>
                    <li><a class="logout_button" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container">
            <h1 align="center">Your Booked Tickets</h1>
            <marquee style="color: red; font-size: 20px; font-weight: bold; margin-bottom:10px;">Note: The booking fee will not be returned in case of cancellation.</marquee>
            <div class="grid">
                <?php
                // Prepared Statement for SQL Injection Protection
                $query = "
                SELECT ticket.id AS ticket_id, bus_info.bus_id, bus_info.pickup, bus_info.destination, bus_info.price, ticket.seat
                FROM ticket
                JOIN bus_info ON ticket.bus_id = bus_info.bus_id
                WHERE ticket.username = ?"; // Use ? placeholder for username
                
                // Prepare the query
                if ($stmt = mysqli_prepare($conn, $query)) {
                    // Bind parameters
                    mysqli_stmt_bind_param($stmt, "s", $username); // "s" for string (username)
                    
                    // Execute the query
                    mysqli_stmt_execute($stmt);
                    
                    // Get result
                    $result = mysqli_stmt_get_result($stmt);
                    
                    if ($result) {
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <div style="text-align: center; font-weight: bold" class="dis">
                                    <img src="../Pictures/buslogopfp.png" alt="busphoto" style="width: 75%;">
                                    <br><br>
                                    <label>Pickup: <?php echo htmlspecialchars($row['pickup']); ?></label><br>
                                    <label>Destination: <?php echo htmlspecialchars($row['destination']); ?></label><br>
                                    <label>Seat No: <?php echo htmlspecialchars($row['seat']); ?></label><br>
                                    <label>Price: Rs <?php echo htmlspecialchars($row['price']); ?></label>
                                    <div class="button-container" style="display: flex; margin-top: 10px; gap: 10px;">
                                        <!-- Download Ticket Form -->
                                        <form method="post" action="ticket.php">
                                            <input type="hidden" name="ticket_id" value="<?php echo htmlspecialchars($row['ticket_id']); ?>">
                                            <button type="submit" class="cancel_button" style="background-color: #075cc3;" onmouseover="this.style.backgroundColor='#0772f4'" onmouseout="this.style.backgroundColor='#075cc3';">View Ticket</button>
                                        </form>
                                        <!-- Cancel Booking Form -->
                                        <form method="post" action="CancelBooking.php">
                                            <input type="hidden" name="bus_id" value="<?php echo htmlspecialchars($row['bus_id']); ?>">
                                            <input type="hidden" name="seat" value="<?php echo htmlspecialchars($row['seat']); ?>">
                                            <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
                                            <button type="submit" class="cancel_button">Cancel Booking</button>
                                        </form>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo "<p>No tickets found</p>";
                        }
                    } else {
                        echo "<p>Error fetching data: " . mysqli_error($conn) . "</p>";
                    }
                    
                    // Close the prepared statement
                    mysqli_stmt_close($stmt);
                }
                ?>
            </div>
        </div>

        <div class="clear"></div>

        <!-- Footer Section -->
        <div class="footer" style="margin-top: 0px;">
            <p>&copy; 2024, All Rights Reserved,<br>Designed by: <b>BusEase</b></p>
        </div>
    </div>
</body>

</html>
