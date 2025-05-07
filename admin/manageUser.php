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
        <title>Manage Menu</title>
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
                                <h5 class="card-header">User List
                                    <button type="button" style="float: right;" class="btn rounded-pill btn-icon btn-primary" data-bs-toggle="modal" data-bs-target="#add-meal">
                                        <span class="tf-icons bx bx-plus"></span>
                                    </button>
                                </h5>
                                <div class="table-responsive text-nowrap">
                                    <table id="menuTable" class="table">
                                        <thead>
                                            <tr class="text-nowrap">
                                                <th>#</th>
                                                <th>Username</th>
                                                <th>FullName</th>
                                                <th>Email</th>
                                                <th>Phone Number</th>
                                                <th>User Type</th>
                                                <th>QR</th>
                                                <th hidden>Password</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 0;
                                            $ret = mysqli_query(
                                                $conn,
                                                "SELECT * FROM user"
                                            );
                                            while ($row = mysqli_fetch_array($ret)) {
                                                $i++;
                                            ?>
                                                <tr id="<?php echo $row['UserID'] ?>">
                                                    <th scope="row"><?php echo $i; ?></th>
                                                    <td><?php echo $row['UserName']; ?></td>
                                                    <td>
                                                        <?php
                                                        echo $row['FullName'];
                                                        ?>
                                                    </td>
                                                    <td><?php echo $row['Email']; ?></td>
                                                    <td><?php echo $row['NumPhone']; ?></td>
                                                    <td><?php echo $row['UserType']; ?></td>
                                                    <td><img style="height: 100px; width: 100px;" src="data:image;base64,  <?php echo $row['UserQR']  ?> " alt="TestQR"></td>
                                                    <td hidden><?php echo $row['Password']; ?></td>
                                                    <td>
                                                        <form method="post">
                                                            <div class="dropdown">
                                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item opn" data-bs-toggle="modal" data-bs-target="#edit-menu" href="javascript:void(0);" data-id="<?php echo $row['UserID']; ?>">
                                                                        <i class="bx bx-edit-alt me-1"></i>
                                                                        Edit
                                                                    </a>
                                                                    <a class="dropdown-item del" data-id="<?php echo $row['UserID']; ?>" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
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
                            <h5 class="modal-title" id="exampleModalLabel2">Create New User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="username" class="form-label">User Name</label>
                                    <input type="text" id="username" name="username" class="form-control" placeholder="Enter Name" value="" />
                                </div>
                                <div class="col mb-3">
                                    <label for="fullName" class="form-label">Full Name</label>
                                    <input type="text" id="fullName" name="fullName" class="form-control" placeholder="Enter Full Name" value="" />
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
                                    <label for="usertype" class="form-label">Select User Type</label>
                                    <select class="form-select" id="usertype" aria-label="Default select example" name="usertype">
                                        <option selected>Open this select menu</option>
                                        <option id="Customer" value="Customer">Customer</option>
                                        <option id="Admin" value="Admin">Admin</option>
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
                            <h5 class="modal-title" id="exampleModalLabel2">Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <div class="row">
                                <div class="col mb-3">
                                    <label for="usernameEdit" class="form-label">User Name</label>
                                    <input type="text" id="usernameEdit" name="usernameEdit" class="form-control" placeholder="Enter Name" value="" />
                                </div>
                                <div class="col mb-3">
                                    <label for="fullNameEdit" class="form-label">Full Name</label>
                                    <input type="text" id="fullNameEdit" name="fullNameEdit" class="form-control" placeholder="Enter Full Name" value="" />
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
                                    <label for="usertypeEdit" class="form-label">Select User Type</label>
                                    <select class="form-select" id="usertypeEdit" aria-label="Default select example" name="usertypeEdit">
                                        <option selected>Open this select menu</option>
                                        <option id="Customer" value="Customer">Customer</option>
                                        <option id="Admin" value="Admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                            <input hidden type="text" id="userIDEdit" name="userIDEdit" class="form-control" placeholder="" value="" />
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
                console.log(col[5].innerText);

                $("#id").val(id);
                $("#usernameEdit").val(col[0].innerText);
                $("#fullNameEdit").val(col[1].innerText);
                $("#emailEdit").val(col[2].innerText);
                $("#phoneNoEdit").val(col[3].innerText);
                $("#usertypeEdit").val(col[4].innerText).change();
                $("#passwordEdit").val(col[6].innerText);
                $("#userIDEdit").val(id);
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
                        window.location.replace("manageUser.php?mode=delete&id=" + id);
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

        $username = $_POST['username'];
        $fullName = $_POST['fullName'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $phoneNo = $_POST['phoneNo'];
        $usertype = $_POST['usertype'];

        //QR
        $pathQr = '../assets/img/qr/';
        $qrCode = $pathQr . time() . ".png";
        QRcode::png($phoneNo . "uid=" . $username, $qrCode, 'H', 4, 4);
        $qrImage = base64_encode(file_get_contents(addslashes($qrCode)));

        $query = mysqli_query($conn, "INSERT INTO user (UserName, Password, FullName, Email, NumPhone, UserType, UserQR) VALUES ('$username','$password', '$fullName','$email','$phoneNo','$usertype','$qrImage')");

        if ($query) {
            echo '
      <script type="text/javascript">
      $(document).ready(function(){
        Swal.fire({
          title: "User Created!",
          icon: "success",
          timer: 2000,
          showConfirmButton: false,
        }).then(function() {
          window.location.href="manageUser.php";
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
                  window.location.href="manageUser.php";
                });
              });
            </script>
           ';
        }
    }

    if (isset($_POST['editBtn'])) {

        $usernameEdit = $_POST['usernameEdit'];
        $fullNameEdit = $_POST['fullNameEdit'];
        $passwordEdit = $_POST['passwordEdit'];
        $emailEdit = $_POST['emailEdit'];
        $phoneNoEdit = $_POST['phoneNoEdit'];
        $usertypeEdit = $_POST['usertypeEdit'];
        $userIDEdit = $_POST['userIDEdit'];

        $query = mysqli_query($conn, "UPDATE user SET UserName = '$usernameEdit', FullName = '$fullNameEdit', Email = '$emailEdit', Password = '$passwordEdit', NumPhone = '$phoneNoEdit', UserType = '$usertypeEdit' WHERE UserID = '$userIDEdit'");

        if ($query) {
            echo '
      <script type="text/javascript">
      $(document).ready(function(){
        Swal.fire({
          title: "User info Updated!",
          icon: "success",
          timer: 2000,
          showConfirmButton: false,
        }).then(function() {
          window.location.href="manageUser.php";
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
                  window.location.href="manageUser.php";
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
            $query = "DELETE from `user` WHERE `UserID` = $id";
            $result = mysqli_query($conn, $query);

            if ($result) {
                echo '<script type="text/javascript">
              Swal.fire({
                title: "Deleted!",
                text: "The user has been deleted.",
                icon: "success",
                confirmButtonText: "OK"
              }).then((result) => {
                if (result.isConfirmed) {
                  window.location.href="manageUser.php";
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
                    window.location.href="manageUser.php";
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