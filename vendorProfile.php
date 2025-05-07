<?php
include('includes/connect.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Generating QR Codes with PHP</title>
    <meta charset="UTF-8">
    <!-- Favicon -->
    <link rel="icon" href="https://umpsa.edu.my/themes/pana/favicon.ico" />

    <!-- Luar  -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <!-- <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet"> -->
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <style>
        .gradient-custom {
            /* fallback for old browsers */
            background: #f6d365;

            /* Chrome 10-25, Safari 5.1-6 */
            background: -webkit-linear-gradient(to right bottom, rgba(246, 211, 101, 1), rgba(253, 160, 133, 1));

            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            background: linear-gradient(to right bottom, rgba(246, 211, 101, 1), rgba(253, 160, 133, 1))
        }
    </style>
</head>

<body>
    <div>
        <?php

        $uid = $_GET['VendorID'];

        $ret = mysqli_query(
            $conn,
            "SELECT * FROM vendor INNER JOIN kiosk ON vendor.KioskID = kiosk.KioskID WHERE VendorID = $uid"
        );

        while ($row = mysqli_fetch_array($ret)) {
        ?>
            <section class="vh-100" style="background-color: #f4f5f7;">
                <div class="container py-5 h-100">
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="col col-lg-6 mb-4 mb-lg-0">
                            <div class="card mb-3" style="border-radius: .5rem;">
                                <div class="row g-0">
                                    <div class="col-md-4 gradient-custom text-center text-white" style="border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;">
                                        <img src="data:image;base64,  <?php echo $row['KioskLogo']  ?>" alt="Avatar" class="img-fluid my-5" style="width: 80px;" />
                                        <h5><?php echo $row['VendorName']; ?></h5>
                                        <p><?php echo $row['KioskNum']; ?></p>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body p-4">
                                            <h6>Information</h6>
                                            <hr class="mt-0 mb-4">
                                            <div class="row pt-1">
                                                <div class="col-6 mb-3">
                                                    <h6>Email</h6>
                                                    <p class="text-muted"><?php echo $row['VendorEmail']; ?></p>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <h6>Phone</h6>
                                                    <p class="text-muted"><?php echo $row['VendorNum']; ?></p>
                                                </div>
                                            </div>
                                            <h6>Status</h6>
                                            <hr class="mt-0 mb-4">
                                            <div class="row pt-1">
                                                <div class="col-6 mb-3">
                                                    <h6>Operation Status</h6>
                                                    <p class="text-muted"><?php echo $row['OperationStatus']; ?></p>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <h6>Vendor QR</h6>
                                                    <img style="height: 100px; width: 100px;" src="data:image/png;base64, <?php echo $row['VendorQR']; ?>" alt="QR Code">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
    </div>
<?php } ?>
</body>

</html>