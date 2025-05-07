<?php

session_start();

include('../includes/connect.php');
include('../functions/functions.php');


// Validate and sanitize input
if (isset($_POST['phoneNumber'])) {
    $phoneNumber = $_POST['phoneNumber']; // Sanitize as needed

    // Query the database to check if the phone number exists
    $getUserIDQuery = mysqli_query($conn, "
    SELECT *
    FROM user u
    JOIN membership m ON u.UserID = m.UserID
    WHERE u.NumPhone = '$phoneNumber' AND u.UserType = 'Customer'
    ");

    $row = mysqli_fetch_assoc($getUserIDQuery);

    // $getUserIDQuery = mysqli_query($conn, "SELECT UserID FROM user WHERE NumPhone = '$phoneNumber'");
    // $row = mysqli_fetch_assoc($getUserIDQuery);

    $totalPrice = 0;

    // Calculating total price
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $itemTotalPrice = $item['price'] * $item['quantity'];
            $totalPrice += $itemTotalPrice;
        }

        $totalPrice = number_format($totalPrice, 2);
    }
    
    // Check if a match is found
    if ($row) { 

        $userID = $row['UserID'];
        $TotalPointsCollect = $row['TotalPointsEarned'];        
        $points =  $totalPrice / 0.2;

        $response = [
            'userID' => $userID,
            'points' => $points,
            'collectedpoints' => $TotalPointsCollect
        ];

        // Return the points as a JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } else {

        $salah = "salah";  
        $takde = "none";      

        $response = [
            'salah' => $salah,
            'points' => $takde
        ];

        // No match found for the phone number
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
        
    }


} else {
    // Handle invalid or missing input
    echo json_encode(array('success' => false));
    exit;
}
?>
