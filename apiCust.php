<?php
session_start();
include('includes/connect.php');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);


// Collected points for customer
if ($_GET['getPoints']) {

    $userID = $_POST['userID'];
    $totalPoints = 0;

    $sql = "SELECT TotalPointsEarned FROM membership WHERE UserID = $userID";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

        // Store the TotalPointsEarned in another variable
        $totalPoints = $row['TotalPointsEarned'];



    } else {
        // Handle the query error
        echo "Error: " . $conn->error;
    }

    echo json_encode([
        "totalPoints" => $totalPoints,
    ],JSON_NUMERIC_CHECK );
    die;


}


//Get Total Spend
if ($_GET['getSpend']) {

    $userID = $_POST['userID'];

    $sql = "SELECT SUM(OrderTotalPrice) SumTotalPrice, DATE_FORMAT(OrderDate, '%M') OrderMonth from onlineorder WHERE onlineorder.UserID= $userID GROUP BY MONTH(OrderDate)";
    $result = $conn->query($sql);
    $result = $result->fetch_all(MYSQLI_ASSOC);


    foreach ($result as $row) {
        $OrderTotal[] = $row['SumTotalPrice'];
        $OrderDate[] = $row['OrderMonth'];
        $totalSpend = array_sum($OrderTotal);
    }

    echo json_encode([
        "OrderTotal" => $OrderTotal,
        "OrderDate" => $OrderDate,
        "totalSpend" => "RM " . $totalSpend,
    ],JSON_NUMERIC_CHECK );
    die;
}

