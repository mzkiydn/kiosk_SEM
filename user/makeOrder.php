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
    <title>Make Order</title>
    <?php include('../includes/headsettings.php'); ?>
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

    <style>


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
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <!-- Content -->

                    <?php
                    // Check if kiosk_id parameter is set in the URL
                    if (isset($_GET['id'])) {
                        $_SESSION['KioskID'] = $_GET['id'];
                        $kiosk_id = $_SESSION['KioskID'];

                        // Query to retrieve menu items for the specified kiosk_id
                        $sql = "SELECT * FROM menu WHERE KioskID = $kiosk_id AND Stock > 0 AND Availability = 'Available'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                              echo "<div class='col-md-3'>";
                              echo "<div class='card m-0'>";

                              // Set a fixed height and width for the container
                              echo "<div style='height: 200px; width: 100%; overflow: hidden;'>";
                              // Set object-fit: cover for the image
                              echo '<img src="data:image;base64,' . $row['ItemImage'] . '" class="img-thumbnail menu-image" style="object-fit: cover; height: 100%; width: 100%;" alt="Menu Item Image">';
                              echo "</div>";

                              echo "<div class='card-body'>";
                              echo "<h5 class='mb-2'>" . $row["ItemName"] . "</h5>";
                              echo "<div class='mb-1'><p>" . $row["ItemDesc"] . "</p></div>";
                              echo "<h6 ><strong>Price:</strong> RM" . $row["ItemPrice"] . "</h6>";
                              echo "<div class='text-center'>";
                              echo "<button class='add_cart btn btn-primary' data-id='{$row["MenuID"]}'>Add to Cart</button>";
                              echo "</div>";
                              echo "</div>";
                              echo "</div>";
                              echo "</div>";
                            }
                        } else {
                          echo "<div class='col-md-3'>None.</div>";
                        }
                    }

                    $conn->close();
                    ?>

                    <!-- / Content -->
                </div>
            </div>


            
          </div>
          
        </div>
      </div>
    </div>

    <!-- / Layout wrapper -->

    <script src="../assets/js/dashboards-analytics.js"></script>
    <script>
        $(document).ready(function() {
            $('.add_cart').click(function() {
                var MenuID = $(this).data('id');

                $.ajax({
                    url: 'addToCart.php',
                    method: 'POST',
                    data: { id: MenuID },
                    success: function(response) {
                        alert('Item added to cart!');
                        return;
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        alert('Failed to add item to cart.');
                        return;
                    }
                });
            });
        });
    </script>
  </body>

  </html>

  <!-- QR Library -->
  <?php 
  require_once '../assets/vendor/phpqrcode/qrlib.php'; 
  ?>
<?php } ?>