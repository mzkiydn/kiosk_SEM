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
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Display Kiosk</title>
         <?php include('../includes/headsettings.php'); ?>
    </head>

  <body>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <?php include('../includes/sidebar.php'); ?>
        <div class="layout-page">
          <?php include('../includes/header.php'); ?>
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
            <?php 
                $sql = "SELECT * FROM kiosk";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Displaying kiosk data in a Bootstrap grid
                    echo "<div class='row'>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='col-md-4'>";
                        echo "<div class='card mb-3'>";

                        // Set a fixed height and width for the container
                        echo "<div style='height: 150px; width: 100%; overflow: hidden;'>";
                        // Set object-fit: cover for the image
                        echo '<img src="data:image;base64,' . $row["KioskLogo"] . '" class="img-thumbnail menu-image" style="height: 100%; width: 100%;" alt="Menu Item Image">';
                        echo "</div>";

                        echo "<div class='card-body'>";
                        echo "<h5 class='card-title mt-2'>" . $row["KioskName"] . "</h5>";

                        // Display the button text based on OperationStatus
                        if ($row["OperationStatus"] == "Open") {
                          echo "<a href='makeOrder.php?id=" . $row["KioskID"] . "' class='btn btn-primary'>Open</a>";
                        } else {
                            echo "<a href='#' class='btn btn-secondary disabled'>Close</a>";
                        }

                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                } else {
                    echo "0 results";
                }

                // Close the connection
                $conn->close();
                ?>

            </div>
            <!-- / Content -->
          </div>
        </div>
      </div>
    </div>
   
        
    </body>
</html>
<?php } ?>