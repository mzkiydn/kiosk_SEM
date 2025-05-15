<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="assets/css/style2.scss">
  <link rel="icon" href="https://umpsa.edu.my/themes/pana/favicon.ico" />
  <?php include('./includes/headsettings.php'); ?>
  <script src="./assets/vendor/libs/jquery/jquery.js"></script>
  <style>
    .input-error {
      border: 2px solid red !important;
      background-color: #ffeaea;
    }
  </style>
</head>
<body>
  <a href="index.php">
    <img src="assets/img/logo.png" alt="Logo" width="180" height="100">
  </a>
  <div class="form">
    <div class="container">
      <h1 class="title"><i>Registration Form</i></h1>
      <form action="" method="post" name="registration" id="registrationForm" autocomplete="off">
        Email: <input type="text" name="email" id="email" placeholder="Email" required />
        Username: <input type="text" name="username" id="username" placeholder="Username" required />
        Password: <input type="password" name="password" id="password" placeholder="Password" required />
        Full Name: <input type="text" name="fullName" id="fullName" placeholder="Full Name" required/>
        Phone Number: <input type="text" name="phoneNum" id="phoneNum" placeholder="Phone Number" required />
        <label for="type">Register as:</label>
        <input type="radio" id="customer" name="type" value="Customer" checked onclick="toggleVendorFields()">
        <label for="customer">Customer</label>
        <input type="radio" id="vendor" name="type" value="Vendor" onclick="toggleVendorFields()">
        <label for="vendor">Vendor</label>
        <br>
        <p id="vendorNote" style="display:none;"><i>This form will be sent to the administration for approval.</i></p>
        <input name="submit" type="submit" value="Submit" />
      </form>
      <a href="login.php"><button>Back</button></a>
    </div>
  </div>
  <script>
    function toggleVendorFields() {
      var type = document.querySelector('input[name="type"]:checked').value;
      document.getElementById('vendorNote').style.display = (type === 'Vendor') ? 'block' : 'none';
    }
    window.onload = toggleVendorFields;

    // Client-side validation
    document.getElementById('registrationForm').onsubmit = function(e) {
      let valid = true;

      // Remove previous error highlights
      let fields = ['email', 'username', 'password', 'fullName', 'phoneNum'];
      fields.forEach(function(id) {
        let el = document.getElementById(id);
        if (el) el.classList.remove('input-error');
      });

      // Email validation
      let email = document.getElementById('email');
      let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(email.value)) {
        email.classList.add('input-error');
        valid = false;
      }

      // Username validation (4-20 chars)
      let username = document.getElementById('username');
      if (username.value.length < 4 || username.value.length > 20) {
        username.classList.add('input-error');
        valid = false;
      }

      // Password validation (alphanumeric, min 6 chars)
      let password = document.getElementById('password');
      let passwordPattern = /^[a-zA-Z0-9]{6,}$/;
      if (!passwordPattern.test(password.value)) {
        password.classList.add('input-error');
        valid = false;
      }

      // Full Name validation (letters and spaces only, min 2 chars)
      let fullName = document.getElementById('fullName');
      if (document.getElementById('customer').checked) {
        let fullNamePattern = /^[a-zA-Z\s]{2,}$/;
        if (!fullNamePattern.test(fullName.value)) {
          fullName.classList.add('input-error');
          valid = false;
        }
      }

      // Phone number validation (digits only, 10-15 chars)
      let phoneNum = document.getElementById('phoneNum');
      let phonePattern = /^[0-9]{10,15}$/;
      if (!phonePattern.test(phoneNum.value)) {
        phoneNum.classList.add('input-error');
        valid = false;
      }

      if (!valid) {
        e.preventDefault();
      }
    };
  </script>
  <?php
  include './includes/connect.php';
  require_once './assets/vendor/phpqrcode/qrlib.php';

  // Server-side validation and username existence check
  $inputError = [];
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['type'])) {
    $type = $_POST['type'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phoneNum = $_POST['phoneNum'];
    $fullName = isset($_POST['fullName']) ? $_POST['fullName'] : '';

    // Validate input format server-side
    if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) $inputError['email'] = true;
    // Username: only check length 4-20 
    if (strlen($username) < 4 || strlen($username) > 20) $inputError['username'] = true;
    // Password: must be alphanumeric and at least 6 chars
    if (!preg_match('/^[a-zA-Z0-9]{6,}$/', $password)) $inputError['password'] = true;
    if (!preg_match('/^[0-9]{10,15}$/', $phoneNum)) $inputError['phoneNum'] = true;
    if (!preg_match('/^[a-zA-Z\s]{2,}$/', $fullName)) $inputError['fullName'] = true;

    // Check for unique username in both tables
    $checkUser = mysqli_query($conn, "SELECT 1 FROM user WHERE UserName = '$username' LIMIT 1");
    $checkVendor = mysqli_query($conn, "SELECT 1 FROM vendor WHERE VendorUsername = '$username' LIMIT 1");
    if (mysqli_num_rows($checkUser) > 0 || mysqli_num_rows($checkVendor) > 0) {
      $inputError['username'] = true;
      echo "<script>document.addEventListener('DOMContentLoaded',function(){document.getElementById('username').classList.add('input-error');});</script>";
    }

    // If any error, highlight fields and stop
    if (!empty($inputError)) {
      echo "<script>
      document.addEventListener('DOMContentLoaded',function(){";
      foreach ($inputError as $field => $err) {
        echo "if(document.getElementById('$field'))document.getElementById('$field').classList.add('input-error');";
      }
      echo "});
      </script>";
    } else if ($type == "Customer") {
      $query = mysqli_query($conn, "INSERT INTO user (UserName, Password, FullName, Email, NumPhone, UserType) VALUES ('$username','$password', '$fullName','$email','$phoneNum','Customer')");
      if ($query) {
        $userid = mysqli_insert_id($conn);
        // Optional QR code
        try {
          $pathQr = './assets/img/qr/';
          $qrCode = $pathQr . $userid . ".png";
          QRcode::png("http://localhost/food-kiosk-management-system/test.php?UserID=" . $userid, $qrCode, 'H', 4, 4);
          $qrImage = base64_encode(file_get_contents(addslashes($qrCode)));
          $queryQR = mysqli_query($conn, "UPDATE user SET UserQR = '$qrImage' WHERE UserID = '$userid'");
        } catch (Exception $e) {
          error_log("QR code generation failed: " . $e->getMessage());
        }
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
    } else if ($type == "Vendor") {
      $query = mysqli_query($conn, "INSERT INTO vendor (VendorName, VendorEmail, VendorUsername, VendorPassword, VendorNum, ApprovalStatus, KioskID) VALUES ('$fullName','$email', '$username', '$password','$phoneNum','Pending',1)");
      if ($query) {
        $vendorID = mysqli_insert_id($conn);
        // Optional QR code (uncomment if needed and GD is available)
        // try {
        //   $pathQr = './assets/img/qr/';
        //   $qrCode = $pathQr . $vendorID . ".png";
        //   QRcode::png("https://indah.ump.edu.my/CB22151/food-kiosk-management-system/vendorProfile.php?VendorID=" . $vendorID, $qrCode, 'H', 4, 4);
        //   $qrImage = base64_encode(file_get_contents(addslashes($qrCode)));
        //   $queryQR = mysqli_query($conn, "UPDATE vendor SET VendorQR = '$qrImage' WHERE VendorID = '$vendorID'");
        // } catch (Exception $e) {
        //   error_log("QR code generation failed: " . $e->getMessage());
        // }
        echo '
<script type="text/javascript">
$(document).ready(function(){
Swal.fire({
  title: "Account Created!",
  icon: "success",
  text: "Your application will be reviewed by admin.",
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
  }
  ?>
</body>
</html>