<?php /*
session_start();
//error_reporting(0);
include('../includes/connect.php');
include('../functions/functions.php');
if (!isset($_SESSION['User'])) {
  header('location:../login.php');
} else {

// Check if the cart is already initiated in the session or create a new cart array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if (isset($_GET['id'])) {
    $item_id = $_GET['id'];

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
        // Item not found, retrieve it from the database
        $stmt = $conn->prepare("SELECT id, name, price FROM your_table WHERE id = ?");
        $stmt->bind_param("i", $itemId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $itemToAdd = [
                'id' => $row['id'],
                'name' => $row['name'],
                'price' => $row['price'],
                'quantity' => 1
            ];
            $_SESSION['cart'][] = $itemToAdd;
        } else {
            echo "Item not found";
            exit; // Stop further execution
        }

        $stmt->close();
    }

        echo "success";
    } else {
        echo "Invalid request";
    }
}




session_start();

$userID = $_SESSION['UserID'];

// Include your database connection code here
include('../includes/connect.php');

// Check if the cart is already initiated in the session or create a new cart array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if (isset($_POST['id'])) {
    $item_id = $_POST['id'];

    // Check if the item is already in the cart
    $existingItemKey = -1;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $item_id) {
            $existingItemKey = $key;
            break;
        }
    }

    if ($existingItemKey !== -1) {
        // Item exists in cart, increase quantity
        $_SESSION['cart'][$existingItemKey]['quantity'] += 1;
    } else {
        // Item not found, retrieve it from the database
        $stmt = $conn->prepare("SELECT id, image, name, price FROM menu WHERE id = ?");
        $stmt->bind_param("i", $item_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $itemToAdd = [
                'id' => $row['id'],
                'image' => $row['image'],
                'name' => $row['name'],
                'price' => $row['price'],
                'quantity' => 1
            ];
            $_SESSION['cart'][] = $itemToAdd;
        } else {
            echo "Item not found";
            exit; // Stop further execution
        }

        $stmt->close();
    }

    echo "success";
} else {
    echo "Invalid request";
}

// Close the database connection
$conn->close(); */


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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {

    $MenuID = $_POST['id'];

    // Query to find the MenuID
    $menuQuery = mysqli_query($conn, "SELECT MenuID FROM menu WHERE MenuID = '$MenuID' AND KioskID = '$KioskID' AND Stock > 0 AND Availability = 'Available'");

    if (!$menuQuery) {
        echo "Error: " . mysqli_error($link);
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
                    'image' => $itemDetails['ItemImage'],
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
        header("Location: cart.php");
    } else {
        echo "<script>alert('Invalid Request'); window.location='cart.php';</script>";

    }
    
} else {
    echo "Invalid request";
}

?> 
