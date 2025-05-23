<?php
session_start();
//error_reporting(0);
include('../includes/connect.php');
include('../functions/functions.php');

if (isset($_GET['mode']) && $_GET['mode'] == "delete" && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Unassign all vendors from this kiosk first
    mysqli_query($conn, "UPDATE vendor SET KioskID = NULL, ApprovalStatus = 'Approved' WHERE KioskID = $id");
    $query = "DELETE FROM `kiosk` WHERE `KioskID` = $id";
    $result = mysqli_query($conn, $query);

    // Always redirect back to manageKiosk.php after delete
    header("Location: manageKiosk.php");
    exit();
}

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
                                                <th>Assigned Vendor</th> 
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
                                                // Find assigned vendor for this kiosk
                                                $kioskID = $row['KioskID'];
                                                $vendorQuery = mysqli_query($conn, "SELECT VendorName FROM vendor WHERE KioskID = '$kioskID' AND ApprovalStatus = 'Assigned' LIMIT 1");
                                                $vendorRow = mysqli_fetch_assoc($vendorQuery);
                                                $assignedVendor = $vendorRow ? $vendorRow['VendorName'] : '';
                                            ?>
                                                <tr id="<?php echo $row['KioskID'] ?>">
                                                    <th scope="row"><?php echo $i; ?></th>
                                                    <td><?php echo $row['KioskName']; ?></td>
                                                    <td><?php echo $row['OperationStatus']; ?></td>
                                                    <td><?php echo $row['KioskNum']; ?></td>
                                                    <td>
                                                        <?php
                                                        if ($row['OperationStatus'] == 'Pending') {
                                                            // Find vendor who requested this kiosk (ApprovalStatus = 'Requested')
                                                            $pendingVendorQuery = mysqli_query($conn, "SELECT VendorName FROM vendor WHERE KioskID = '{$row['KioskID']}' AND ApprovalStatus = 'Requested' LIMIT 1");
                                                            $pendingVendor = mysqli_fetch_assoc($pendingVendorQuery);
                                                            echo $pendingVendor ? '<span class="text-warning">' . htmlspecialchars($pendingVendor['VendorName']) . ' (Requested)</span>' : '-';
                                                        } else {
                                                            echo htmlspecialchars($assignedVendor);
                                                        }
                                                        ?>
                                                    </td> 
                                                    <td>
                                                        <form method="post" style="display:inline;">
                                                            <div class="dropdown" style="display:inline;">
                                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item opn" data-bs-toggle="modal" data-bs-target="#edit-kiosk" href="javascript:void(0);" data-id="<?php echo $row['KioskID']; ?>">
                                                                        <i class="bx bx-edit-alt me-1"></i>
                                                                        Edit
                                                                    </a>
                                                                    <a class="dropdown-item" href="manageKiosk.php?mode=delete&id=<?php echo $row['KioskID']; ?>">
                                                                        <i class="bx bx-trash me-1"></i> Delete
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <?php if ($row['OperationStatus'] == 'Pending') { ?>
                                                                <input type="hidden" name="pendingKioskID" value="<?php echo $row['KioskID']; ?>">
                                                                <button type="submit" name="acceptKioskRequest" class="btn btn-success btn-sm" style="margin-left:5px;">Accept</button>
                                                                <button type="submit" name="rejectKioskRequest" class="btn btn-danger btn-sm" style="margin-left:2px;">Reject</button>
                                                            <?php } ?>
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
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="operationstatus" class="form-label">Operation Status</label>
                                    <select id="operationstatus" name="operationstatus" class="form-control">
                                        <option value="Available">Available</option>
                                        <option value="Open">Open</option>
                                        <option value="Closed">Closed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="kiosknum" class="form-label">Kiosk Number</label>
                                    <input type="text" id="kiosknum" name="kiosknum" class="form-control" placeholder="Enter Kiosk Number" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label class="form-label">Assigned Vendor</label>
                                    <select id="assignedVendorAdd" name="assignedVendorAdd" class="form-control">
                                        <option value="">-</option>
                                        <?php
                                        // List all vendors with ApprovalStatus = 'Approved'
                                        $vendorList = mysqli_query($conn, "SELECT VendorID, VendorName FROM vendor WHERE ApprovalStatus = 'Approved'");
                                        while ($v = mysqli_fetch_assoc($vendorList)) {
                                            echo '<option value="' . $v['VendorID'] . '">' . htmlspecialchars($v['VendorName']) . '</option>';
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
                                    <select id="statusEdit" name="statusEdit" class="form-control">
                                        <option value="Available">Available</option>
                                        <option value="Open">Open</option>
                                        <option value="Closed">Closed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="kiosknumEdit" class="form-label">Kiosk Number</label>
                                    <input type="text" id="kiosknumEdit" name="kiosknumEdit" class="form-control" placeholder="Enter Kiosk Number" value="" readonly />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label class="form-label">Assigned Vendor</label>
                                    <select id="assignedVendorEdit" name="assignedVendorEdit" class="form-control">
                                        <option value="">-</option>
                                        <?php
                                        // List all vendors with ApprovalStatus = 'Approved'
                                        $vendorList = mysqli_query($conn, "SELECT VendorID, VendorName FROM vendor WHERE ApprovalStatus = 'Approved'");
                                        while ($v = mysqli_fetch_assoc($vendorList)) {
                                            echo '<option value="' . $v['VendorID'] . '">' . htmlspecialchars($v['VendorName']) . '</option>';
                                        }
                                        ?>
                                    </select>
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
                $("#id").val(id);
                $("#kiosknameEdit").val(col[0].innerText);
                $("#statusEdit").val(col[1].innerText.trim());
                $("#kiosknumEdit").val(col[2].innerText);
                $("#kioskIDEdit").val(id);

                // AJAX to get assigned vendor for this kiosk (returns VendorID or empty)
                $.ajax({
                    url: "getAssignedVendorID.php",
                    method: "POST",
                    data: { kioskID: id },
                    success: function(response) {
                        $("#assignedVendorEdit").val(response);
                    },
                    error: function() {
                        $("#assignedVendorEdit").val("");
                    }
                });
            });

            $(document).on('click', '.del', function() {
    var id = $(this).data('id');
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
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

            $('#assignedVendorAdd').on('change', function() {
    if ($(this).val() === "") {
        $('#operationstatus').html('<option value="Available" selected>Available</option>');
    } else {
        $('#operationstatus').html('<option value="Open">Open</option><option value="Closed">Closed</option>');
        $('#operationstatus').val('Open');
    }
});

$('#assignedVendorEdit').on('change', function() {
    if ($(this).val() === "") {
        $('#statusEdit').html('<option value="Available" selected>Available</option>');
    } else {
        $('#statusEdit').html('<option value="Open">Open</option><option value="Closed">Closed</option>');
        $('#statusEdit').val('Open');
    }
});
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
        $assignedVendorID = $_POST['assignedVendorAdd'];

        // Enforce logic in backend
        if (empty($assignedVendorID)) {
            $operationstatus = 'Available';
        } else if ($operationstatus != 'Open' && $operationstatus != 'Closed') {
            $operationstatus = 'Open';
        }

        $query = mysqli_query($conn, "INSERT INTO kiosk (KioskName, OperationStatus, KioskNum) VALUES ('$kioskname','$operationstatus', '$kiosknum')");

        if ($query) {
            $newKioskID = mysqli_insert_id($conn);
            if (!empty($assignedVendorID)) {
                mysqli_query($conn, "UPDATE vendor SET KioskID='$newKioskID', ApprovalStatus='Assigned' WHERE VendorID='$assignedVendorID'");
            }
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
        $assignedVendorID = $_POST['assignedVendorEdit'];

        // Enforce logic in backend
        if (empty($assignedVendorID)) {
            $operationstatus = 'Available';
        } else if ($operationstatus != 'Open' && $operationstatus != 'Closed') {
            $operationstatus = 'Open';
        }

        $query = mysqli_query($conn, "UPDATE kiosk SET KioskName='$kioskname', OperationStatus='$operationstatus', KioskNum='$kiosknum' WHERE KioskID='$kioskid'");

        // Unassign any vendor currently assigned to this kiosk
        mysqli_query($conn, "UPDATE vendor SET KioskID=NULL, ApprovalStatus='Approved' WHERE KioskID='$kioskid' AND ApprovalStatus='Assigned'");
        // Assign the selected vendor if any
        if (!empty($assignedVendorID)) {
            mysqli_query($conn, "UPDATE vendor SET KioskID='$kioskid', ApprovalStatus='Assigned' WHERE VendorID='$assignedVendorID'");
        }

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

    if (isset($_POST['acceptKioskRequest'])) {
    $kioskID = intval($_POST['pendingKioskID']);
    // Assign the vendor (ApprovalStatus = 'Requested') to this kiosk
    $vendorQuery = mysqli_query($conn, "SELECT VendorID FROM vendor WHERE KioskID = '$kioskID' AND ApprovalStatus = 'Requested' LIMIT 1");
    if ($vendorRow = mysqli_fetch_assoc($vendorQuery)) {
        $vendorID = $vendorRow['VendorID'];
        // Set this vendor as assigned, set kiosk to Open
        mysqli_query($conn, "UPDATE vendor SET ApprovalStatus = 'Assigned' WHERE VendorID = '$vendorID'");
        mysqli_query($conn, "UPDATE kiosk SET OperationStatus = 'Open' WHERE KioskID = '$kioskID'");
    }
    echo '<script>
        Swal.fire({title:"Request Accepted!",icon:"success"}).then(function(){window.location.href="manageKiosk.php";});
    </script>';
    exit();
}

if (isset($_POST['rejectKioskRequest'])) {
    $kioskID = intval($_POST['pendingKioskID']);
    // Find the vendor who requested
    $vendorQuery = mysqli_query($conn, "SELECT VendorID FROM vendor WHERE KioskID = '$kioskID' AND ApprovalStatus = 'Requested' LIMIT 1");
    if ($vendorRow = mysqli_fetch_assoc($vendorQuery)) {
        $vendorID = $vendorRow['VendorID'];
        // Set vendor status to Rejected and unassign kiosk
        mysqli_query($conn, "UPDATE vendor SET ApprovalStatus = 'Rejected' WHERE VendorID = '$vendorID'");
        // Set kiosk status to Available
        mysqli_query($conn, "UPDATE kiosk SET OperationStatus = 'Available' WHERE KioskID = '$kioskID'");
    }
    echo '<script>
        Swal.fire({title:"Request Rejected!",icon:"info"}).then(function(){window.location.href="manageKiosk.php";});
    </script>';
    exit();
}
    //Retrieve image from database and display it on html webpage
    function displayImageFromDatabase()
    {
        //use global keyword to declare conn inside a function
        global $conn;
    }
    ?>
<?php } ?>