<?php
include("connect.php");
session_start();
if (!isset($_SESSION["username"])) {
    header("location:login.php");
}
$username = $_SESSION["username"];

// Fetch user data
$userQuery = "SELECT user_id, fullname, username, email, password FROM user WHERE username='$username';";
$userResult = mysqli_query($conn, $userQuery);
if (!$userResult) {
    die("Error fetching user data: " . mysqli_error($conn));
}

// Fetch available buses from the database
$ticketQuery = "SELECT * FROM bus_info";
$ticketResult = mysqli_query($conn, $ticketQuery);
if (!$ticketResult) {
    die("Error fetching tickets: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/user.css">
    <title>User Panel</title>
</head>

<body onload="greetUser()">
    <!-- Header Section -->
    <div class="header">
        <div class="logo">
            <a href="homepage.html">BusEase</a>
        </div>
    </div>

    <!-- Navigation Bar -->
    <div class="nav">
        <div class="nav1">
            <h1>User Panel</h1>
        </div>
        <div class="nav2">
            <ul>
                <li><a href="ViewBookedTickets.php">View Booked Tickets</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        <h1 id="greeting" style="font-family: myfont; padding-left: 40px; margin-top: 22px; color:#353535;"></h1>
        <h1 style="text-shadow:0 0 3px #353535; color:white; text-transform: uppercase; text-align:center; font-size: 50px; font-family: myfont; margin-bottom:20px;">Available Buses</h1>

        <!-- Ticket Grid Section -->
        <div class="ticket_grid">
            <?php
            if (mysqli_num_rows($ticketResult) > 0) {
                while ($ticketRow = mysqli_fetch_assoc($ticketResult)) {
                    ?>
                    <form method="post" action="seatselect.php">
                        <div style="text-align: center; font-weight: bold" class="dis">
                            <img src="../Pictures/buslogopfp.png" alt="busphoto" style="width: 75%;">
                            <input type="hidden" name="bus_id" value="<?php echo $ticketRow['bus_id']; ?>">
                        </div>
                        <br>
                        <label for="pickup-point">Pickup Point: <?php echo ($ticketRow['pickup']); ?></label><br>
                        <label for="destination-point">Destination Point: <?php echo ($ticketRow['destination']); ?></label><br>
                        <label for="date">
                            Date:
                            <?php
                            $date = date('Y-m-d h:i A', strtotime($ticketRow['date']));
                            echo $date;
                            ?>
                        </label><br>
                        <label for="price">Price: Rs <?php echo ($ticketRow['price']); ?></label><br>
                        <button type="submit" class="ticket_button">Select Ticket</button>
                    </form>
                    <?php
                }
            } else {
                echo "<p style='font-size:20px; margin: 0px;'>No available buses</p>";
            }
            ?>
        </div>
    </main>

    <!-- Footer Section -->
    <div class="footer" >
        <p>&copy; 2024, All Rights Reserved,<br>Designed by: <b>BusEase</b></p>
    </div>

    <!-- JavaScript for Greeting -->
    <script>
        function greetUser() {
            const greetingElement = document.getElementById("greeting");
            const currentTime = new Date();
            const hours = currentTime.getHours();

            let greetingMessage = "Good Morning";
            if (hours >= 12 && hours < 18) {
                greetingMessage = "Good Afternoon";
            } else if (hours >= 18) {
                greetingMessage = "Good Evening";
            }

            const username = "<?php echo $_SESSION['username']; ?>";
            greetingElement.textContent = greetingMessage + " " + username;
        }
    </script>
</body>
</html>
