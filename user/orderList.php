<?php
session_start();
//error_reporting(0);
include('../includes/connect.php');
include('../functions/functions.php');
if (!isset($_SESSION['User'])) {
  header('location:../login.php');
} else {
?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <?php include('../includes/headsettings.php'); ?>
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <style>
        .content {
            margin: 20px;
        }

        .card-style {
            padding: 20px;
        }

        .btn-add-item {
            margin-right: 5px;
        }

        .total-price {
            text-align: right;
            margin-bottom: 20px;
        }

        .checkout-btn {
            width: 100%;
        }

        .quantity-input {
            text-align: center;
        }

        .td-width {
            width: 20%;
            
        }

        .td-remove {
            width: 10%;
            
        }
    </style>
  </head>
    <body>
    <div class="content-wrapper content">
    
        <!-- ACTIVE ORDER -->
        <div class="container-xxl flex-grow-1 container-p-y">
                
            <div class="mb-3">
                <h5>Past Order</h5>
            </div>

                <!-- PHP code to display cart items -->
                <?php

                // Check if the cart is initiated in the session
                if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                    echo "<div class='card card-style position-relative'>";
                    echo "<div class='table-responsive text-nowrap'>";
                    echo "<table class='table'>";
                    echo "<thead><tr>";
                    echo "<th scope='col'>#</th>";
                    echo "<th scope='col'>Item Name</th>";
                    echo "<th scope='col'>Total Price</th>";
                    echo "<th class='td-width' scope='col'>Quantity</th>";
                    echo "<th class='td-remove' scope='col'>Show QR Receipt</th>";
                    echo "</tr></thead>";
                } else {
                    echo "<div class='card card-style position-relative'>";
                    echo "<div class='table-responsive text-nowrap'>";
                    echo "<table class='table'>";
                    echo "<thead><tr>";
                    echo "<th scope='col'>#</th>";
                    echo "<th scope='col'>Item Name</th>";
                    echo "<th scope='col'>Total Price</th>";
                    echo "<th class='td-width' scope='col'>Quantity</th>";
                    echo "<th class='td-remove' scope='col'>Show QR Receipt</th>";
                    echo "</tr></thead>";
                    echo "<tbody>";

                    $totalPrice = 0; // Initializing total price
                    $itemNumber = 1;

                    //Display items in the cart
                    foreach ($_SESSION['cart'] as $itemId => $item) {
                        $itemTotalPrice = $item['price'] * $item['quantity'];
                        $totalPrice += $itemTotalPrice; // Accumulating total price considering quantity
                    
                        echo "<tr>";
                        echo "<th scope='row'>" . $itemNumber . "</th>";
                        echo "<td>" . $item['name'] . "</td>";
                        echo "<td class='item-price'>RM " . number_format($itemTotalPrice, 2) . "</td>";
                        echo "<td class='td-width'>";
                        echo "<input type='number' name='quantity' class='form-control quantity-input' value='{$item['quantity']}'>";
                        echo "</td>";
                        echo "<td>"; // Remove button column
                        echo "<form action='receipt.php' method='post'>";
                        echo "<input type='hidden' name='item_id' value='{$itemId}'>";
                        echo "<button type='submit' name='remove_item' class='btn btn-success mb-2'>Show</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    
                        $itemNumber++; // Increment item number for the next item
                    }
                    
                }
                ?>
                <!-- End of PHP code -->
  
        </div>
    </div>

    </body>
  </html>
  <?php } ?>