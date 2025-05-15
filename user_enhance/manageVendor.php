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
        <title>Manage Vendor</title>
        <?php include('../includes/headsettings.php'); ?>
        <script src="../assets/vendor/libs/jquery/jquery.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
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
                            <div class="card">
                                <h5 class="card-header">Vendor List
                                    <button type="button" style="float: right;" class="btn rounded-pill btn-icon btn-primary" data-bs-toggle="modal" data-bs-target="#add-meal">
                                        <span class="tf-icons bx bx-plus"></span>
                                    </button>
                                </h5>
                                <div class="table-responsive text-nowrap">
                                    <table id="menuTable" class="table">
                                        <thead>
                                            <tr class="text-nowrap">
                                                <th>#</th>
                                                <th>Vendor Name</th>
                                                <th>Vendor Email</th>
                                                <th>Phone Number</th>
                                                <th>Approval Status</th>
                                                <th>Approval Date</th>
                                                <th>Kiosk ID</th>
                                                <th>QR</th>
                                                <th hidden>Vendor Password</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 0;
                                            $ret = mysqli_query(
                                                $conn,
                                                "SELECT * FROM vendor"
                                            );
                                            while ($row = mysqli_fetch_array($ret)) {
                                                $i++;
                                            ?>
                                                <tr id="<?php echo $row['VendorID'] ?>">
                                                    <th scope="row"><?php echo $i; ?></th>
                                                    <td><?php echo $row['VendorName']; ?></td>
                                                    <td><?php echo $row['VendorEmail']; ?></td>
                                                    <td>
                                                        <?php
                                                        echo $row['VendorNum'];
                                                        ?>
                                                    </td>
                                                    <td><?php echo $row['ApprovalStatus']; ?></td>
                                                    <td><?php

                                                        if ($row['ApprovalDate'] != null) {
                                                            echo date('d/m/Y', strtotime($row['ApprovalDate']));
                                                        } else {
                                                            echo "Null";
                                                        };
                                                        ?></td>
                                                    <td><?php echo $row['KioskID']; ?></td>
                                                    <td><img style="height: 100px; width: 100px;" src="data:image;base64,  <?php echo $row['VendorQR']  ?> " alt="TestQR"></td>
                                                    <td hidden><?php echo $row['VendorPassword']; ?></td>
                                                    <td>
                                                        <form method="post">
                                                            <div class="dropdown">
                                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item opn" data-bs-toggle="modal" data-bs-target="#edit-menu" href="javascript:void(0);" data-id="<?php echo $row['VendorID']; ?>">
                                                                        <i class="bx bx-edit-alt me-1"></i>
                                                                        Edit
                                                                    </a>
                                                                    <?php

                                                                    $approveStatus = $row['ApprovalStatus'];

                                                                    if ($approveStatus == "Pending") {
                                                                        echo '<a class="dropdown-item approveBtn text-success" href="javascript:void(0);" data-id="' . $row['VendorID'] . '">
                                                                        <i class="bx bx-check-square me-1"></i>
                                                                        Approve
                                                                        </a>';
                                                                    }

                                                                    ?>
                                                                    <a class="dropdown-item del text-danger" data-id="<?php echo $row['VendorID']; ?>" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <!-- / Content -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Add User Modal -->
        <form method="post" enctype="multipart/form-data">
            <div class="modal fade" id="add-meal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel2">Create New Vendor</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="vendorName" class="form-label">Vendor Name</label>
                                    <input type="text" id="vendorName" name="vendorName" class="form-control" placeholder="Enter Vendor Name" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password" value="" />
                                </div>
                                <div class="col mb-3">
                                    <label for="phoneNo" class="form-label">Phone Number</label>
                                    <input type="text" id="phoneNo" name="phoneNo" class="form-control" placeholder="Enter Phone Number" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" id="email" name="email" class="form-control" placeholder="Enter Email" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="approval" class="form-label">Approve Status</label>
                                    <select class="form-select" id="approval" aria-label="Default select example" name="approval">
                                        <option selected>Open this select menu</option>
                                        <option id="Approved" value="Approved">Approved</option>
                                        <option id="Pending" value="Pending">Pending</option>
                                    </select>
                                </div>
                                <div class="col mb-3">
                                    <label for="kiosk" class="form-label">Kiosk</label>
                                    <select class="form-select" id="kiosk" aria-label="Default select example" name="kiosk">
                                        <option selected>Open this select menu</option>
                                        <?php
                                        $kiosk = getListKiosk();
                                        foreach ($kiosk as $row2) {
                                            echo '<option value="' . $row2['KioskID'] . '">' . $row2['KioskName'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" name="addBtn" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Edit User Modal -->
        <form method="post">
            <div class="modal fade" id="edit-menu" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel2">Edit Vendor</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="vendorNameEdit" class="form-label">Vendor Name</label>
                                    <input type="text" id="vendorNameEdit" name="vendorNameEdit" class="form-control" placeholder="Enter Vendor Name" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="passwordEdit" class="form-label">Password</label>
                                    <input type="password" id="passwordEdit" name="passwordEdit" class="form-control" placeholder="Enter Password" value="" />
                                </div>
                                <div class="col mb-3">
                                    <label for="phoneNoEdit" class="form-label">Phone Number</label>
                                    <input type="text" id="phoneNoEdit" name="phoneNoEdit" class="form-control" placeholder="Enter Phone Number" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="emailEdit" class="form-label">Email</label>
                                    <input type="text" id="emailEdit" name="emailEdit" class="form-control" placeholder="Enter Email" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="approvalEdit" class="form-label">Approve Status</label>
                                    <select class="form-select" id="approvalEdit" aria-label="Default select example" name="approvalEdit">
                                        <option selected>Open this select menu</option>
                                        <option id="Approved" value="Approved">Approved</option>
                                        <option id="Pending" value="Pending">Pending</option>
                                    </select>
                                </div>
                                <div class="col mb-3">
                                    <label for="kioskEdit" class="form-label">Kiosk</label>
                                    <select class="form-select" id="kioskEdit" aria-label="Default select example" name="kioskEdit">
                                        <option selected>Open this select menu</option>
                                        <?php
                                        $kiosk = getListKiosk();
                                        foreach ($kiosk as $row2) {
                                            echo '<option value="' . $row2['KioskID'] . '">' . $row2['KioskName'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <input hidden type="text" id="vendorIDEdit" name="vendorIDEdit" class="form-control" placeholder="" value="" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" name="editBtn" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </form>
        <!-- / Layout wrapper -->

        <script>
            var id;
            $(".opn").click(function() {
                id = $(this).data('id');
                var col = $("#".concat(id, " > td"));
                //console.log(col[5].innerText);

                $("#id").val(id);
                $("#vendorNameEdit").val(col[0].innerText);
                $("#emailEdit").val(col[1].innerText);
                $("#phoneNoEdit").val(col[2].innerText);
                $("#approvalEdit").val(col[3].innerText).change();
                $("#kioskEdit").val(col[5].innerText).change();
                $("#passwordEdit").val(col[7].innerText);

                $("#vendorIDEdit").val(id);
            });

            $(".approveBtn").click(function() {
                id = $(this).data('id');

                $.post('../api.php?postVendorStatus=1', {
                    test: id
                }, function(res) {
                    Swal.fire({
                        title: "Vendor Approved!",
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false,
                    }).then(function() {
                        window.location.href = "manageVendor.php";
                    });
                })
            });

            $(".del").click(function() {
                id = $(this).data('id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    con: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.replace("manageVendor.php?mode=delete&id=" + id);
                    }
                });
            });

            $(document).ready(function() {
                $('#menuTable').dataTable({
                    dom: '<"custom-length"f><t><p>',

                    // Callback function to handle the DataTable initialization
                    initComplete: function(settings, json) {
                        $('.custom-length').css('margin-right', '25px'); // Adjust the margin value as needed
                    }
                });
            });

            function preview() {
                frame.src = URL.createObjectURL(event.target.files[0]);
            }

            function clearImage() {
                document.getElementById('formFile').value = null;
                frame.src = "";
            }
        </script>
        <script src="../assets/js/dashboards-analytics.js"></script>
    </body>

    </html>

    <!-- QR Library -->
    <?php
    require_once '../assets/vendor/phpqrcode/qrlib.php';
    ?>

    <!-- CRUD Function -->
    <?php
    if (isset($_POST['addBtn'])) {

        $vendorName = $_POST['vendorName'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $phoneNo = $_POST['phoneNo'];
        $approval = $_POST['approval'];
        $kioskid = $_POST['kiosk'];



        $query = mysqli_query($conn, "INSERT INTO vendor (VendorName, VendorEmail, VendorPassword, VendorNum, ApprovalStatus, KioskID) VALUES ('$vendorName','$email', '$password','$phoneNo','$approval','$kioskid')");

        if ($query) {

            $vendorID = mysqli_insert_id($conn);

            //QR
            $pathQr = '../assets/img/qr/';
            $qrCode = $pathQr.$vendorID.".png";
            QRcode::png("https://indah.ump.edu.my/CB22151/food-kiosk-management-system/vendorProfile.php?VendorID=".$vendorID, $qrCode, 'H', 4, 4);
            $qrImage = base64_encode(file_get_contents(addslashes($qrCode)));

            

            $queryQR = mysqli_query($conn, "UPDATE vendor SET VendorQR = '$qrImage' WHERE VendorID = '$vendorID'");

            echo '
      <script type="text/javascript">
      $(document).ready(function(){
        Swal.fire({
          title: "User Created!",
          icon: "success",
          timer: 2000,
          showConfirmButton: false,
        }).then(function() {
          window.location.href="manageVendor.php";
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
                  window.location.href="manageVendor.php";
                });
              });
            </script>
           ';
        }
    }

    if (isset($_POST['editBtn'])) {

        $vendorName = $_POST['vendorNameEdit'];
        $password = $_POST['passwordEdit'];
        $email = $_POST['emailEdit'];
        $phoneNo = $_POST['phoneNoEdit'];
        $approval = $_POST['approvalEdit'];
        $kioskid = $_POST['kioskEdit'];
        $vendorIDEdit = $_POST['vendorIDEdit'];

        $query = mysqli_query($conn, "UPDATE vendor SET VendorName = '$vendorName',VendorPassword = '$password',VendorEmail = '$email',VendorNum = '$phoneNo',ApprovalStatus = '$approval',KioskID = '$kioskid'  WHERE VendorID = '$vendorIDEdit'");

        if ($query) {
            echo '
      <script type="text/javascript">
      $(document).ready(function(){
        Swal.fire({
          title: "Vendor info Updated!",
          icon: "success",
          timer: 2000,
          showConfirmButton: false,
        }).then(function() {
          window.location.href="manageVendor.php";
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
                  window.location.href="manageVendor.php";
                });
              });
            </script>
           ';
        }
    }

    // Delete Function
    if (isset($_GET['mode'])) {
        if ($_GET['mode'] == "delete") {
            $id = $_GET['id'];
            $query = "DELETE from `vendor` WHERE `VendorID` = $id";
            $result = mysqli_query($conn, $query);

            if ($result) {
                echo '<script type="text/javascript">
              Swal.fire({
                title: "Deleted!",
                text: "The vendor has been deleted.",
                icon: "success",
                confirmButtonText: "OK"
              }).then((result) => {
                if (result.isConfirmed) {
                  window.location.href="manageVendor.php";
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
                    window.location.href="manageVendor.php";
                  }
                });
              });
            </script>
            ';
            }
        }
    }

    //Retrieve image from database and display it on html webpage
    function displayImageFromDatabase()
    {
        //use global keyword to declare conn inside a function
        global $conn;
    }
    ?>
<?php } ?>