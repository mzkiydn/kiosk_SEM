<?php
session_start();
//error_reporting(0);
include('../includes/connect.php');

// Get the values
$KioskID = $_POST['KioskID'];
$UserID = $_POST['UserID'];
$dapatredeem = $_POST['dapatredeem'];
$InPurchaseSubtotal = $_POST['InPurchaseSubtotal'];
$InPurchaseTotalPrice = $_POST['InPurchaseTotalPrice'];
$TotalPointsEarned = $_POST['TotalPointsEarned'];
$TotalPointsRedeemed = $_POST['TotalPointsRedeemed'];
$TotalPointsCollect = $_POST['TotalPointsCollect'];
$TotalPointsCollectedRedeem = $_POST['TotalPointsCollectedRedeem'];
$PaymentType = $_POST['PaymentType'];

//Handle cart data
$cartItems = $_SESSION['cart'];

if($UserID == 0){

    $query = "INSERT INTO inpurchaseorder (UserID, KioskID, InPurchaseDate, InPurchaseTime, InPurchaseSubTotal, InPurchaseTotalPrice, TotalPointsEarned, TotalPointsRedeemed)
              VALUES ( null, '$KioskID', CURDATE(), CURTIME(), '$InPurchaseSubtotal', '$InPurchaseSubtotal', 0, 0 )" ;
    $result = mysqli_query($conn, $query);

    if($result){

        $InPurchaseID = mysqli_insert_id($conn);

        foreach ($cartItems as $cartItem){
            
            $MenuID = $cartItem['id'];
            $ItemName = $cartItem['name'];
            $ItemsTotalAmount = $cartItem['price'] * $cartItem['quantity'];
            $Quantity = $cartItem['quantity'];
        
            $query2 = "INSERT INTO inpurchaselist (InPurchaseID, ItemName, Quantity, ItemsTotalAmount )
                       VALUES ('$InPurchaseID', '$ItemName', '$Quantity', '$ItemsTotalAmount') ";
            $result2 = mysqli_query($conn, $query2);
        
            // To update stock of items
            $getQuantity = "SELECT Stock FROM menu WHERE MenuID = '$MenuID'";
            $resultQuantity = mysqli_query($conn, $getQuantity);
        
            if ($resultQuantity) {
                $row = mysqli_fetch_assoc($resultQuantity);
                $currentStock = $row['Stock'];
            
                // Calculate the new stock quantity
                $newStock = $currentStock - $Quantity;
        
                $updateMenuQuantity ="UPDATE menu SET Stock = '$newStock' WHERE MenuID = '$MenuID'";
                $resultUpdate = mysqli_query($conn, $updateMenuQuantity);
            }
        }
        

        $query3 = "INSERT INTO payment (PaymentDate, PaymentTime, PaymentType, OrderID, InPurchaseID) 
                       VALUES (CURDATE(), CURTIME(), '$PaymentType', null, '$InPurchaseID')";
        $result3 = mysqli_query($conn, $query3);

    }

    unset($_SESSION['cart']);

    // Send a response back to JavaScript (if needed)
    $response = array('success' => true);
    echo json_encode($response);
    exit;

} else if($UserID > 0){
    
    if($dapatredeem === 'true'){

        $query = "INSERT INTO inpurchaseorder (UserID, KioskID, InPurchaseDate, InPurchaseTime, InPurchaseSubTotal, InPurchaseTotalPrice, TotalPointsEarned, TotalPointsRedeemed)
                  VALUES ( '$UserID', '$KioskID', CURDATE(), CURTIME(), '$InPurchaseSubtotal', '$InPurchaseTotalPrice', '$TotalPointsEarned', '$TotalPointsRedeemed' )" ;
        $result = mysqli_query($conn, $query);

        if($result){

            $InPurchaseID = mysqli_insert_id($conn);

            foreach ($cartItems as $cartItem){
            
                $MenuID = $cartItem['id'];
                $ItemName = $cartItem['name'];
                $ItemsTotalAmount = $cartItem['price'] * $cartItem['quantity'];
                $Quantity = $cartItem['quantity'];
            
                $query2 = "INSERT INTO inpurchaselist (InPurchaseID, ItemName, Quantity, ItemsTotalAmount )
                           VALUES ('$InPurchaseID', '$ItemName', '$Quantity', '$ItemsTotalAmount') ";
                $result2 = mysqli_query($conn, $query2);
            
                // To update stock of items
                $getQuantity = "SELECT Stock FROM menu WHERE MenuID = '$MenuID'";
                $resultQuantity = mysqli_query($conn, $getQuantity);
            
                if ($resultQuantity) {
                    $row = mysqli_fetch_assoc($resultQuantity);
                    $currentStock = $row['Stock'];
                
                    // Calculate the new stock quantity
                    $newStock = $currentStock - $Quantity;
            
                    $updateMenuQuantity ="UPDATE menu SET Stock = '$newStock' WHERE MenuID = '$MenuID'";
                    $resultUpdate = mysqli_query($conn, $updateMenuQuantity);
                }
            }

            $query3 = "INSERT INTO payment (PaymentDate, PaymentTime, PaymentType, OrderID, InPurchaseID) 
                       VALUES (CURDATE(), CURTIME(), '$PaymentType', null, '$InPurchaseID')";
            $result3 = mysqli_query($conn, $query3);

            $updateQuery ="UPDATE membership SET TotalPointsEarned = '$TotalPointsCollectedRedeem' WHERE UserID = '$UserID '";
            $updateQuery = mysqli_query($conn, $updateQuery);
        }

        unset($_SESSION['cart']);

        // Send a response back to JavaScript (if needed)
        $response = array('success' => true);
        echo json_encode($response);
        exit;

    }else{

        $query = "INSERT INTO inpurchaseorder (UserID, KioskID, InPurchaseDate, InPurchaseTime, InPurchaseSubTotal, InPurchaseTotalPrice, TotalPointsEarned, TotalPointsRedeemed)
                  VALUES ( '$UserID', '$KioskID', CURDATE(), CURTIME(), '$InPurchaseSubtotal', '$InPurchaseSubtotal', '$TotalPointsEarned', 0 )" ;
        $result = mysqli_query($conn, $query);

        if($result){

            $InPurchaseID = mysqli_insert_id($conn);

            foreach ($cartItems as $cartItem){
            
                $MenuID = $cartItem['id'];
                $ItemName = $cartItem['name'];
                $ItemsTotalAmount = $cartItem['price'] * $cartItem['quantity'];
                $Quantity = $cartItem['quantity'];
            
                $query2 = "INSERT INTO inpurchaselist (InPurchaseID, ItemName, Quantity, ItemsTotalAmount )
                           VALUES ('$InPurchaseID', '$ItemName', '$Quantity', '$ItemsTotalAmount') ";
                $result2 = mysqli_query($conn, $query2);
            
                // To update stock of items
                $getQuantity = "SELECT Stock FROM menu WHERE MenuID = '$MenuID'";
                $resultQuantity = mysqli_query($conn, $getQuantity);
            
                if ($resultQuantity) {
                    $row = mysqli_fetch_assoc($resultQuantity);
                    $currentStock = $row['Stock'];
                
                    // Calculate the new stock quantity
                    $newStock = $currentStock - $Quantity;
            
                    $updateMenuQuantity ="UPDATE menu SET Stock = '$newStock' WHERE MenuID = '$MenuID'";
                    $resultUpdate = mysqli_query($conn, $updateMenuQuantity);
                }
            }

            $query3 = "INSERT INTO payment (PaymentDate, PaymentTime, PaymentType, OrderID, InPurchaseID) 
                       VALUES (CURDATE(), CURTIME(), '$PaymentType', null, '$InPurchaseID')";
            $result3 = mysqli_query($conn, $query3);

            $updateQuery ="UPDATE membership SET TotalPointsEarned = '$TotalPointsCollect' WHERE UserID = '$UserID '";
            $updateQuery = mysqli_query($conn, $updateQuery);
        }

        unset($_SESSION['cart']);

        // Send a response back to JavaScript (if needed)
        $response = array('success' => true);
        echo json_encode($response);
        exit;

    }
} 

?>
    
    

