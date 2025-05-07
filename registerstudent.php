<!DOCTYPE html>
<html>

<head>
  <title>Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- connect css -->
  <link rel="stylesheet" type="text/css" href="assets/css/style2.scss">
  <!-- Favicon -->
  <link rel="icon" href="https://umpsa.edu.my/themes/pana/favicon.ico" />
  <?php include('./includes/headsettings.php'); ?>
  <script src="./assets/vendor/libs/jquery/jquery.js"></script>
  <?php

  include './includes/connect.php';
  // QR Library
  require_once './assets/vendor/phpqrcode/qrlib.php';


  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    // $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $password = $_POST['password'];
    $email = $_POST['email'];
    $fullName = $_POST['fullName'];
    $phoneNum = $_POST['phoneNum'];

    $query = mysqli_query($conn, "INSERT INTO user (UserName, Password, FullName, Email, NumPhone, UserType) VALUES ('$username','$password', '$fullName','$email','$phoneNum','Customer')");

    if ($query) {

      $userid = mysqli_insert_id($conn);

      //QR
      $pathQr = './assets/img/qr/';
      $qrCode = $pathQr.$userid.".png";
      QRcode::png("http://localhost/food-kiosk-management-system/test.php?UserID=".$userid, $qrCode, 'H', 4, 4);
      $qrImage = base64_encode(file_get_contents(addslashes($qrCode)));

      $queryQR = mysqli_query($conn, "UPDATE user SET UserQR = '$qrImage' WHERE UserID = '$userid'");

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
  <!-- HTML form for user registration -->
</head>

<body>
  <a href="index.php">
    <img src="assets/img/logo.png" alt="Vendor" width="180" height="100">
  </a>
  <div class="form">
    <div class="container">
      <h1 class="title"><i>Customer Registration Form</i></h1>
      <form action="" method="post" name="registration">
        Email: <input type="text" name="email" placeholder="Email" required />
        Username: <input type="text" name="username" placeholder="Username" required />
        Password: <input type="password" name="password" placeholder="Password" required />
        Full Name: <input type="text" name="fullName" placeholder="Full Name" required />
        Phone Number: <input type="text" name="phoneNum" placeholder="Phone Number" required />
        <input name="submit" type="submit" value="Submit" />
      </form>
      <a href="registration.php"><button>Back</button></a>
    </div>
  </div>
  </form>
</body>

</html>