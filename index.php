<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>

  <!-- Favicon -->
  <link rel="icon" href="https://umpsa.edu.my/themes/pana/favicon.ico" />

  <!-- Luar  -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <!-- <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet"> -->
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.scss" rel="stylesheet">

</head>

<body>
  <?php include('includes/connect.php'); ?>
  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center">

      <!-- <h1 class="logo me-auto"><a href="index.html">KIOSK</a></h1> -->
      <!-- Uncomment below if you prefer to use an image logo -->
      <a href="#" class="logo me-auto"><img src="https://kalam.ump.edu.my/pluginfile.php/1/core_admin/favicon/64x64/1698313381/android-chrome-512x512.png" alt="" class="img-fluid"></a>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto" href="index.php">Home</a></li>
          <li><a class="nav-link scrollto" href="index.php#menu">Daily Menu</a></li>
          <li><a class="nav-link scrollto" href="index.php#vendors">Vendors</a></li>
          <li><a class="nav-link scrollto" href="index.php#about">About Us</a></li>
          <li><a class="nav-link scrollto" href="login.php">Login</a></li>
          <li><a class="getstarted scrollto" href="registration.php">Get Started</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header>
  <!-- End Header -->
  <!-- Start Hero -->
  <section id="hero" class="d-flex align-items-center">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1">
          <h1>Food Kiosk Management System</h1>
          <h2>Web-based application which aim to manage the Fakulti Komputeran Food Kiosk Management System (Kiosk).</h2>
          <div class="d-flex justify-content-center justify-content-lg-start">
            <a href="registration.php" class="btn-get-started scrollto">Get Started</a>
          </div>
        </div>
        <div class="col-lg-6 order-1 order-lg-2 hero-img">
          <img src="assets/img/NasiLemak.png" class="img-fluid animated" alt="">
        </div>
      </div>
    </div>
  </section>
  <!-- End Hero -->
  <!-- ======= Daily Menu Section ======= -->
  <section id="menu" class="menu section-bg">
    <div class="container" data-aos="fade-up">
      <div class="section-title">
        <h2>Available Menus</h2>
        
      </div>
      <div class="row">
        <?php
        $ret = mysqli_query(
          $conn,
          "SELECT * FROM menu INNER JOIN vendor ON menu.KioskID = vendor.KioskID INNER JOIN kiosk ON vendor.KioskID = kiosk.KioskID WHERE Availability ='Available' AND OperationStatus = 'Open' AND ApprovalStatus = 'Approved'"
        );
        while ($row = mysqli_fetch_array($ret)) {
        ?>
        <div class="col mb-3">
          <div class="card" style="width: 15rem;">
            <img class="card-img-top" style="height: 160px;" src="data:image;base64,  <?php echo $row['ItemImage']  ?> " alt="Menu Image">
            <div class="card-body">
              <h5 class="card-title"><?php echo $row['ItemName']; ?></h5>
              <p class="card-text"><?php echo $row['ItemDesc']; ?></p>
              <p class="card-text">Stocks Available : <?php echo $row['Stock']; ?></p>
              <p class="card-text">Kiosk Name : <?php echo $row['VendorName']; ?></p>
              <a href="login.php" class="btn btn-primary">Browse Now</a>
            </div>
          </div>
        </div>
        <?php
        }
        ?>
      </div>
    </div>
  </section>
  <!-- End Daily Menu Section -->
  <!-- ======= Vendor Section ======= -->
  <section id="vendors" class="vendors section-bg">
    <div class="container" data-aos="fade-up">
      <div class="section-title">
        <h2>Vendors</h2>
        
      </div>
      <div class="row">
        <?php
        $ret = mysqli_query(
          $conn,
          "SELECT * FROM vendor INNER JOIN kiosk ON vendor.KioskID = kiosk.KioskID WHERE ApprovalStatus = 'Approved'"
        );
        while ($row = mysqli_fetch_array($ret)) {
        ?>
        <div class="col mb-3">
          <div class="card" style="width: 15rem;">
            <img class="card-img-top" style="height: 160px;" src="data:image;base64,  <?php echo $row['KioskLogo']  ?> " alt="Menu Image">
            <div class="card-body">
              <h5 class="card-title"><?php echo $row['VendorName']; ?></h5>
              <p class="card-text">Kiosk Num : <?php echo $row['KioskNum']; ?></p>
              <p class="card-text">Operation Status : <?php echo $row['OperationStatus']; ?></p>
              <p class="card-text">Vendor QR : <?php echo $row['VendorName']; ?></p>
              <img class="card-img-top" src="data:image;base64,  <?php echo $row['VendorQR']  ?> " alt="Menu Image">
            </div>
          </div>
        </div>
        <?php
        }
        ?>
      </div>
    </div>
  </section>
  <!-- Vendors Section -->
  <!-- Start About Us -->
  <section id="about" class="about">
    <div class="container" data-aos="fade-up">
      <div class="section-title">
        <h2>About Us</h2>
      </div>

      <div class="row content">
        <div class="col-lg-6">
          <p>
            Welcome to Fakulti Komputeran's innovative Food Kiosk Management System, a cutting-edge solution designed to revolutionize
            the way we experience food services on campus. Developed by a team of dedicated professionals at Fakulti Komputeran,
            our system aims to enhance efficiency, convenience, and overall satisfaction for both customers and vendors within the university community.
          </p>
        </div>
        <div class="col-lg-6 pt-4 pt-lg-0">
          <p>
            At Fakulti Komputeran, we envision a seamless and enjoyable food experience on campus. Our Food Kiosk Management System is geared towards creating a dynamic environment that fosters quick, efficient, and delightful interactions for all users.
          </p>
          <a href="#" class="btn-learn-more">Learn More</a>
        </div>
      </div>
    </div>
  </section>
  <!-- End About Us Section -->
</body>

</html>