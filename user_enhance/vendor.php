<!DOCTYPE html>
<html>

<head>
  <title>Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- connect css -->
  <link rel="stylesheet" type="text/css" href="assets/css/style2.scss">
  <script src="./assets/vendor/libs/jquery/jquery.js"></script>
  <?php
  include './includes/connect.php';
  include('./includes/headsettings.php');
  require_once './assets/vendor/phpqrcode/qrlib.php';
  ?>
  <!-- HTML form for user registration -->
</head>

<body>
  <a href="index.php">
    <img src="assets/img/logo.png" alt="Vendor" width="180" height="100">
  </a>
  <div class="form">
    <h1 class="title"><i>Vendor Registration Form</i></h1>
    <div class="container">
      <form method="post" name="registration">
        Vendor Name: <input type="text" name="vendorName" placeholder="Business Name" required />
        Email: <input type="text" name="email" placeholder="Email" required />
        Password: <input type="password" name="password" placeholder="Password" required />
        Phone Number: <input type="text" name="phoneNum" placeholder="Phone Number" required />
        <input id="submitBtn" name="submitBtn" type="submit" value="Submit" />
      </form>
      <a href="registration.php"><button>Back</button></a>
      <p>This form will be send to the administration for approval.</p>
    </div>
  </div>

  <?php

  if (isset($_POST['submitBtn'])) {

    $vendorName = $_POST['vendorName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phoneNum = $_POST['phoneNum'];



    $query = mysqli_query($conn, "INSERT INTO vendor (VendorName, VendorEmail, VendorPassword, VendorNum, ApprovalStatus,KioskID) VALUES ('$vendorName','$email','$password','$phoneNum','Pending',1)");

    if ($query) {

      $vendorID = mysqli_insert_id($conn);

      //QR
      // $pathQr = './assets/img/qr/';
      // $qrCode = $pathQr.$vendorID. ".png";
      // QRcode::png("https://indah.ump.edu.my/CB22151/food-kiosk-management-system/vendorProfile.php?VendorID=".$vendorID, $qrCode, 'H', 4, 4);
      // $qrImage = base64_encode(file_get_contents(addslashes($qrCode)));

      // $queryQR = mysqli_query($conn, "UPDATE vendor SET VendorQR = '$qrImage' WHERE VendorID = '$vendorID'");

      echo '
<script type="text/javascript">
$(document).ready(function(){
Swal.fire({
  title: "Account Created!",
  icon: "success",
  timer: 2000,
  showConfirmButton: false,
}).then(function() {
  window.location.href="login.php";
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
          window.location.href="login.php";
        });
      });
    </script>
   ';
    }
  }

  ?>

</body>

</html>