<?php
session_start();
//error_reporting(0);
include('../includes/connect.php');
include('../functions/functions.php');
$KioskID = $_SESSION['KioskID'];
$userid = $_SESSION['User'];
$orderidFK= $_SESSION['ordered'];

if (!isset($_SESSION['User'])) {
  header('location:../login.php');
} else {

?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Page</title>
    <?php include('../includes/headsettings.php'); 
    ?>
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
                              <h5 class="card-header">Your Receipt</h5>
                              <div class="card-body">
                              <?php

                                $sql = "SELECT 
                                            o.OrderTotalPrice,
                                            ol.Quantity,
                                            ol.OrderTotalAmount,
                                            m.ItemName,
                                            m.ItemImage
                                        FROM onlineorder o
                                        JOIN orderlist ol ON o.OrderID = ol.OrderID
                                        JOIN menu m ON ol.MenuID = m.MenuID
                                        WHERE o.OrderID = $orderidFK";

                                $result = $conn->query($sql);

                                if ($result) {
                                    // Check if there are rows in the result set
                                    if ($result->num_rows > 0) {
                                        // Fetch the data from the result set
                                        while ($row = $result->fetch_assoc()) {
                                            $orderTotalPrice = $row['OrderTotalPrice'];
                                            $quantity = $row['Quantity'];
                                            $orderTotalAmount = $row['OrderTotalAmount'];
                                            $itemName = $row['ItemName'];
                                            $itemImage = $row['ItemImage'];

                                            // Output the HTML table with fetched data
                                            echo "<table class='table'>
                                                    <tr>
                                                        <td rowspan='2' style='width:30%;'><img src='data:image;base64,{$itemImage}' class='img-thumbnail menu-image' style='object-fit: cover; height: 80%; width: 80%;' alt='Menu Item Image'></td>
                                                        <td class='no-border'>{$itemName}</td>
                                                        <td class='item-price'>RM {$orderTotalAmount}</td>
                                                        <td>Quantity: {$quantity}</td>
                                                    </tr>
                                                </table>";
                                        }
                                    } else {
                                        echo "No results found.";
                                    }
                                } else {
                                    // Handle the query error
                                    echo "Error: " . $conn->error;
                                }

                                // Close the database connection when done
                                ?>
                              </div>
                          </div>
                      </div>

                      <!-- Left side of page -->
                      <div class="col-md-4">
                          <div class="card">
                              <h5 class="card-header"></h5>
                              <div class="card-body">
                              <div class="d-flex justify-content-between mb-3">
                                <p class="m-0"><strong>Total Payment:</strong></p>
                                <p class="m-0 text-end"><strong>
                                    <?php
                                    // Assuming $order contains the OrderID you're interested in

                                    // SQL query to retrieve OrderTotalPrice for a specific OrderID
                                    $sql = "SELECT OrderTotalPrice FROM onlineorder WHERE OrderID = $orderidFK";
                                    $result = $conn->query($sql);

                                    if ($result) {
                                        $row = $result->fetch_assoc();

                                        if ($row) {
                                            $orderTotalPrice = $row['OrderTotalPrice'];
                                            echo $orderTotalPrice;
                                        } else {
                                            echo "N/A";
                                        }
                                    } else {
                                        echo "Error: " . $conn->error;
                                    }
                                    ?>
                                </strong></p>
                            </div>

                            <?php

                            $sql = "SELECT OrderQR FROM onlineorder WHERE OrderID = $orderidFK";
                            $result = $conn->query($sql);

                            if ($result) {
                                $row = $result->fetch_assoc();

                                if ($row) {
                                    $orderQR = $row['OrderQR'];

                                    // Display the QR Code image
                                    echo "<div class='d-flex justify-content-between mb-3'>";
                                    echo "<p class='m-0'><strong>QR Order:</strong></p>";
                                    echo "<img src='data:image;base64, " . $orderQR . "' class='img-thumbnail menu-image' style='object-fit: cover; height: 80%; width: 80%;' alt='Menu Item Image'>";
                                    echo "</div>";
                                } else {
                                    echo "<p class='m-0 text-danger'>No QR Code found for OrderID: $orderidFK</p>";
                                }
                            } else {
                                // Handle the query error
                                echo "Error: " . $conn->error;
                            }
                            ?>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success mb-2" disabled>Order Received</button>
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
    
  </div>
  
    
</body>
</html>



<?php } ?>