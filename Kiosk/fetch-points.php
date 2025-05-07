<?php
session_start();

include('../includes/connect.php');
include('../functions/functions.php');


if(isset($_GET['userID'])){

    $userID = $_GET['userID'];

    // Fetch TotalPointsEarned based on UserID from your membership table
    $getTP = mysqli_query($conn, "SELECT TotalPointsEarned FROM membership WHERE UserID = '$userID'");
    $row = mysqli_fetch_assoc($getTP);
    
    // Check if a match is found
    if ($row) { 

        $totalPointsEarned = $row['TotalPointsEarned'];

        // Calculate RMRedeem, RMRedeem2, and pointsRedeem2
        if($totalPointsEarned >= 100){
            
            $totalPrice = 0;

            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    $itemTotalPrice = $item['price'] * $item['quantity'];
                    $totalPrice += $itemTotalPrice;
                }

                $totalPrice = number_format($totalPrice, 2);
            }

            $RMRedeem = $totalPointsEarned / 100;
            $RMRedeem = intval($RMRedeem);


            if($RMRedeem > $totalPrice){
                $RMRedeem2 = $totalPrice;
                $pointsRedeem2 = $RMRedeem2 * 100;
                $totalPriceBaru = $RMRedeem2-$totalPrice;

            } else {
                $RMRedeem2 = $RMRedeem;
                $pointsRedeem2 = $RMRedeem2 * 100;
                $totalPriceBaru = $totalPrice-$RMRedeem2;

            }

            $true="true";

            // Construct the response array
            $response = [
                'TotalPointsEarned' => $totalPointsEarned,
                'totalPriceBaru' => $totalPriceBaru,
                'RMRedeem' => $RMRedeem,
                'RMRedeem2' => $RMRedeem2,
                'pointsRedeem2' => $pointsRedeem2,
                'check' => $true

            ];

            // Return the response as JSON
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {

            $wrong="false";

            $response = [
                'TotalPointsEarned' => $totalPointsEarned,
                'check' => $wrong
            ];

            // Return the response as JSON
            header('Content-Type: application/json');
            echo json_encode($response);
        } 

    } else {
        // No match found for the phone number
        echo "false";
        exit;
    }
    
}

?>
