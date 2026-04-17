<?php
include("connect.php");
session_start();

// Capture bus_id when ticket is selected
if (isset($_POST['bus_id'])) {
    $_SESSION['bus_id'] = $_POST['bus_id'];
}

// Fetch booked seats from the database
$bookedSeats = [];
$query = "SELECT seat FROM ticket WHERE bus_id = " . $_SESSION['bus_id'];
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $bookedSeats[] = $row['seat'];
}

$bookingMessage = '';

// Handle seat booking form submission
if (isset($_POST['submit'])) {
    $selectedSeats = json_decode($_POST['selectedSeats'], true) ?? [];

    if (!empty($selectedSeats) && isset($_SESSION['bus_id'])) {
        // Check if any of the selected seats are already booked
        $alreadyBookedSeats = array_intersect($selectedSeats, $bookedSeats);

        if (empty($alreadyBookedSeats)) {
            // Fetch price for the selected bus
            $priceQuery = "SELECT price FROM bus_info WHERE bus_id = " . $_SESSION['bus_id'];
            $priceResult = mysqli_query($conn, $priceQuery);

            if ($priceResult) {
                $priceRow = mysqli_fetch_assoc($priceResult);
                $price = $priceRow['price'] ?? 0;

                // Store booking details in the session
                $_SESSION['selectedSeats'] = $selectedSeats;
                $_SESSION['price'] = $price * count($selectedSeats); // Calculate total price

                // Prepare data for Khalti API
                $khaltiData = [
                    "return_url" => "http://localhost/brs/php/payment-response.php",
                    "website_url" => "http://localhost/brs/",
                    "amount" => $_SESSION['price'] * 100, // Convert to paisa
                    "purchase_order_id" => uniqid("order_"), // Generate a unique order ID
                    "purchase_order_name" => "Bus Ticket: Seats " . implode(", ", $selectedSeats),
                    "customer_info" => [
                        "name" => $_SESSION['username'], // Assuming 'username' is stored in session
                        "email" => $_SESSION['email'],   // Assuming 'email' is stored in session
                    ]
                ];

                // Initiate Khalti Payment
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/initiate/',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => json_encode($khaltiData),
                    CURLOPT_HTTPHEADER => [
                        'Authorization: Key live_secret_key_68791341fdd94846a146f0457ff7b455',
                        'Content-Type: application/json',
                    ],
                ]);

                $response = curl_exec($curl);
                curl_close($curl);

                $responseArray = json_decode($response, true);
                if (isset($responseArray['payment_url'])) {
                    // Redirect to the payment page
                    header("Location: " . $responseArray['payment_url']);
                    exit();
                } else {
                    $bookingMessage = "Payment initiation failed. Please try again.";
                }
            } else {
                $bookingMessage = "Error fetching price: " . mysqli_error($conn);
            }
        } else {
            $bookingMessage = "The following seats are already booked: " . implode(", ", $alreadyBookedSeats);
        }
    } else {
        $bookingMessage = "Please select at least one seat.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Seat Selection</title>
    <link rel="stylesheet" href="../css/seatselect.css">
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="logo">
                <a href="homepage.html">BusEase</a>
            </div>
        </div>

        <div class="nav">
            <h1>Bus Seat Selection</h1>
            <div class="nav2">
                <ul>
                    <li><a class="l_button" href="user.php">User Panel</a></li>
                    <li><a class="l_button" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>

        <div class="container">
            <div class="bus-container">
            <h1>Seat Selection</h1>
            <br>
            <div class="bookedavailableselectedcontainer">
                <h3><span class="color-box booked"></span>Booked</h3>
                <h3><span class="color-box available"></span>Available</h3>
                <h3><span class="color-box selected"></span>Selected</h3>
            </div>
            <div class="bus-layout">
                <?php
                $allSeats = ['L1', 'L2', 'R1', 'R2', 'L3', 'L4', 'R3', 'R4', 'L5', 'L6', 'R5', 'R6', 'L7', 'L8', 'R7', 'R8', 'L9', 'L10', 'R9', 'R10'];
                foreach ($allSeats as $seat) {
                    $isBooked = in_array($seat, $bookedSeats) ? "booked" : "";
                    echo "<div class='seat $isBooked' data-seat='$seat'>$seat</div>";
                }
                ?>
            </div>
            <form method="post">
                <input type="hidden" name="selectedSeats" id="selectedSeats">
                <button type="submit" name="submit" class="buttons">Book and Pay with Khalti</button>
            </form>
            </div>
        </div>

        <div class="footer">
            <p>&copy; 2024, All Rights Reserved,<br>Designed by: <b>BusEase</b></p>
        </div>
    </div>

    <script>
        const seats = document.querySelectorAll('.seat:not(.booked)');
        const selectedSeatsInput = document.getElementById('selectedSeats');
        const selectedSeats = [];

        seats.forEach(seat => {
            seat.addEventListener('click', () => {
                seat.classList.toggle('selected');
                const seatNumber = seat.getAttribute('data-seat');

                if (seat.classList.contains('selected')) {
                    selectedSeats.push(seatNumber);
                } else {
                    const index = selectedSeats.indexOf(seatNumber);
                    if (index > -1) selectedSeats.splice(index, 1);
                }

                selectedSeatsInput.value = JSON.stringify(selectedSeats);
            });
        });
    </script>
</body>
</html>
