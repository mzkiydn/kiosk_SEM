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
        .menu-item {
            margin-bottom: 15px;
        }

        .menu-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
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
                      <div class="col-md-8 mb-3 mb-md-0">
                          <div class="card">
                              <h5 class="card-header">Order Details</h5>
                              <div class="card-body">
                                  <?php if (!empty($_SESSION['cart'])): ?>
                                
                                        <?php foreach ($_SESSION['cart'] as $index => $item):
                                                $totalPrice = 0;
                                                $itemTotalPrice = $item['price'] * $item['quantity'];
                                                $totalPrice += $itemTotalPrice; ?>
                                        <table class="table">
                                            <tr>
                                                <td rowspan='2' style="width:30%;"><img src="data:image;base64,<?= $item['image'] ?>" class="img-thumbnail menu-image" style="object-fit: cover; height: 80%; width: 80%;" alt="Menu Item Image"></td>
                                                <td class="no-border"><?= $item['name'] ?></td>
                                                <td class="item-price">RM <?= number_format($item['price'], 2) ?></td>
                                                <td>Quantity : <?=$item['quantity']?></td>

                                            </tr>
                                        </table>
                                        <?php endforeach; ?>
                                  <?php else: ?>
                                      <p>Your cart is empty</p>
                                  <?php endif; ?>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="card">
                              <h5 class="card-header">Checkout Information</h5>
                              <div class="card-body">
                                  <div class="d-flex justify-content-between mb-3">
                                      <p class="m-0"><strong>Subtotal:</strong></p>
                                      <p class="m-0 text-end"><strong><?= number_format($totalPrice, 2) ?></strong></p>
                                  </div>
                                  <!-- Add payment method form or information here -->
                                  <form action="onlineOrder.php" method="POST">
                                      <p class="m-0"><strong>Points Earned:</strong></p>
                                      <p class="m-0 text-end"><strong><?= $index + 1 ?></strong></p>
                                      
                                      <div class="mb-3">
                                          <button type="button" class="btn btn-link px-0 text-decoration-none" data-bs-toggle="modal" data-bs-target="#redeemPointsModal">
                                              <strong>Redeem Points</strong>
                                          </button>
                                      </div>
                                      <p class="m-0"><strong>Payment Method:</strong></p>
                                      <select name="payment_method" class="form-select mb-3">
                                          <option value="Cash">Cash</option>
                                          <option value="Kiosk Membership Card">Kiosk Membership Card</option>
                                          <!-- Add more options as needed -->
                                      </select>
                                      <div class="d-flex justify-content-between mb-3">
                                          <p class="m-0"><strong>Total Amount:</strong></p>
                                          <p class="m-0 text-end"><strong>RM <?= number_format($totalPrice, 2 )?></strong></p>
                                      </div>
                                      
                                      
                                  </form>
                                  <form action="onlineOrder.php" method='post'>
                                      <div class="d-grid">
                                          <button type="submit" class="btn btn-success mb-2">Checkout</button>
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
  <!-- Redeem Points Modal -->
  <div class="modal fade" id="redeemPointsModal" tabindex="-1" role="dialog" aria-labelledby="redeemPointsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="redeemPointsModalLabel">Redeem Points</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Add the content of your redeem points modal here -->
                    <!-- Example: Input field for entering points to redeem -->
                    <label for="redeemPoints">Enter Points to Redeem:</label>
                    <input type="text" class="form-control" id="redeemPoints" name="redeemPoints">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- Add any additional buttons or actions here -->
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
<?php } ?>