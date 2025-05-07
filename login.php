<!DOCTYPE html>
<html>

<head>
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Favicon -->
  <link rel="icon" href="https://umpsa.edu.my/themes/pana/favicon.ico" />

  <!-- Luar  -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <!-- <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet"> -->
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">

  <!-- connect css -->
  <link rel="stylesheet" type="text/css" href="assets/css/style2.scss">
  <!-- HTML form for user login -->
</head>

<body>
  <a href="index.php">
    <img src="assets/img/logo.png" alt="Vendor" width="180" height="100">
  </a>
  <div class="form">
    <h1 class="title"><i>Food Kiosk Management System</i></h1>
    <div class="container">
      <div class="row">
        <p>Login to your Account</p>
      </div>
      <form action="functions/submitLogin.php" method="post" name="login" id="login">
        <div class="row">
          <div class="col mb-3">
            Your Username: <input type="text" name="username" placeholder="Username" required />
          </div>

        </div>
        <div class="row">
          <div class="col mb-3">
            Your Password: <input type="password" name="password" placeholder="Password" required />
          </div>
        </div>
        <div class="row">
          <div class="col mb-3">
            <select class="form-select" id="userType" aria-label="Default select example" name="userType">
              <option value="" selected>Open this select menu</option>
              <option id="Customer" value="Customer">Customer</option>
              <option id="Vendor" value="Vendor">Vendor</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col mb-3">
            <input name="submit" type="submit" value="Login" />
          </div>
        </div>
      </form>
      <form action="functions/submitLogin.php" method="post">
      <div class="row">
          <div class="col mb-3">
            <input name="guest" type="submit" value="Login as Guest" />
          </div>
        </div>
      </form>

      <p><a href='registration.php'>Don't have an account?</a></p>
      <!-- <p><a href='forgot.php'>Forgot Password?</a></p> -->
    </div>
  </div>
</body>

</html>