<?php
session_start();
include 'connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    die("Access denied. Please log in first.");
}

$username = $_SESSION['username'];

// Ensure ticket_id is passed through the URL
if (isset($_POST['ticket_id'])) {
    $ticket_id = $_POST['ticket_id'];

    // SQL query with the correct references
    $sql = "
    SELECT 
        ticket.id AS ticket_id, 
        bus_info.bus_id, 
        bus_info.pickup, 
        bus_info.destination, 
        DATE_FORMAT(bus_info.date, '%Y-%m-%d') AS date,  -- Correct date reference
        DATE_FORMAT(bus_info.date, '%h:%i %p') AS time,  -- Correct time format
        ticket.username, 
        ticket.seat, 
        ticket.price
    FROM 
        ticket
    JOIN 
        bus_info
    ON 
        ticket.bus_id = bus_info.bus_id
    WHERE 
        ticket.username = ? AND ticket.id = ?"; // Prepared statement placeholders

    // Prepare the query
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "si", $username, $ticket_id);  // "si" means string and integer

        // Execute the query
        mysqli_stmt_execute($stmt);

        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        // Check if the query returns any rows
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        } else {
            $row = null; // No ticket found
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        die("Query preparation failed: " . mysqli_error($conn));
    }
} else {
    die("No ticket ID provided.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Ticket</title>
    <link rel="stylesheet" href="../css/ticket.css">
    <style>
        /* Hide the buttons when generating PDF */
        .hide-for-pdf {
            display: none;
        }
    </style>
</head>
<body>
    <?php if ($row) { ?>
    <div class="ticket-container" id="ticket">
        <div class="ticket-header">
            <h1>BusEase</h1>
            <p>Bus Ticket</p>
        </div>
        <div class="ticket-body">
            <div class="ticket-row">
                <span class="label">Ticket ID:</span>
                <span class="value"><?php echo $row['ticket_id']; ?></span>
            </div>
            <div class="ticket-row">
                <span class="label">Pickup:</span>
                <span class="value"><?php echo $row['pickup']; ?></span>
            </div>
            <div class="ticket-row">
                <span class="label">Destination:</span>
                <span class="value"><?php echo $row['destination']; ?></span>
            </div>
            <div class="ticket-row">
                <span class="label">Date:</span>
                <span class="value"><?php echo $row['date']; ?></span>
            </div>
            <div class="ticket-row">
                <span class="label">Time:</span>
                <span class="value"><?php echo $row['time']; ?></span>
            </div>
            <div class="ticket-row">
                <span class="label">Username:</span>
                <span class="value"><?php echo $row['username']; ?></span>
            </div>
            <div class="ticket-row">
                <span class="label">Seat:</span>
                <span class="value"><?php echo $row['seat']; ?></span>
            </div>
            <div class="ticket-row">
                <span class="label">Price:</span>
                <span class="value"><?php echo $row['price']; ?></span>
            </div>
        </div>
        <div class="ticket-footer">
            <p>Thank you for traveling with <span class="busease">BusEase</span></p>
        </div>
        <div class="btn-container">
            <!-- Go Back Button -->
            <button class="btn" onclick="location.href='ViewBookedTickets.php'">Go Back</button>

            <!-- Download Ticket Button -->
            <button id="download-btn" class="btn" onclick="downloadPDF()">Download as PDF</button>
        </div>
    </div>
    <?php } else { ?>
        <p>No ticket found for the selected seat.</p>
    <?php } ?>

    <!-- html2pdf.js library -->
    <script src="../html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF() {
            // Hide the buttons temporarily before generating the PDF
            const buttons = document.querySelectorAll('.btn-container button');
            buttons.forEach(button => button.classList.add('hide-for-pdf'));

            const ticket = document.getElementById('ticket');
            const options = {
                margin: 10,
                filename: 'bus_ticket.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            html2pdf().set(options).from(ticket).save().then(() => {
                // Show buttons again after the PDF is saved
                buttons.forEach(button => button.classList.remove('hide-for-pdf'));
            });
        }
    </script>
</body>
</html>
