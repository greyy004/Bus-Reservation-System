<?php
include("connect.php");
session_start();

// Check if the admin is logged in, otherwise redirect to login page
if (!isset($_SESSION["username"])) {
    header("location:login.php");
}

// Handle form submission for setting a ticket
if (isset($_POST['set-ticket'])) {
    $pickup = $_POST['pickup-point'];
    $destination = $_POST['destination-point'];
    $date = $_POST['date'];
    $price = $_POST['price'];

    // Insert the new ticket information into the database
    $query = "INSERT INTO bus_info (`pickup`, `destination`, `date`, `price`) VALUES ('$pickup', '$destination', '$date', '$price');";

    // Check if the query was successful
    if (mysqli_query($conn, $query)) {
        echo '<script>alert("Ticket set successfully");</script>';
    } else {
        echo '<script>alert("Error occurred while setting the ticket");</script>';
    }
}

$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../css/admin.css">
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
                <h1>Admin Panel</h1>
            </div>
            <div class="nav2">
                <ul>
                    <li><a class="l_button" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-container">
            <!-- Left Section: Admin Info -->
            <div class="dashboard">
                <h2 align="center">Admin Dashboard</h2>
                <img src="../Pictures/admin_user_pfp.jpg" alt="pfp">
                <?php
                $query = "SELECT user_id, fullname, username, email, password FROM user WHERE username='$username';";
                $result = mysqli_query($conn, $query);
                if ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="admin_info_displayer">
                        <label for="user_id">Admin ID: <?php echo $row['user_id']; ?></label>
                        <br><br>
                        <label for="fullname">Full Name: <?php echo $row['fullname']; ?></label>
                        <br><br>
                        <label for="username">Username: <?php echo $row['username']; ?></label>
                        <br><br>
                        <label for="email">Email: <?php echo $row['email']; ?></label>
                        <br><br>
                        <label for="password">Password: <?php echo str_repeat('*', strlen($row['password'])); ?></label>
                        <br><br>
                    </div>
                    <?php
                } else {
                    echo "<p style='color: red; font-size:20px;'>Error fetching data: " . mysqli_error($conn) . "</p>";
                }
                ?>
            </div>

            <!-- Middle Section: Info Boxes -->
            <div class="info">
                <div class="pop_count">
                    <h2>Total Users:
                        <?php
                        $query = "SELECT COUNT(*) AS total_users FROM user WHERE usertype='user'";
                        $result = mysqli_query($conn, $query);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            printf("%d", $row['total_users']);
                        } else {
                            echo "Query failed: " . mysqli_error($conn);
                        }
                        ?>
                    </h2>
                    <button type="submit" onclick="document.location='viewuser.php'" class="pop_count_button">View Users</button>
                </div>

                <div class="pop_count">
                    <h2>Total Admins:
                        <?php
                        $query = "SELECT COUNT(*) AS total_admins FROM user WHERE usertype='admin'";
                        $result = mysqli_query($conn, $query);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            printf("%d", $row['total_admins']);
                        } else {
                            echo "Query failed: " . mysqli_error($conn);
                        }
                        ?>
                    </h2>
                    <button type="submit" onclick="document.location='viewadmin.php'" class="pop_count_button">View Admins</button>
                </div>

                <div class="pop_count">
                    <h2>Total Buses:
                        <?php
                        $query = "SELECT COUNT(*) AS total_buses FROM bus_info";
                        $result = mysqli_query($conn, $query);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            printf("%d", $row['total_buses']);
                        } else {
                            echo "Query failed: " . mysqli_error($conn);
                        }
                        ?>
                    </h2>
                    <button type="submit" onclick="document.location='viewbus.php'" class="pop_count_button">View Buses</button>
                </div>

                <div class="pop_count">
                    <h2>Revenue:
                        <?php
                        $query = "SELECT SUM(price) AS sum FROM ticket";
                        $result = mysqli_query($conn, $query);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            printf("Rs. %d", $row['sum']);
                        } else {
                            echo "Query failed: " . mysqli_error($conn);
                        }
                        ?>
                    </h2>
                </div>

                <div class="pop_count">
                    <h2>Total Seats Booked:
                        <?php
                        $query = "SELECT COUNT(id) AS total_seats_booked FROM ticket";
                        $result = mysqli_query($conn, $query);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            printf("%d", $row['total_seats_booked']);
                        } else {
                            echo "Query failed: " . mysqli_error($conn);
                        }
                        ?>
                    </h2>
                </div>
            </div>

            <!-- Right Section: Ticket Form -->
            <div class="ticket_maker">
                <form method="post">
                    <legend>Bus Time</legend>
                    <label for="pickup-point">Pickup: </label>
                    <select name="pickup-point" id="pickup-point" required>
                        <option value="" disabled selected hidden>Select your pickup point</option>
                        <option value="Kathmandu">Kathmandu</option>
                        <option value="Bhaktapur">Bhaktapur</option>
                        <option value="Lalitpur">Lalitpur</option>
                        <option value="Kirtipur">Kirtipur</option>
                        <option value="Hetauda">Hetauda</option>
                    </select>
                    <br>
                    <label for="destination-point">Destination: </label>
                    <select name="destination-point" id="destination-point" required>
                        <option value="" disabled selected hidden>Select your destination</option>
                        <option value="Kathmandu">Kathmandu</option>
                        <option value="Bhaktapur">Bhaktapur</option>
                        <option value="Lalitpur">Lalitpur</option>
                        <option value="Kirtipur">Kirtipur</option>
                        <option value="Hetauda">Hetauda</option>
                    </select>
                    <br>
                    <label for="date">Set Date and Time: </label>
                    <input type="datetime-local" id="date" name="date" placeholder="Set your date and time" required>
                    <br>
                    <label for="price">Ticket Price: </label>
                    <input type="number" id="price" name="price" placeholder="Enter the ticket price" min="10" required>
                    <br>
                    <button type="submit" name="set-ticket" value="set-ticket" class="admin_ticket_selector">Set Ticket</button>
                </form>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="footer" style="margin-top: 0px;">
            <p>&copy; 2024, All Rights Reserved,<br>Designed by: <b>BusEase</b></p>
        </div>

    </div>

    <script>
        // Script for date validation
        var today = new Date();
        var day = (today.getDate() < 10 ? '0' : '') + today.getDate();
        var month = (today.getMonth() + 1 < 10 ? '0' : '') + (today.getMonth() + 1);
        var year = today.getFullYear();
        var hours = (today.getHours() < 10 ? '0' : '') + today.getHours();
        var minutes = (today.getMinutes() < 10 ? '0' : '') + today.getMinutes();
        var minDateTime = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
        document.getElementById('date').setAttribute('min', minDateTime);
    </script>
</body>

</html>
