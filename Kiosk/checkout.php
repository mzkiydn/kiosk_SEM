<?php
session_start();
//error_reporting(0);
include('../includes/connect.php');
include('../functions/functions.php');

$totalPrice = 0;
$KioskID = $_SESSION['KioskID'];

// Calculating total price
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $itemTotalPrice = $item['price'] * $item['quantity'];
        $totalPrice += $itemTotalPrice;
    }

    $totalPrice = number_format($totalPrice, 2);
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
    <title>Checkout</title>
    <?php include('../includes/headsettings.php'); ?>
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Include jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
     <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>

        .textcolor {
            color: #57574C;
        }

        .textcolor2 {
            color: #7D7D6E;
        }

        .custom-border {
            border: 1px solid #ced4da; /* Define border properties */
            border-radius: 0.25rem; /* Add some border radius */
            padding: 10px; /* Add padding for spacing */
            margin-bottom: 10px; /* Add margin between form-check elements */
            cursor: pointer; /* Change cursor to pointer on hover */
            display: flex; /* Use flexbox for alignment */
            align-items: center; /* Align items in the center vertically */
            justify-content: space-between; /* Space between elements */
        }
        .form-check-input {
            margin-right: 10px; /* Adjust margin for the input to create space */
        }

        #useButton:disabled {
            background-color: grey;
            border-color: grey;
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
            <div class="container-xxl flex-grow-1 container-p-y ">
                <div class="row">
                    <div class="col-md-7 mb-3 mb-md-0">

                        <div class="card mb-3">
                            <h5 class="card-header">Payment Method</h5>
                            <div class="card-body" style="padding: 30px;">
                                <!-- First form-check -->
                                <div class="custom-border" onclick="setPaymentMethod('Cash'); document.getElementById('Cash').checked = true;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="Payment" id="Cash" checked>
                                        <label class="form-check-label" for="Cash">
                                            Cash
                                        </label>
                                    </div>
                                    <div>
                                        <i class="fa fa-money" style="font-size:24px;color: #7D7D6E"></i> <!-- Font Awesome cash icon -->
                                    </div>
                                </div>
                                <!-- Second form-check -->
                                <div class="custom-border" onclick="setPaymentMethod('Kiosk Membership Card'); document.getElementById('Kiosk Membership Card').checked = true;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="Payment" id="Kiosk Membership Card">
                                        <label class="form-check-label" for="Kiosk Membership Card">
                                            Kiosk Membership Card
                                        </label>
                                    </div>
                                    <div>
                                        <i class="fa fa-credit-card" style="font-size:24px;color: #7D7D6E"></i><!-- Font Awesome cash icon -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- collect/redeem -->
                        <div class="card">
                            <h5 class="card-header">Collect/Redeem Point</h5>
                            <div class="card-body" style="padding: 30px;">
                                    <p class="mb-1"><strong>Collect Points: </span></strong></p>
                                    <div class="input-group mb-3">
                                        <input  type="text" name="phonenumber" class="form-control" placeholder="Phone Number" aria-label="Phone number" aria-describedby="button-collect-points" >
                                        <button id="collectingpoints" type="button" class="btn btn-outline-secondary">Enter</button>
                                    </div>
                                    <div class="mb-3">
                                        <button id="redeemButton" type="button" class="btn btn-link px-0 text-decoration-none" data-bs-toggle="modal" data-bs-target="#redeemPointsModal" disabled>
                                            <strong>Redeem Points</strong>
                                        </button>
                                    </div>                                                               
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="card">
                            <h5 class="card-header">Checkout Information</h5> 
                            <div class="card-body" style="padding: 30px">
                                <?php if (!empty($_SESSION['cart'])): ?>
                                        <?php foreach ($_SESSION['cart'] as $index => $item): ?>   
                                            <div class="d-flex justify-content-between mb-2">       
                                                <p class="m-0 textcolor"><?= $item['quantity'] ?> x <?= $item['name'] ?></p>
                                                <p class="m-0 text-end textcolor">RM <?= number_format($item['price'] * $item['quantity'], 2) ?></p>
                                            </div>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <p>Your cart is empty</p>
                                <?php endif; ?>
                                <div style="margin:30px;"></div>
                                <hr>
                                <div style="margin:30px;"></div>
                                <div class="d-flex justify-content-between mb-2">
                                        <p class="m-0 textcolor2">Subtotal:</p>
                                        <p class="m-0 text-end textcolor2">RM <?= $totalPrice ?></p>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <p id="pointsRedeemSection" class="m-0 textcolor2" style="display: none;">Redeemed amount:</p>
                                    <p id="pointsRedeemValue" class="m-0 text-end textcolor2"></p>
                                </div>
                                <div  class="d-flex justify-content-between mb-3" >
                                    <p id="pointsCollectedSection" class="m-0 textcolor2" style="display: none;">Points collected:</p> 
                                    <p id="pointsCollectedValue" class="m-0 text-end textcolor2"></p>
                                </div>
                                <div style="margin:30px;"></div>
                                <div class="d-flex justify-content-between mb-3">
                                    <h4 class="m-0"><strong>Total Amount:</strong></h4>
                                    <h4 id="HargaTotalTolakTambah" class="m-0 text-end"><strong>RM <?= $totalPrice ?></strong></h4>
                                </div>                              
                                    <div class="d-grid">
                                        <button type="submit" id="confirmOrderBtn" class="btn btn-success mb-2">Confirm Order</button>
                                        <button type="button" id="cancelOrderBtn" class="btn btn-danger">Cancel Order</button>
                                    </div>
                            </div>
                        </div>
                    </div>

                    <div id="output"></div>
                </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="redeemPointsModal">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Redeem Points</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body d-flex flex-column">
                    <p id="pointsInfo">Total Points:</p>
                    <p id="redeemablePointsInfo">Redeemable Points:</p>
                    <button id="useButton" class="btn btn-primary" data-bs-dismiss="modal" disabled>Use</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <script>

            var KioskID =  <?= json_encode($KioskID); ?>;
            var userID = 0;
            var TotalPointsEarned = 0;
            var points = 0;
    
            var totalPriceInitial = <?= json_encode($totalPrice); ?>;
            var totalPriceBaru = 0;
            var RMRedeem = 0;
            var RMRedeem2 = 0;
            var dapatredeem = "false";

            var pointsRedeem2 = 0;
            var selectedPaymentMethod = 'Cash';

            function setPaymentMethod(method) {
                selectedPaymentMethod = method;
            }

        $(document).ready(function() {     

            // Event listener for the "Enter" button
            $('#collectingpoints').click(function(e) {
                e.preventDefault(); // Prevent default form submission

                // Fetch entered phone number value
                var phoneNumber = $('input[name="phonenumber"]').val();

                // Check if the input field is empty
                if (phoneNumber.trim() === '') {
                    // If input is empty, do not proceed
                    alert('Please enter a phone number');
                    $('#redeemButton').prop('disabled', true); // Enable Redeem Points button 
                    $('#pointsCollectedValue').text('');
                    $('#pointsCollectedSection').hide();
                    $('#pointsRedeemValue').text('');
                    $('#pointsRedeemSection').hide(); 
                    $('#HargaTotalTolakTambah').html('<strong>RM ' + totalPriceInitial + '</strong>');
                    
                    userID = 0;
                    points = 0;
                    
                    return; // Exit the function
                }
                                
                // AJAX request to check userID
                $.ajax({
                    url: 'checkprocess.php',
                    method: 'POST',
                    data: { phoneNumber: phoneNumber },
                    success: function(response) {

                        if (response.salah === 'salah') {

                            alert('Account not found for membership');  
                            $('#redeemButton').prop('disabled', true);
                            $('#pointsCollectedValue').text('');
                            $('#pointsCollectedSection').hide(); 
                            $('#pointsRedeemValue').text('');
                            $('#pointsRedeemSection').hide();   
                            $('#HargaTotalTolakTambah').html('<strong>RM ' + totalPriceInitial + '</strong>');

                            userID = 0;
                            points = 0;

                        } else {
                            // Process the response from the server
                            userID = response.userID;
                            points = response.points;
                            points = Math.floor(points);
                            TotalPointsEarned = response.collectedpoints;
                            
                            $('#redeemButton').prop('disabled', false);
                            $('#pointsCollectedValue').text(points);
                            $('#pointsCollectedSection').show(); // Show the points collected section
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error); // Log any errors
                    }
                });
                 
                
            });
            
            // Event listener for "Redeem Points" button
            $('#redeemPointsModal').on('show.bs.modal', function (event) {
                // Make AJAX request to fetch TotalPointsEarned based on UserID
                $.ajax({
                url: 'fetch-points.php', // Replace with your server-side script
                method: 'GET',
                data: { userID: userID },
                success: function(response) {

                    if (response.check === 'false'){

                        $('#pointsInfo').html('Total Points: ' + TotalPointsEarned);
                        $('#redeemablePointsInfo').html('Insufficient Balance');
                        $('#useButton').prop('disabled', true);


                    } else{

                        $('#useButton').prop('disabled', false);

                        totalPriceBaru = response.totalPriceBaru;
                        totalPriceBaru = parseFloat(totalPriceBaru).toFixed(2);

                        RMRedeem = response.RMRedeem ;
                        RMRedeem = parseFloat(RMRedeem).toFixed(2);

                        RMRedeem2 = response.RMRedeem2;
                        RMRedeem2 = parseFloat(RMRedeem2).toFixed(2);

                        pointsRedeem2 = response.pointsRedeem2;

                        // Update modal content with calculated values
                        $('#pointsInfo').html('Your Total Points: ' + TotalPointsEarned);
                        $('#redeemablePointsInfo').html('Redeemable Amount: RM ' + RMRedeem2 + ' (' + pointsRedeem2 + ')');

                        // Use button event listener
                        $('#useButton').off().click(function() {  

                            if (dapatredeem === "false") {

                                $('#pointsRedeemValue').text('- RM ' + RMRedeem2);
                                $('#pointsRedeemSection').show();

                                // Update the total amount in the main checkout area
                                $('#HargaTotalTolakTambah').html('<strong>RM ' + totalPriceBaru + '</strong>');
                                points = totalPriceBaru / 0.2;
                                points = Math.floor(points);
                                $('#pointsCollectedValue').text(points);
                                $(this).text('Remove').removeClass('btn-primary').addClass('btn-danger');

                                dapatredeem= "true";
                                
                            } else if (dapatredeem === "true") {

                                $(this).text('Use').removeClass('btn-danger').addClass('btn-primary');

                                $('#pointsRedeemValue').text('');
                                $('#pointsRedeemSection').hide();   
                                $('#HargaTotalTolakTambah').html('<strong>RM ' + totalPriceInitial + '</strong>');

                                points = totalPriceInitial / 0.2;
                                points = Math.floor(points);
                                $('#pointsCollectedValue').text(points);

                                dapatredeem= "false";
                                                                                              
                            }
    
                        });

                    }
                    
                },
                error: function(xhr, status, error) {
                    console.error(error); // Log any errors
                }
                });

            });

            $('#confirmOrderBtn').click(function(e){

                var TotalPointsCollected = parseInt(TotalPointsEarned, 10) + parseInt(points, 10);
                var TotalPointsCollectedRedeem = parseInt(TotalPointsEarned, 10) - parseInt(pointsRedeem2, 10);

                // console.log("Checkoutnya");
                // console.log("Kiosk ID:", KioskID);
                // console.log("price value:", totalPriceInitial);
                // console.log("User ID:", userID);
                // console.log("Point baru dlm db: ", TotalPointsCollected)
                // console.log("Point baru dlm db bila redeem: ", TotalPointsCollectedRedeem)
                // console.log("points value:", points);
                // console.log("dapatredeem", dapatredeem);
                // console.log("redeem points:", pointsRedeem2);
                // console.log("price value after redeem:", totalPriceBaru);
                // console.log("payment method:", selectedPaymentMethod); 
                
                // send to PHP for storage
                $.ajax({
                    url: 'store_values.php',
                    method: 'POST',
                    data: {
                        KioskID: KioskID,
                        UserID: userID,
                        dapatredeem: dapatredeem,
                        InPurchaseSubtotal: totalPriceInitial,
                        InPurchaseTotalPrice: totalPriceBaru,
                        TotalPointsEarned: points,
                        TotalPointsRedeemed: pointsRedeem2,
                        TotalPointsCollect: TotalPointsCollected,
                        TotalPointsCollectedRedeem: TotalPointsCollectedRedeem,
                        PaymentType: selectedPaymentMethod
                    },
                    success: function(response) {
                        console.log('Success response:', response); // Log success response
                        alert('Checkout succesfull!');
                        window.location.href = 'InstoreCart.php';
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error); // Log any errors
                        console.log('XHR object:', xhr); // Log XHR object for more details
                        alert('Error occurred. Check console for details.'); // Display alert to user
                    }
                });
                
                
            });

            $('#cancelOrderBtn').click(function(e) {

                var confirmation = window.confirm("Are you sure you want to proceed?");

                if (confirmation) {
                    // User clicked 'OK' (confirmed)
                    console.log("User confirmed.");
                    window.location.href = 'InstoreCart.php';
                } else {
                    // User clicked 'Cancel'
                    console.log("User canceled.");
                    return;
                }
                
            });

        });

    </script>  
    
    <!-- / Layout wrapper -->

    <script src="../assets/js/dashboards-analytics.js"></script>
  </body>
  </html>

  <!-- QR Library -->
  <?php 
  require_once '../assets/vendor/phpqrcode/qrlib.php'; 
  ?>
<?php } ?>

