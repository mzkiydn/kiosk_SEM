<?php
session_start();
//error_reporting(0);
include('../includes/connect.php');
include('../functions/functions.php');

// Function to clear the cart
function clearCart() {
    unset($_SESSION['cart']);
}

// Check if the clear button is clicked
if (isset($_POST['clear'])) {
    clearCart();
    header("Location: InstoreCart.php"); // Redirect to refresh the page
    exit;
}

// Check if the add button is clicked
if (isset($_POST['add'])) {
    header("Location: scanQRItem.php"); // Redirect to refresh the page
    exit;
}

// Function to remove an item from the cart
function removeItem($itemId) {
    if (isset($_SESSION['cart'][$itemId])) {
        unset($_SESSION['cart'][$itemId]);
    }

    // Re-index the array after removing an item
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Check if the remove button is clicked
if (isset($_POST['remove_item'])) {
    $itemIdToRemove = $_POST['item_id'];
    removeItem($itemIdToRemove);
    header("Location: InstoreCart.php"); // Redirect to refresh the page
    exit;
}


if (!isset($_SESSION['User'])) {
  header('location:../login.php');
} else {

?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In-Store Purchase</title>
    <?php include('../includes/headsettings.php'); ?>
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <style>
        .content {
 
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
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <?php include('../includes/sidebar.php'); ?>
        <div class="layout-page">
          <?php include('../includes/header.php'); ?>

          <div class="content-wrapper content">
            <div class="container-xxl flex-grow-1 container-p-y">
                <!-- Buttons -->
                <div class="mb-3">
                    <form method='post'>
                        <button type='submit' name='add' class="btn btn-primary btn-add-item">Add Item</button>
                        <button type='submit' name='clear' class="btn btn-danger btn-clear-all">Clear All Items</button>
                    </form>
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
                        echo "<th scope='col'>Price</th>";
                        echo "<th class='td-width' scope='col'>Quantity</th>";
                        echo "<th class='td-remove' scope='col'>Action</th>";
                        echo "</tr></thead>";
                    } else {
                        echo "<div class='card card-style position-relative'>";
                        echo "<div class='table-responsive text-nowrap'>";
                        echo "<table class='table'>";
                        echo "<thead><tr>";
                        echo "<th scope='col'>#</th>";
                        echo "<th scope='col'>Item Name</th>";
                        echo "<th style='width:28%' scope='col'>Price</th>";
                        echo "<th class='td-width' scope='col'>Quantity</th>";
                        echo "<th class='td-remove' scope='col'>Action</th>";
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
                            echo "<form class='quantity-form' data-item-id='{$item['id']}' method='post'>";
                            echo "<input type='hidden' name='item_id' value='{$item['id']}'>";
                            echo "<div class='input-group'>";
                            echo "<button type='button' class='input-group-text quantity-button decrease'>-</button>";
                            echo "<input type='number' name='quantity' class='form-control quantity-input' value='{$item['quantity']}' readonly style='background-color: white;'>";
                            echo "<button type='button' class='input-group-text quantity-button increase'>+</button>";
                            echo "</div>";
                            echo "</form>";
                            echo "</td>";
                            echo "<td>"; // Remove button column
                            echo "<form method='post'>";
                            echo "<input type='hidden' name='item_id' value='{$itemId}'>";
                            echo "<button type='submit' name='remove_item' class='btn btn-danger'>Remove</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        
                            $itemNumber++; // Increment item number for the next item
                        }
                        

                        echo "</tbody></table></div>";
                        echo "<div class='total-price mt-4'>";
                        //Display total price
                        echo "<p><strong>Total Price: RM <span id='total-price'>" . number_format($totalPrice, 2) . "</span></strong><p>";
                        echo "</div>";
                        echo "<form action='checkout.php' method='GET'>";
                        echo "<button typr='submit' class='btn btn-success checkout-btn'>Checkout</button>";
                        echo "</form>";
                        echo "</div>"; // card div
                    }
                ?>
                <!-- End of PHP code -->

            </div>
          </div>

        </div>
      </div>
    </div>



    <!-- / Layout wrapper -->

    <script src="../assets/js/dashboards-analytics.js"></script>
    <script>
    //to handle quantity and price update with Javascript and AJAX    
    document.addEventListener('DOMContentLoaded', () => {
        const quantityForms = document.querySelectorAll('.quantity-form');

        quantityForms.forEach(form => {
            form.addEventListener('click', e => {
                const target = e.target;
                const form = target.closest('.quantity-form');
                const itemId = form.getAttribute('data-item-id');
                const input = form.querySelector('input[name="quantity"]');
                const currentValue = parseInt(input.value);

                if (target.classList.contains('decrease')) {
                    if (currentValue > 1) {
                        input.value = currentValue - 1;
                        updateQuantity(itemId, currentValue - 1);
                    }
                } else if (target.classList.contains('increase')) {
                    input.value = currentValue + 1;
                    updateQuantity(itemId, currentValue + 1);
                }
            });
        });

        function updateQuantity(itemId, newQuantity) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update-cart.php'); 
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const updatedCart = JSON.parse(xhr.responseText);
                    updatePrices(updatedCart); // Update displayed prices using the updated cart data
                } else {
                    console.error('Request failed');
                }
            };

            const params = `item_id=${itemId}&new_quantity=${newQuantity}`;
            xhr.send(params);
        }

        function updatePrices(updatedCart) {
            const items = updatedCart; // Use the updated cart data received from the server
            const itemPrices = document.querySelectorAll('.item-price');
            let total = 0;

            itemPrices.forEach((price, index) => {
                const itemTotalPrice = items[index].price * items[index].quantity;
                price.textContent = `RM ${itemTotalPrice.toFixed(2)}`;
                total += itemTotalPrice;
            });

            document.querySelector('p strong').textContent = `Total Price: RM ${total.toFixed(2)}`;
        }
    });

    </script>
  </body>

  </html>

  <!-- QR Library -->
  <?php 
  require_once '../assets/vendor/phpqrcode/qrlib.php'; 
  ?>
<?php } ?>