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
    <title>Edit Profile</title>
    <?php include('../includes/headsettings.php'); ?>
  </head>

  <?php

  $uid = $_SESSION['User'];

  $ret = mysqli_query(
    $conn,
    "SELECT * FROM vendor INNER JOIN kiosk ON vendor.KioskID = kiosk.KioskID WHERE VendorID = $uid"
  );

  while ($row = mysqli_fetch_array($ret)) {

  ?>

    <body>
      <script src="../assets/vendor/libs/jquery/jquery.js"></script>
      <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
          <?php include('../includes/sidebar.php'); ?>
          <div class="layout-page">
            <?php include('../includes/header.php'); ?>
            <div class="content-wrapper">
              <!-- Content -->

              <div class="container-xxl flex-grow-1 container-p-y">
                <h4 class="fw-bold py-2 mb-2"><span class="text-muted fw-light">Account Settings /</span> Account</h4>
                <div class="row">
                  <div class="col-md-12">
                    <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    </ul>
                    <div class="card mb-4">
                      <h5 class="card-header">Profile Details</h5>
                      <!-- Account -->
                      <hr class="my-0" />
                      <div class="card-body">
                        <form id="formAccountSettings" method="post" enctype="multipart/form-data">
                          <div class="row">
                            <div class="mb-3 col-md-6">
                              <label for="vendorName" class="form-label">Vendor Name</label>
                              <input class="form-control" type="text" id="vendorName" name="vendorName" value="<?php echo $row['VendorName']; ?>" autofocus />
                            </div>
                            <div class="mb-3 col-md-6">
                              <label for="email" class="form-label">E-mail</label>
                              <input class="form-control" type="text" id="email" name="email" value="<?php echo $row['VendorEmail']; ?>" placeholder="john.doe@example.com" />
                            </div>
                            <div class="mb-3 col-md-6">
                              <label class="form-label" for="phoneNumber">Phone Number</label>
                              <div class="input-group input-group-merge">
                                <span class="input-group-text">MY (+6)</span>
                                <input type="text" id="phoneNumber" name="phoneNumber" class="form-control" value="<?php echo $row['VendorNum']; ?>" placeholder="1234567890" />
                              </div>
                            </div>
                            <div class="mb-3 col-md-6">
                              <label for="password" class="form-label">Password</label>
                              <input class="form-control" type="password" id="password" name="password" value="<?php echo $row['VendorPassword']; ?>" autofocus />
                            </div>
                            <div class="mb-3 col-md-6">
                              <label for="qr" class="form-label">QR Code</label>
                              <img style="height: 100px; width: 100px;" src="data:image/png;base64, <?php echo $row['VendorQR']; ?>" alt="QR Code">
                            </div>
                            <div class="mb-3 col-md-6">
                              <label for="kioskStatus" class="form-label">Kiosk Status</label>
                              <select class="form-select" id="kioskStatus" aria-label="Default select example" name="kioskStatus" >
                                <option <?php if($row['OperationStatus'] == "Open"){echo "selected";}  ?> id="kioskOpen" value="Open">Open</option>
                                <option <?php if($row['OperationStatus'] == "Close"){echo "selected";} ?> id="kioskClose" value="Close">Close</option>
                              </select>
                            </div>
                          </div>


                          <div class="mt-2">
                            <button id="editBtn" name="editBtn" type="submit" class="btn btn-primary me-2">Save changes</button>
                            <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                          </div>
                        </form>
                      </div>
                      <!-- /Account -->
                    </div>

                    <div class="card mb-4">
                      <h5 class="card-header">Edit Logo</h5>
                      <!-- Account -->
                      <hr class="my-0" />
                      <div class="card-body">
                        <form id="editLogo" method="post" enctype="multipart/form-data">
                          <div class="row">
                            <div class="col mb-3">
                              <label for="formFile" class="form-label">Kiosk Logo </label>
                              <input class="form-control" type="file" id="formFile" name="formFile" onchange="preview()">
                            </div>
                          </div>
                          <div class="row">
                            <div class="col mb-3">
                              <img id="frame" src="data:image;base64,  <?php echo $row['KioskLogo']  ?>" class="img-fluid" style="height: 200px;" />
                            </div>
                          </div>
                          <div class="mt-2">
                            <button id="editLogoBtn" name="editLogoBtn" type="submit" class="btn btn-primary me-2" disabled>Save changes</button>
                          </div>
                        </form>
                      </div>
                      <!-- /Account -->
                    </div>

                    <script>
                      function checkMe() {
                        var checkbox = document.getElementById("accountDeactivation");

                        if (checkbox.checked == true) {
                          document.getElementById("deactiveBtn").disabled = false;
                        } else {
                          document.getElementById("deactiveBtn").disabled = true;
                        }
                      }

                      function preview() {
                        frame.src = URL.createObjectURL(event.target.files[0]);

                        if (document.getElementById("formFile").files.length >= 1) {
                          document.getElementById("editLogoBtn").disabled = false;
                        }
                      }

                      function clearImage() {
                        document.getElementById('formFile').value = null;
                        frame.src = "";
                      }

                      function enableLogoBtn() {

                      }
                    </script>
                    <div class="card">
                      <h5 class="card-header">Delete Account</h5>
                      <div class="card-body">
                        <div class="mb-3 col-12 mb-0">
                          <div class="alert alert-warning">
                            <h6 class="alert-heading fw-bold mb-1">Are you sure you want to delete your account?</h6>
                            <p class="mb-0">Once you delete your account, there is no going back. Please be certain.</p>
                          </div>
                        </div>
                        <form id="formAccountDeactivation" method="post">
                          <div class="form-check mb-3">
                            <input class="form-check-input accountDeactivation" type="checkbox" name="accountDeactivation" id="accountDeactivation" onclick="checkMe()" />
                            <label class="form-check-label" for="accountDeactivation">I confirm my account deactivation</label>
                          </div>

                          <button name="deactiveBtn" id="deactiveBtn" type="submit" class="btn btn-danger deactiveBtn" disabled>Deactivate Account</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- / Content -->
            </div>
          </div>
        </div>
      </div>
      <!-- / Layout wrapper -->
    </body>

    <!-- CRUD -->
    <?php

    if (isset($_POST['editBtn'])) {

      $uid = $_SESSION['User'];
      $vendorName = $_POST['vendorName'];
      $email = $_POST['email'];
      $phoneNumber = $_POST['phoneNumber'];
      $password = $_POST['password'];
      $kioskStatus = $_POST['kioskStatus'];

      $query = mysqli_query($conn, "UPDATE vendor INNER JOIN kiosk ON vendor.KioskID = kiosk.KioskID SET VendorName = '$vendorName', VendorEmail = '$email', VendorNum = '$phoneNumber', VendorPassword = '$password', OperationStatus = '$kioskStatus'  WHERE VendorID = '$uid'");

      if ($query) {
        echo '
        <script type="text/javascript">
        $(document).ready(function(){
          Swal.fire({
            title: "Profile Updated!",
            icon: "success",
            timer: 2000,
            showConfirmButton: false,
          }).then(function() {
            window.location.href="profile.php";
          });
        });
  
        </script>
              ';
      } else {
        echo '
        <script type="text/javascript">
                $(document).ready(function(){
                  Swal.fire({
                    title: "Something went wrong! 😢",
                    text: "Please try again",
                    icon: "error",
                    timer: 2000,
                    showConfirmButton: false,
                  }).then(function() {
                    window.location.href="profile.php";
                  });
                });
              </script>
             ';
      }
    }

    if (isset($_POST['editLogoBtn'])) {
      //declare variables
      $uid = $_SESSION['User'];
      $image = $_FILES['formFile']['tmp_name'];
      $name = $_FILES['formFile']['name'];
      $image = base64_encode(file_get_contents(addslashes($image)));

      if ($image != null) {
        $query = mysqli_query($conn, "UPDATE vendor INNER JOIN kiosk ON vendor.KioskID = kiosk.KioskID SET KioskLogo = '$image'  WHERE VendorID = '$uid'");
        if ($query) {
          echo '
          <script type="text/javascript">
          $(document).ready(function(){
            Swal.fire({
              title: "Logo Updated!",
              icon: "success",
              timer: 2000,
              showConfirmButton: false,
            }).then(function() {
              window.location.href="profile.php";
            });
          });
    
          </script>
                ';
        } else {
          echo '
          <script type="text/javascript">
                  $(document).ready(function(){
                    Swal.fire({
                      title: "Something went wrong! 😢",
                      text: "Please try again",
                      icon: "error",
                      timer: 2000,
                      showConfirmButton: false,
                    }).then(function() {
                      window.location.href="profile.php";
                    });
                  });
                </script>
               ';
        }
      } else {
        echo '
          <script type="text/javascript">
                  $(document).ready(function(){
                    Swal.fire({
                      title: "Something went wrong! 😢",
                      text: "Please choose your image!",
                      icon: "error",
                      timer: 2000,
                      showConfirmButton: false,
                    }).then(function() {
                      window.location.href="profile.php";
                    });
                  });
                </script>
               ';
      }
    }

    if (isset($_POST['deactiveBtn'])) {

      $uid = $_SESSION['User'];

      $query = "DELETE from `vendor` WHERE `VendorID` = $uid";
      $result = mysqli_query($conn, $query);

      if ($result) {
        echo '<script type="text/javascript">
              Swal.fire({
                title: "Deleted!",
                text: "Your account has been deleted.",
                icon: "success",
                confirmButtonText: "OK"
              }).then((result) => {
                if (result.isConfirmed) {
                  window.location.href="../logout.php";
                }
              });
            </script>';
      } else {
        echo '
            <script type="text/javascript">
            $(document).ready(function() {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Something went wrong!",
                confirmButtonText: "Back"
                }).then((result) => {
                  if (result.isConfirmed) {
                    window.location.href="profile.php";
                  }
                });
              });
            </script>
            ';
      }
    }

    ?>

  </html>

<?php }
} ?>