<?php
session_start();
//error_reporting(0);
include('../includes/connect.php');
include('../functions/functions.php');

if (!isset($_SESSION['User'])) {
  header('location:../login.php');
} else {

    $KioskID = $_SESSION['KioskID'];
?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View In-Store Purchase Invoice</title>
    <?php include('../includes/headsettings.php'); ?>
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

    <style>
        .card-style {
            padding: 20px;
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
                    <!-- Content -->
                    
                    <div class="row">
                        <div class="col">
                            <h4>In-Store Purchase Invoice</h4>
                        </div>
                        <div class="col-auto">
                            <form class="form-inline">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="searchInput" placeholder="Search">
                                    <button id="searchButton" type="button" class="btn btn-primary">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php

                        $sqlTotalRows = "SELECT COUNT(*) AS totalRows FROM inpurchaseorder io
                        LEFT JOIN payment p ON io.InPurchaseID = p.InPurchaseID WHERE io.KioskID = $KioskID";
                        $resultTotalRows = $conn->query($sqlTotalRows);
                        $rowTotalRows = $resultTotalRows->fetch_assoc();
                        $totalRows = $rowTotalRows['totalRows'];

                        // Define the number of records per page
                        $recordsPerPage = 30;

                        // Get the current page number from the URL, default to 1 if not set
                        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

                        // Calculate the offset for the SQL query
                        $offset = ($page - 1) * $recordsPerPage;

                        // Fetch data from the database with LIMIT and OFFSET
                        $sql = "SELECT io.InPurchaseID, io.UserID, DATE_FORMAT(io.InPurchaseDate, '%d/%m/%Y') AS InPurchaseDate, io.InPurchaseTime, io.InPurchaseSubtotal, io.InPurchaseTotalPrice, p.PaymentType
                                FROM inpurchaseorder io
                                LEFT JOIN payment p ON io.InPurchaseID = p.InPurchaseID
                                WHERE io.KioskID = $KioskID
                                ORDER BY io.InPurchaseID DESC
                                LIMIT $offset, $recordsPerPage";

                        $result = $conn->query($sql);

                        // Calculate the total number of pages
                        $totalPages = ceil($totalRows / $recordsPerPage);

                        // Close the database connection
                        $conn->close();
                    ?>
                    
                    <div class='card card-style position-relative mt-3'>
                        <div class='table-responsive text-nowrap'>
                            <table id="invoiceTable" class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User ID</th>
                                        <th>Purchase Date</th>
                                        <th>Subtotal</th>
                                        <th>Total Price</th>
                                        <th>Payment Method</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>{$row['InPurchaseID']}</td>
                                                    <td>{$row['UserID']}</td>
                                                    <td>{$row['InPurchaseDate']}</td>
                                                    <td>RM {$row['InPurchaseSubtotal']}</td>
                                                    <td>RM {$row['InPurchaseTotalPrice']}</td>
                                                    <td>{$row['PaymentType']}</td>
                                                    <td>
                                                        <button id='viewDetails' type='button' class='btn btn-link px-0 text-decoration-none detailsBtn' data-bs-toggle='modal' data-bs-target='#viewDetailsModal' data-id='{$row['InPurchaseID']}'>
                                                            Details
                                                        </button>
                                                    </td>
                                                </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='7'>No data available</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                            
                        </div>
                    </div>

                        <!-- Pagination -->
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end mt-3">
                                <?php
                                for ($i = 1; $i <= $totalPages; $i++) {
                                    echo "<li class='page-item " . ($page == $i ? 'active' : '') . "'><a class='page-link' href='?page=$i'>$i</a></li>";
                                }
                                ?>
                            </ul>
                        </nav> 
                    
                    <!-- / Content -->
                </div>
            </div>  
        </div>
      </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title"><strong>Order Details</strong></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body d-flex flex-column">
                    <div>       
                        <p id="orderDate" class="m-0 textcolor">Order date: 14/1/2024</p>
                        <p id="orderTime" class="m-0 textcolor">Order time: 8:02</p>
                    </div>
                    <hr>
                    <div id="itemDetailsContainer">
                        <p style="margin: 0;">1 x Nasi Lemak</p>
                        <p style="margin: 0; text-align: right;">RM 4.50</p>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <p class="m-0 textcolor2">Subtotal:</p>
                        <p id="subtotal" class="m-0 text-end textcolor2">RM 4.50</p>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <p class="m-0 textcolor2">Redeemed amount:</p>
                        <p id="redeemedAmount" class="m-0 text-end textcolor2">RM 0</p>
                    </div>
                    <div  class="d-flex justify-content-between mb-3" >
                        <p class="m-0 textcolor2">Points collected:</p> 
                        <p id="pointsCollected" class="m-0 text-end textcolor2">RM 0</p>
                    </div>
                    <div style="margin:5px;"></div>
                    <div class="d-flex justify-content-between mb-3">
                        <h4 class="m-0"><strong>Total Amount:</strong></h4>
                        <h4 id="totalAmount" class="m-0 text-end"><strong>RM 4.50</strong></h4>
                    </div>
                    <hr>
                    <div  class="d-flex justify-content-between mb-3" >
                        <h5 class="m-0 textcolor2">Paid with:</h5> 
                        <h5 id="paymentMethod" class="m-0 text-end textcolor2">Cash</h5>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- / Layout wrapper -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>

    <script src="../assets/js/dashboards-analytics.js"></script>
  </body>

  </html>

    <script>
        

    $(document).ready(function () {
        // Use only jQuery to select details buttons
        var detailsButtons = $('.detailsBtn');

        detailsButtons.on('click', function (event) {
            var button = $(this); // Use jQuery to wrap the button
            var id = button.data('id'); // Use jQuery to get data attribute
            console.log('Data received:', id);


            fetch('get_order_details.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    console.log('Data received kah?:', id);

                    var nilairedeem = data.TotalPointsRedeemed;
                    nilairedeem = nilairedeem/100;
                    nilairedeem = parseFloat(nilairedeem).toFixed(2);


                    // Update modal content with details
                    $('#orderDate').text('Order Date: ' + data.InPurchaseDate);
                    $('#orderTime').text('Order Time: ' + data.InPurchaseTime);
                    $('#subtotal').text('RM ' + data.InPurchaseSubtotal);
                    $('#totalAmount').css('font-weight', 'bold').text('RM ' + data.InPurchaseTotalPrice);
                    $('#redeemedAmount').text('- RM ' + nilairedeem);
                    $('#pointsCollected').text(data.TotalPointsEarned);
                    $('#paymentMethod').text(data.PaymentType);

                     console.log(data.ItemDetails);

        
                    // Clear existing item details
                    var itemDetailsContainer = $('#itemDetailsContainer');
                    itemDetailsContainer.empty();

                    var orderdeets = data.ItemDetails;

                    orderdeets.forEach(item => {
                        itemDetailsContainer.append(`
                            <div style="display: flex; justify-content: space-between; margin-bottom: 2px;">       
                                <p style="margin: 0;">${item.Quantity} x ${item.ItemName}</p>
                                <p style="margin: 0; text-align: right;">RM ${item.Price}</p>
                            </div>
                        `);
                    });
                })
                
                
                .catch(error => console.error('Error:', error));
        });


        // Search functionality
        $('#searchButton').on('click', function () {
            var searchText = $('#searchInput').val().toLowerCase();

            // Filter table rows based on the search input
            $('#invoiceTable tbody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1);
            });
        });

        
    });
</script>


  <!-- QR Library -->
  <?php 
  require_once '../assets/vendor/phpqrcode/qrlib.php'; 
  ?>
<?php } ?>