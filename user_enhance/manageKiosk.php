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
        <title>Manage Kiosk</title>
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
                                <h5 class="card-header">Kiosk List
                                    <button type="button" style="float: right;" class="btn rounded-pill btn-icon btn-primary" data-bs-toggle="modal" data-bs-target="#add-kiosk">
                                        <span class="tf-icons bx bx-plus"></span>
                                    </button>
                                </h5>
                                <div class="table-responsive text-nowrap">
                                    <table id="kioskTable" class="table">
                                        <thead>
                                            <tr class="text-nowrap">
                                                <th>#</th>
                                                <th>Kiosk Name</th>
                                                <th>Operation Status</th>
                                                <th>Kiosk Number</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 0;
                                            $ret = mysqli_query(
                                                $conn,
                                                "SELECT * FROM kiosk"
                                            );
                                            while ($row = mysqli_fetch_array($ret)) {
                                                $i++;
                                            ?>
                                                <tr id="<?php echo $row['KioskID'] ?>">
                                                    <th scope="row"><?php echo $i; ?></th>
                                                    <td><?php echo $row['KioskName']; ?></td>
                                                    <td>
                                                        <?php
                                                        echo $row['OperationStatus'];
                                                        ?>
                                                    </td>
                                                    <td><?php echo $row['KioskNum']; ?></td>
                                                    <td>
                                                        <form method="post">
                                                            <div class="dropdown">
                                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item opn" data-bs-toggle="modal" data-bs-target="#edit-kiosk" href="javascript:void(0);" data-id="<?php echo $row['KioskID']; ?>">
                                                                        <i class="bx bx-edit-alt me-1"></i>
                                                                        Edit
                                                                    </a>
                                                                    <a class="dropdown-item del" data-id="<?php echo $row['KioskID']; ?>" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
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
            <div class="modal fade" id="add-kiosk" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel2">Add New Kiosk</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="kioskname" class="form-label">Kiosk Name</label>
                                    <input type="text" id="kioskname" name="kioskname" class="form-control" placeholder="Enter Name" value="" />
                                </div>
                                <div class="col mb-3">
                                    <label for="operationstatus" class="form-label">Operation Status</label>
                                    <input type="text" id="operationstatus" name="operationstatus" class="form-control" placeholder="Enter Status" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="kiosknum" class="form-label">Kiosk Number</label>
                                    <input type="text" id="kiosknum" name="kiosknum" class="form-control" placeholder="Enter Kiosk Number" value="" />
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
            <div class="modal fade" id="edit-kiosk" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel2">Edit Kiosk</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <div class="row">
                                <div class="col mb-3">
                                    <label for="kiosknameEdit" class="form-label">Kiosk Name</label>
                                    <input type="text" id="kiosknameEdit" name="kiosknameEdit" class="form-control" placeholder="Enter Name" value="" />
                                </div>
                                <div class="col mb-3">
                                    <label for="statusEdit" class="form-label">Operation Status</label>
                                    <input type="text" id="statusEdit" name="statusEdit" class="form-control" placeholder="Enter Status" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="kiosknumEdit" class="form-label">Kiosk Number</label>
                                    <input type="text" id="kiosknumEdit" name="kiosknumEdit" class="form-control" placeholder="Enter Kiosk Number" value="" />
                                </div>
                            </div>
                            <input hidden type="text" id="kioskIDEdit" name="kioskIDEdit" class="form-control" placeholder="" value="" />
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
                console.log(col[2].innerText);

                $("#id").val(id);
                $("#kiosknameEdit").val(col[0].innerText);
                $("#statusEdit").val(col[1].innerText);
                $("#kiosknumEdit").val(col[2].innerText);
                $("#kioskIDEdit").val(id);
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
                        window.location.replace("manageKiosk.php?mode=delete&id=" + id);
                    }
                });
            });

            $(document).ready(function() {
                $('#kioskTable').dataTable({
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

    <!-- CRUD Function Comment-->
    <?php
    if (isset($_POST['addBtn'])) {

        $kioskname = $_POST['kioskname'];
        $operationstatus = $_POST['operationstatus'];
        $kiosknum = $_POST['kiosknum'];

        $query = mysqli_query($conn, "INSERT INTO kiosk (KioskName, OperationStatus, KioskNum) VALUES ('$kioskname','$operationstatus', '$kiosknum')");

        if ($query) {
            echo '
      <script type="text/javascript">
      $(document).ready(function(){
        Swal.fire({
          title: "Kiosk Created!",
          icon: "success",
          timer: 2000,
          showConfirmButton: false,
        }).then(function() {
          window.location.href="manageKiosk.php";
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
                  window.location.href="manageKiosk.php";
                });
              });
            </script>
           ';
        }
    }

    if (isset($_POST['editBtn'])) {

        $kioskname = $_POST['kiosknameEdit'];
        $operationstatus = $_POST['statusEdit'];
        $kiosknum = $_POST['kiosknumEdit'];
        $kioskid = $_POST['kioskIDEdit'];

        $query = mysqli_query($conn, "UPDATE kiosk SET KioskName='$kioskname', OperationStatus='$operationstatus', KioskNum='$kiosknum' WHERE KioskID='$kioskid'");

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
          window.location.href="manageKiosk.php";
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
                  window.location.href="manageKiosk.php";
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
                  window.location.href="manageKiosk.php";
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
                    window.location.href="manageKiosk.php";
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