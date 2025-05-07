<?php
session_start();
//error_reporting(0);
include('../includes/connect.php');
require_once '../assets/vendor/phpqrcode/qrlib.php'; 
  

$items = $_SESSION['cart'];
$userid = $_SESSION['User'];
$kioskid = $_SESSION['KioskID'];
$totalPrice = 0;
$finalPrice = 0;

foreach ($items as $item){
    $menuid = $item['id'];
    $itemName = $item['name'];
    $orderPrice = $item['price'];
    $quantity = $item['quantity'];

    $totalPrice += ($orderPrice*$quantity);

}

$query = mysqli_query($conn, "INSERT INTO onlineorder (UserID, KioskID, OrderDate, OrderTime, OrderSubTotal, OrderTotalPrice, TotalPointsEarned, TotalPointsRedeemed, OrderStatus) VALUES ('$userid', '$kioskid', CURDATE() , CURTIME() , '$totalPrice','$totalPrice', 0, 0,'Pending')");

if($query){

    $orderidFK = mysqli_insert_id($conn);

    $_SESSION['ordered'] = $orderidFK ;

    // QR here
    $pathQR = '../assets/img/qr/';
    $qrCode = $pathQR . time() . ".png";
    QRcode :: png($orderidFK, $qrCode, 'H',4,4);
    $qrImage = base64_encode(file_get_contents(addslashes($qrCode)));

    $query2 = mysqli_query($conn, "UPDATE onlineorder SET OrderQR = '$qrImage' WHERE OrderID = '$orderidFK'");

    
    if($query2){

        foreach ($items as $item){

            $menuid = $item['id'];
            $itemName = $item['name'];
            $orderPrice = $item['price'];
            $quantity = $item['quantity'];
            $finalPrice = $orderPrice* $quantity;

            $query3 = "INSERT INTO orderlist (OrderID, MenuID, Quantity, OrderTotalAmount )
                        VALUES ('$orderidFK', '$menuid', '$quantity', '$finalPrice') ";
            $result3 = mysqli_query($conn, $query3);
            
        }

    }

    unset ($_SESSION['cart']);
    header("Location: receipt.php");
    exit();

}








?>