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
    <title>View Bus</title>
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
            <h1>View Bus</h1>
            <div class="nav2">
                <ul>
                    <li><a class="l_button" href="admin.php">Admin Panel</a></li>
                    <li><a class="l_button" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container">
            <h1>Buses</h1>
            <br>
            <div class="grid">
                <?php
                $query = "SELECT bus_id, pickup, destination, date, price FROM bus_info;";

                $result = mysqli_query($conn, $query);

                if ($result) {
                    // Check if any records were returned
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Format date and time to 12-hour format (MM/DD/YYYY hh:mm AM/PM)
                            $formattedDateTime = date('m/d/Y h:i A', strtotime($row['date']));
                            ?>
                            <div style="text-align: center; font-weight: bold" class="dis">
                                <div class="userimagecontainer">
                                    <img src="../Pictures/buslogopfp.png" alt="pfp" style="width: 50%">
                                </div>
                                <br>
                                <label for="bus_id" style='text-align:center; font-size: 20px;'>Bus Code:
                                    <?php echo $row['bus_id']; ?></label>
                                <br>
                                <label for="pickup_point">Pickup: <?php echo $row['pickup']; ?></label>
                                <br>
                                <label for="destination_point">Destination: <?php echo $row['destination']; ?></label>
                                <br>
                                <label for="date">Date: <?php echo $formattedDateTime; ?></label>
                                <br>
                                <label for="price">Price: Rs <?php echo $row['price']; ?></label>
                                <br>
                                <!-- Form to delete the bus -->
                                <div class="divide_button">
                                    <form method="post" action="ViewBookedSeatsByAdmin.php">
                                        <input type="hidden" name="bus_id" value="<?php echo $row['bus_id']; ?>">
                                        <button type="submit" class="cancel_button" style="background-color: #075cc3;"
                                            onmouseover="this.style.backgroundColor='#0772f4'"
                                            onmouseout="this.style.backgroundColor='#075cc3';">View Bus</button>
                                    </form>
                                    <form method="post" action="deletebus.php">
                                        <input type="hidden" name="bus_id" value="<?php echo $row['bus_id']; ?>">
                                        <button type="submit" class="cancel_button">Delete Bus</button>
                                    </form>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p style='font-size:25px; margin:0px; color:red; font-weight: bold;''>No buses found</p>";
                    }
                } else {
                    echo "<p style='text-align: center;'>Error fetching data: " . mysqli_error($conn) . "</p>";
                }
                ?>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>&copy; 2024, All Rights Reserved,<br>Designed by: <b>BusEase</b></p>
        </div>
    </div>
</body>

</html>