<?php
include("connect.php");
session_start();
if (!isset($_SESSION["username"])) {
    header("location:login.php");
    exit;
}

$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/view.css">
    <title>View User</title>
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
                <h1>View User</h1>
            </div>
            <div class="nav2">
                <ul>
                    <li><a href="admin.php">Admin Panel</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container">
            <h1>Users</h1>
            <br>
            <div class="grid">
                <?php
                // Query to get user data
                $query = "SELECT user_id, fullname, username, email FROM user WHERE usertype='user'";

                $result = mysqli_query($conn, $query);

                if ($result) {
                    // Check if any records were returned
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <div style="text-align: center; font-weight: bold" class="dis">
                           <div class="userimagecontainer">
                           <img src="../Pictures/admin_user_pfp.jpg" alt="pfp" style="width: 50%">
                           </div>
                                <br>
                                <label for="user_id" style='text-align:center; font-size:20px;'>User Id: <?php echo htmlspecialchars($row['user_id']); ?></label>
                                <br>
                                <label for="fullname">Full Name: <?php echo htmlspecialchars($row['fullname']); ?></label>
                                <br>
                                <label for="username">Username: <?php echo htmlspecialchars($row['username']); ?></label>
                                <br>
                                <label for="email">Email: <?php echo htmlspecialchars($row['email']); ?></label>
                                <br>
                                <!-- Form to delete the users -->
                                <form method="post" action="deleteuser.php">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($row['user_id']); ?>">
                                    <input type="hidden" name="fullname" value="<?php echo htmlspecialchars($row['fullname']); ?>">
                                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
                                    <button type="submit" class="cancel_button" onclick="return confirm('Are you sure you want to delete this user?')">Delete User</button>
                                </form>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p style='font-size:25px; margin:0px; color:red; font-weight: bold;'>No users found</p>";
                    }
                } else {
                    echo "<p style='color: red; font-size:20px;'>Error fetching data: " . mysqli_error($conn) . "</p>";
                }
                ?>
            </div>
        </div>

        <div class="clear"></div>

        <!-- Footer Section -->
        <div class="footer">
            <p>&copy; 2024, All Rights Reserved,<br>Designed by: <b>BusEase</b></p>
        </div>
    </div>
</body>

</html>
