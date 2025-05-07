<?php
    session_start();
    //error_reporting(0);
    include('../includes/connect.php');
    include('../functions/functions.php');


    $totalPrice = 0;

    // Calculating total price
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $itemTotalPrice = $item['price'] * $item['quantity'];
            $totalPrice += $itemTotalPrice;
        }

        $totalPrice = number_format($totalPrice, 2);
    }

    //Function to delete item
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
        header("Location: cart.php"); // Redirect to refresh the page
        exit;
    }

    // Function to clear all items
    function clearCart() {
        unset($_SESSION['cart']);
    }

    // Check if the clear button is clicked
    if (isset($_POST['clear'])) {
        clearCart();
        header("Location: cart.php"); // Redirect to refresh the page
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
    <title>Add to Cart</title>
    <?php include('../includes/headsettings.php'); ?>
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <style>


        .no-border {
            border: none !important;
        }
    </style>
  </head>

  <body>
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <?php include('../includes/sidebar.php'); ?>
      <div class="layout-page">
      <?php include('../includes/header.php'); ?>
        <div class="content-wrapper">
            <div class="content-wrapper content">
              <div class="container-xxl flex-grow-1 container-p-y">
                  <div class="row">
                    <!-- First Column - Larger Card -->
                    <div class="col-lg-8">
                        <div class="card">
                            <h5 class="card-header">Your Cart</h5>
                            <div class="card-body">
                            <?php if (!empty($_SESSION['cart'])): ?>
                                        <?php foreach ($_SESSION['cart'] as $index => $item): ?>   
                                            <table class="table">
                                                <tr>
                                                    <td rowspan='2' style="width:30%;"><img src="data:image;base64,<?= $item['image'] ?>" class="img-thumbnail menu-image" style="object-fit: cover; height: 80%; width: 80%;" alt="Menu Item Image"></td>
                                                    <td class="no-border"><?= $item['name'] ?></td>
                                                    <td style="width:30%;" class="no-border">
                                                        <form class='quantity-form' data-item-id='<?= $item['id'] ?>' method='post'>
                                                            <input type='hidden' name='item_id' value='<?= $item['id'] ?>'>
                                                            <div class='input-group'>
                                                                <button type='button' class='input-group-text quantity-button decrease'>-</button>
                                                                <input type='number' name='quantity' class='form-control quantity-input' value='<?= $item['quantity'] ?>' readonly style='background-color: white;'>
                                                                <button type='button' class='input-group-text quantity-button increase'>+</button>
                                                            </div>
                                                        </form>
                                                    </td>                         
                                                </tr>
                                                <tr>
                                                    <td class="item-price">RM <?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                                    <td ><button class="btn btn-danger remove_item">Remove</button></td>
                                                </tr>
                                                <tr>
                                                    
                                                </tr>
                                            </table>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <p>Your cart is empty</p>
                            <?php endif; ?>
                            </div>
                        </div>
                    </div>

                      <!-- Left side of page -->
                      <div class="col-md-4">
                          <div class="card">
                              <div class="card-body">
                                  <div class="d-flex justify-content-between mb-3">
                                      <p class="m-0"><b>Total Price:</b></p>
                                      <p class='m-0 text-end'><strong>RM <?= number_format($totalPrice, 2)?></strong></p>
                                  </div>
                                  <!-- Add payment method form or information here -->
                                  <form action="checkoutPage.php" method="POST">
                                    <p class="m-0"><strong>Points Earned:</strong></p>
                                    <div class="d-grid mt-4">
                                        <button type="submit" class="btn btn-success mb-2">Proceed to checkout</button>
                                        <button type="submit" formaction="displayKiosk.php" class="btn btn-danger mb-2">Browse more menu</button>
                                    </div>
                                  </form>
                              </div>
                          </div>
                      </div>

                  </div>
              </div>
            </div>
        </div>

      </div>

    </div>
    
  </div>


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
            xhr.open('POST', 'update_cart.php'); 
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

            document.querySelector('p strong').textContent = `RM ${total.toFixed(2)}`;
        }
    });

    </script>


</body>
</html> 
<?php } ?>