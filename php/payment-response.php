<?php
session_start();
$pidx = $_GET['pidx'] ?? null;

if ($pidx) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/lookup/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode(['pidx' => $pidx]),
        CURLOPT_HTTPHEADER => [
            'Authorization: Key live_secret_key_68791341fdd94846a146f0457ff7b455',
            'Content-Type: application/json',
        ],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    $responseArray = json_decode($response, true);
    if ($responseArray['status'] === 'Completed') {
        header("Location: logic.php"); // Redirect to booking logic
        exit();
    } else {
        header("Location: seatselect.php"); // Redirect to seat selection on failure
        exit();
    }
}
?>
