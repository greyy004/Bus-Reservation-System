<?php 
session_start();
include 'connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    die("Access denied. Only logged-in users can access this page.");
}

// Fetch usertype from the database based on the session username
$username = $_SESSION['username'];
$query = "SELECT usertype FROM user WHERE username = '$username'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    
    // Check if the user is an admin
    if ($user['usertype'] !== 'admin') {
        die("Access denied. Only admins can access this page.");
    }
} else {
    die("Error fetching user details.");
}

// Get bus ID from POST request
$bus_id = $_POST['bus_id'];

// If bus_id is not provided, handle the error
if (!$bus_id) {
    die("No bus selected.");
}

// Fetch list of booked seats for the specific bus
$bookedSeatsQuery = "SELECT seat FROM ticket WHERE bus_id = $bus_id";
$bookedSeatsResult = mysqli_query($conn, $bookedSeatsQuery);

if (!$bookedSeatsResult) {
    die("Error executing query: " . mysqli_error($conn));  // Show the actual error if query fails
}

$bookedSeats = [];
if ($bookedSeatsResult) {
    while ($row = mysqli_fetch_assoc($bookedSeatsResult)) {
        $bookedSeats[] = $row['seat'];
    }
}

// Fetch the bus details to display bus_id (or any other details if needed)
$busDetailsQuery = "SELECT bus_id FROM bus_info WHERE bus_id = $bus_id";
$busDetailsResult = mysqli_query($conn, $busDetailsQuery);

if (!$busDetailsResult) {
    die("Error executing query: " . mysqli_error($conn));  // Show the actual error if query fails
}

$busDetails = mysqli_fetch_assoc($busDetailsResult);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Seat Selection</title>
    <link rel="stylesheet" href="../css/ViewBookedSeatsByAdmin.css">
</head>

<body>
    <div class="wrapper">
        <div class="header">
            <div class="logo">
                <a href="homepage.html">BusEase</a>
            </div>
        </div>

        <div class="nav">
            <div class="nav1">
                <h1>View Booked Seats</h1>
            </div>
            <div class="nav2">
                <ul>
                    <li><a class="l_button" href="admin.php">Admin Panel</a></li>
                    <li><a class="l_button" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>

        <div class="container">
            <div class="bus-container">
                <!-- Display Bus ID -->
                <h1><?php echo"Bus ID: ". htmlspecialchars($busDetails['bus_id']); ?></h1>
                <br>
                <div class="bookedavailablecontainer">
                <h3><span class="color-box booked"></span>Booked</h3>
                <h3><span class="color-box available"></span>Available</h3>
                </div>

                <div class="bus-layout">
                    <div class="seat-row">
                        <div class="seat" id="L1">L1</div>
                        <div class="seat" id="L2">L2</div>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="seat" id="R1">R1</div>
                        <div class="seat" id="R2">R2</div>
                    </div>
                    <div class="seat-row">
                        <div class="seat" id="L3">L3</div>
                        <div class="seat" id="L4">L4</div>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="seat" id="R3">R3</div>
                        <div class="seat" id="R4">R4</div>
                    </div>
                    <div class="seat-row">
                        <div class="seat" id="L5">L5</div>
                        <div class="seat" id="L6">L6</div>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="seat" id="R5">R5</div>
                        <div class="seat" id="R6">R6</div>
                    </div>
                    <div class="seat-row">
                        <div class="seat" id="L7">L7</div>
                        <div class="seat" id="L8">L8</div>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="seat" id="R7">R7</div>
                        <div class="seat" id="R8">R8</div>
                    </div>
                    <div class="seat-row">
                        <div class="seat" id="L9">L9</div>
                        <div class="seat" id="L10">L10</div>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="seat" id="R9">R9</div>
                        <div class="seat" id="R10">R10</div>
                    </div>
                    <button type="button "class="cancel_button" onclick="document.location='viewbus.php'">Go Back</button>
                </div>
            </div>

        <div class="clear"></div>

        <div class="footer">
            <p>&copy; 2024, All Rights Reserved,<br>Designed by: <b>BusEase</b></p>
        </div>
    </div>

    <script>


        const bookedSeats = [
    <?php 
        foreach ($bookedSeats as $seat) {
            echo "'$seat', "; // Output each booked seat ID as a JavaScript string
        }
    ?>
];

// Highlight Booked Seats
bookedSeats.forEach(seat => {
    const seatElement = document.getElementById(seat); // Find seat by ID
    if (seatElement) {
        seatElement.classList.add('booked'); // Add 'booked' class
    }
});

    </script>
</body>

</html>
