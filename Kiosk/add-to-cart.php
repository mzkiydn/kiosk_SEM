<?php
session_start();

$KioskID = $_SESSION['KioskID'];

include('../includes/connect.php');
// Check if the cart is already initiated in the session or create a new cart array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Retrieve menu items from the database table
$menuQuery = mysqli_query($conn, "SELECT * FROM menu WHERE KioskID = '$KioskID' AND Stock > 0 AND Availability = 'Available'");

if (!$menuQuery) {
    echo "Error: " . mysqli_error($conn);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['data'])) {

    $qrContent = $_POST['data'];

    // Query to find the MenuID based on scanned content
    $menuQuery = mysqli_query($conn, "SELECT MenuID FROM menu WHERE MenuID = '$qrContent' AND KioskID = '$KioskID' AND Stock > 0 AND Availability = 'Available'");

    if (!$menuQuery) {
        echo "Error: " . mysqli_error($conn);
        exit();
    }

    $row = mysqli_fetch_assoc($menuQuery);

    if ($row) {
        $itemId = $row['MenuID'];

        // Check if the item is already in the cart
        $existingItemKey = -1;
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $itemId) {
                $existingItemKey = $key;
                break;
            }
        }

        if ($existingItemKey !== -1) {
            // Item exists in cart, increase quantity
            $_SESSION['cart'][$existingItemKey]['quantity'] += 1;
        } else {
            // Retrieve item details based on MenuID
            $itemQuery = mysqli_query($conn, "SELECT * FROM menu WHERE MenuID = '$itemId' AND KioskID = '$KioskID'");

            if (!$itemQuery) {
                echo "Error: " . mysqli_error($conn);
                exit();
            }

            $itemDetails = mysqli_fetch_assoc($itemQuery);

            if ($itemDetails) {
                
                $itemToAdd = array(
                    'id' => $itemDetails['MenuID'],
                    'name' => $itemDetails['ItemName'],
                    'price' => $itemDetails['ItemPrice'],
                    'quantity' => 1
                );
                $_SESSION['cart'][] = $itemToAdd;
            } else {
                echo "Item not found";
                exit;
            }
        }

        echo "success";
        header("Location: InstoreCart.php");
    } else {
        echo "<script>alert('Menu is not available/found'); window.location='InstoreCart.php';</script>";
        // echo "MenuID not found for scanned content";
    }
    
} else {
    echo "Invalid request";
}

?>
