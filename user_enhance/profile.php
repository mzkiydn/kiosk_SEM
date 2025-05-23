<?php
session_start();
include(__DIR__ . '/../includes/connect.php');
include(__DIR__ . '/../functions/functions.php');

if (!isset($_SESSION['User'])) {
  header('location:../../login.php');
  exit();
}

$uid = $_SESSION['User'];
$role = $_SESSION['Role'];
$userType = '';
$row = null;

// Try user table first
$ret = mysqli_query($conn, "SELECT * FROM user WHERE UserID = $uid");
$ret1 = mysqli_query($conn, "SELECT * FROM vendor WHERE VendorID = $uid");

$row = mysqli_fetch_assoc($ret);
if ($row && ($role == 2 || $role == 3)) {
    $userType = $row['UserType'];
} else {
    // Try vendor table (for Kiosk)
    $row = mysqli_fetch_assoc($ret1);
    if ($row && $role == 1) {
        $userType = 'Vendor';
        // Get operation status if exists
        $VendorName = isset($row['VendorName']) ? $row['VendorName'] : '';
        $VendorEmail = isset($row['VendorEmail']) ? $row['VendorEmail'] : '';
        $VendorNum = isset($row['VendorNum']) ? $row['VendorNum'] : '';
        $VendorPassword = isset($row['VendorPassword']) ? $row['VendorPassword'] : '';
    } else {
        echo "<script>alert('User not found!');window.location='login.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile</title>
  <?php include('../includes/headsettings.php'); ?>

  <!-- Add this to test if the file loads -->
  
  <link rel="stylesheet" href="../../assets/css/style.scss">
</head>
<body>
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>

  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <?php include('../includes/sidebar.php'); ?>
      <div class="layout-page">
        <?php include('../includes/header.php'); ?>        
        <div class="content-wrapper">


          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-2 mb-2"><span class="text-muted fw-light">Account Settings /</span> Account</h4>
            <div class="row">
              <div class="col-md-12">
                <!-- Profile Settings Card -->
                <div class="card mb-4">
                  <h5 class="card-header">Profile Details</h5>
                  
                  <hr class="my-0" />
                  <div class="card-body">
                    <form id="formAccountSettings" method="post" enctype="multipart/form-data">
                      <div class="row">
                        <?php if ($role == 2 || $role == 3) { ?>
                        <div class="mb-3 col-md-6">
                          <label for="adminName" class="form-label"><?php echo ($userType == "Admin") ? "Admin Name" : "Full Name"; ?></label>
                          <input class="form-control" type="text" id="adminName" name="adminName" value="<?php echo $row['FullName']; ?>" autofocus />
                        </div>
                        <div class="mb-3 col-md-6">
                          <label for="email" class="form-label">E-mail</label>
                          <input class="form-control" type="text" id="email" name="email" value="<?php echo $row['Email']; ?>" placeholder="john.doe@example.com" />
                        </div>
                        <div class="mb-3 col-md-6">
                          <label class="form-label" for="phoneNumber">Phone Number</label>
                          <div class="input-group input-group-merge">
                            <span class="input-group-text">MY (+6)</span>
                            <input type="text" id="phoneNumber" name="phoneNumber" class="form-control" value="<?php echo $row['NumPhone']; ?>" placeholder="1234567890" />
                          </div>
                        </div>
                        <div class="mb-3 col-md-6">
                          <label for="password" class="form-label">Password</label>
                          <input class="form-control" type="password" id="password" name="password" value="<?php echo $row['Password']; ?>" />
                        </div>
                        <?php if (!empty($row['UserQR'])) { ?>
                        <div class="mb-3 col-md-6">
                          <label for="qr" class="form-label">QR Code</label>
                          <img style="height: 100px; width: 100px;" src="data:image/png;base64, <?php echo $row['UserQR']; ?>" alt="QR Code">
                        </div>
                        <?php } ?>
                        <?php } else if ($role == 1){ ?>
                        <div class="mb-3 col-md-6">
                          <label for="vendorName" class="form-label">Vendor Name</label>
                          <input class="form-control" type="text" id="vendorName" name="vendorName" value="<?php echo $row['VendorName']; ?>" autofocus />
                        </div>
                        <div class="mb-3 col-md-6">
                          <label for="email" class="form-label">E-mail</label>
                          <input class="form-control" type="text" id="email" name="email" value="<?php echo $row['VendorEmail']; ?>" placeholder="john.doe@example.com" />
                        </div>
                        <div class="mb-3 col-md-6">
                          <label class="form-label" for="phoneNumber">Phone Number</label>
                          <div class="input-group input-group-merge">
                            <span class="input-group-text">MY (+6)</span>
                            <input type="text" id="phoneNumber" name="phoneNumber" class="form-control" value="<?php echo $row['VendorNum']; ?>" placeholder="1234567890" />
                          </div>
                        </div>
                        <div class="mb-3 col-md-6">
                          <label for="password" class="form-label">Password</label>
                          <input class="form-control" type="password" id="password" name="password" value="<?php echo $row['VendorPassword']; ?>" />
                        </div>
                        <?php if (!empty($row['VendorQR'])) { ?>
                        <div class="mb-3 col-md-6">
                          <label for="qr" class="form-label">QR Code</label>
                          <img style="height: 100px; width: 100px;" src="data:image/png;base64, <?php echo $row['VendorQR']; ?>" alt="QR Code">
                        </div>
                        <?php } ?>
                        <?php } ?>
                      </div>
                      <div class="mt-2">
                        <button id="editBtn" name="editBtn" type="button" class="btn btn-primary me-2">Save changes</button>
                        <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                      </div>
                    </form>
                  </div>
                </div>
                <!-- Kiosk Management Card (for Vendor only) -->
                <?php if ($userType == "Vendor") { ?>
                <div class="card mb-4">
                  <h5 class="card-header">Kiosk Management</h5>
                  <div class="card-body">
                    <?php
                    $vendorID = $uid;
                    $vendor = mysqli_fetch_assoc(mysqli_query($conn, "SELECT KioskID, ApprovalStatus FROM vendor WHERE VendorID = '$vendorID'"));
                    $kioskID = $vendor['KioskID'];
                    $approvalStatus = $vendor['ApprovalStatus'];

                    if ($approvalStatus == 'Pending') {
                      echo '<div class="alert alert-info">Your account is pending admin approval. You cannot request a kiosk yet.</div>';
                    } elseif ($approvalStatus == 'Approved') {
                      // Show request form
                      $availableKiosks = mysqli_query($conn, "SELECT KioskID, KioskNum FROM kiosk WHERE OperationStatus = 'Available'");
                      ?>
                      <form method="post">
                        <div class="mb-3">
                          <label class="form-label">Request Kiosk</label>
                          <select class="form-control" name="requestKioskID" required>
                            <option value="">-- Select Available Kiosk --</option>
                            <?php while ($k = mysqli_fetch_assoc($availableKiosks)) { ?>
                              <option value="<?php echo $k['KioskID']; ?>"><?php echo htmlspecialchars($k['KioskNum']); ?></option>
                            <?php } ?>
                          </select>
                        </div>
                        <button type="submit" name="requestKioskBtn" class="btn btn-success">Request Kiosk</button>
                      </form>
                      <?php
                    } elseif ($approvalStatus == 'Requested' && $kioskID) {
                      // Show request status in a table
                      $kiosk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT KioskNum FROM kiosk WHERE KioskID = '$kioskID'"));
                      ?>
                      <h6>Your Kiosk Request</h6>
                      <table class="table">
                        <thead>
                          <tr>
                            <th>Kiosk Number</th>
                            <th>Approval Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><?php echo htmlspecialchars($kiosk['KioskNum']); ?></td>
                            <td><?php echo htmlspecialchars($approvalStatus); ?></td>
                          </tr>
                        </tbody>
                      </table>
                      <?php
                    } elseif ($approvalStatus == 'Assigned' && $kioskID) {
                      $kiosk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kiosk WHERE KioskID = '$kioskID'"));
                      ?>
                      <h6>Assigned Kiosk Details</h6>
                      <form method="post" style="display:inline;">
                        <div class="mb-3">
                          <label class="form-label">Kiosk Number</label>
                          <input type="text" class="form-control" value="<?php echo htmlspecialchars($kiosk['KioskNum']); ?>" readonly>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Kiosk Name</label>
                          <input type="text" class="form-control" name="kioskNameUpdate" value="<?php echo htmlspecialchars($kiosk['KioskName']); ?>" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Operation Status</label>
                          <select class="form-control" name="kioskStatusUpdate">
                            <option value="Open" <?php if($kiosk['OperationStatus'] == 'Open') echo 'selected'; ?>>Open</option>
                            <option value="Closed" <?php if($kiosk['OperationStatus'] == 'Closed') echo 'selected'; ?>>Closed</option>
                          </select>
                        </div>
                        <button type="submit" name="updateKioskBtn" class="btn btn-primary">Update Kiosk</button>
                        <button type="submit" name="dropKioskBtn" class="btn btn-danger" onclick="return confirm('Are you sure you want to drop this kiosk?');">Drop Kiosk</button>
                      </form>
                      <?php
                    } elseif ($approvalStatus == 'Rejected' && $kioskID) {
                      $kiosk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT KioskNum FROM kiosk WHERE KioskID = '$kioskID'"));
                      ?>
                      <div class="alert alert-danger">Your kiosk request for Kiosk Number <?php echo htmlspecialchars($kiosk['KioskNum']); ?> was rejected.</div>
                      <form method="post">
                        <button type="submit" name="resetKioskRequestBtn" class="btn btn-warning">Request New Kiosk</button>
                      </form>
                      <?php
                    }
                    ?>
                  </div>
                </div>
                <?php } ?>
                <div class="card">
                  <h5 class="card-header">Delete Account</h5>
                  <div class="card-body">
                    <div class="mb-3 col-12 mb-0">
                      <div class="alert alert-warning">
                        <h6 class="alert-heading fw-bold mb-1">Are you sure you want to delete your account?</h6>
                        <p class="mb-0">Once you delete your account, there is no going back. Please be certain.</p>
                      </div>
                    </div>
                    <form id="formAccountDeactivation" method="post">
                      <div class="form-check mb-3">
                        <input class="form-check-input accountDeactivation" type="checkbox" name="accountDeactivation" id="accountDeactivation" onclick="checkMe()" />
                        <label class="form-check-label" for="accountDeactivation">I confirm my account deactivation</label>
                      </div>
                      <button name="deactiveBtn" id="deactiveBtn" type="submit" class="btn btn-danger deactiveBtn" disabled>Deactivate Account</button>
                    </form>
                  </div>
                </div>
                <script>
                  function checkMe() {
                    var checkbox = document.getElementById("accountDeactivation");
                    document.getElementById("deactiveBtn").disabled = !checkbox.checked;
                  }
                  document.addEventListener('DOMContentLoaded', function() {
                    const editBtn = document.getElementById('editBtn');
                    const form = document.getElementById('formAccountSettings');
                    if (editBtn && form) {
                      editBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        Swal.fire({
                          title: 'Enter Current Password',
                          input: 'password',
                          inputLabel: 'Current Password',
                          inputPlaceholder: 'Enter your current password',
                          inputAttributes: {
                            maxlength: 50,
                            autocapitalize: 'off',
                            autocorrect: 'off'
                          },
                          showCancelButton: true,
                          confirmButtonText: 'Confirm',
                          preConfirm: (currentPassword) => {
                            if (!currentPassword) {
                              Swal.showValidationMessage('Please enter your current password');
                            }
                            return currentPassword;
                          }
                        }).then((result) => {
                          if (result.isConfirmed && result.value) {
                            // Add hidden input with current password and submit
                            let hidden = document.createElement('input');
                            hidden.type = 'hidden';
                            hidden.name = 'currentPassword';
                            hidden.value = result.value;
                            form.appendChild(hidden);
                            form.submit(); // <-- Use this instead of editBtn.click()
                          }
                        });
                      });
                    }
                  });
                </script>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

<?php
if (isset($_POST['editBtn'])) {
    $uid = $_SESSION['User'];
    $currentPassword = isset($_POST['currentPassword']) ? $_POST['currentPassword'] : '';

    if ($userType == "Admin" || $userType == "Customer") {
        $adminName = $_POST['adminName'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['phoneNumber'];
        $newPassword = $_POST['password'];

        // Always fetch the real password from DB for comparison
        $getPass = mysqli_query($conn, "SELECT Password FROM user WHERE UserID = '$uid' LIMIT 1");
        $rowPass = mysqli_fetch_assoc($getPass);

        if ($rowPass && $currentPassword === $rowPass['Password']) {
            // Only now allow update
            $query = mysqli_query($conn, "UPDATE user SET FullName = '$adminName', Email = '$email', NumPhone = '$phoneNumber', Password = '$newPassword' WHERE UserID = '$uid'");
            if ($query) {
                echo '
                <script type="text/javascript">
                $(document).ready(function(){
                  Swal.fire({
                    title: "Profile Updated!",
                    icon: "success",
                    timer: 1500,
                    showConfirmButton: false
                  });
                  setTimeout(function(){ window.location.href="profile.php"; }, 1600);
                });
                </script>
                ';
                exit();
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
                    window.location.href="profile.php";
                  });
                });
                </script>
                ';
            }
        } else {
            echo '
            <script type="text/javascript">
            $(document).ready(function(){
              Swal.fire({
                title: "Wrong Password!",
                text: "The current password you entered is incorrect.",
                icon: "error",
                timer: 2000,
                showConfirmButton: false,
              }).then(function() {
                window.location.href="profile.php";
              });
            });
            </script>
            ';
        }
    } else if ($userType == "Vendor") {
        $vendorName = $_POST['vendorName'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['phoneNumber'];
        $newPassword = $_POST['password'];

        // Always fetch the real password from DB for comparison
        $getPass = mysqli_query($conn, "SELECT VendorPassword FROM vendor WHERE VendorID = '$uid' LIMIT 1");
        $rowPass = mysqli_fetch_assoc($getPass);

        if ($rowPass && $currentPassword === $rowPass['VendorPassword']) {
            $query = mysqli_query($conn, "UPDATE vendor SET VendorName = '$vendorName', VendorEmail = '$email', VendorNum = '$phoneNumber', VendorPassword = '$newPassword' WHERE VendorID = '$uid'");
            if ($query) {
                echo '
                <script type="text/javascript">
                $(document).ready(function(){
                  Swal.fire({
                    title: "Profile Updated!",
                    icon: "success",
                    timer: 1500,
                    showConfirmButton: false
                  });
                  setTimeout(function(){ window.location.href="profile.php"; }, 1600);
                });
                </script>
                ';
                exit();
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
                    window.location.href="profile.php";
                  });
                });
                </script>
                ';
            }
        } else {
            echo '
            <script type="text/javascript">
            $(document).ready(function(){
              Swal.fire({
                title: "Wrong Password!",
                text: "The current password you entered is incorrect.",
                icon: "error",
                timer: 2000,
                showConfirmButton: false,
              }).then(function() {
                window.location.href="profile.php";
              });
            });
            </script>
            ';
        }
    }
}

if (isset($_POST['deactiveBtn'])) {
  $uid = $_SESSION['User'];
  if ($userType == "Vendor") {
    $query = "DELETE from `vendor` WHERE `VendorID` = $uid";
  } else {
    $query = "DELETE from `user` WHERE `UserID` = $uid";
  }
  $result = mysqli_query($conn, $query);

  if ($result) {
    echo '<script type="text/javascript">
          Swal.fire({
            title: "Deleted!",
            text: "Your account has been deleted.",
            icon: "success",
            confirmButtonText: "OK"
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href="logout.php";
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
                window.location.href="profile.php";
              }
            });
          });
        </script>
        ';
  }
}

if (isset($_POST['requestKioskBtn']) && $userType == "Vendor") {
    $requestKioskID = intval($_POST['requestKioskID']);
    // Update vendor and kiosk tables
    mysqli_query($conn, "UPDATE vendor SET ApprovalStatus = 'Requested', KioskID = '$requestKioskID' WHERE VendorID = '$uid'");
    mysqli_query($conn, "UPDATE kiosk SET OperationStatus = 'Pending' WHERE KioskID = '$requestKioskID'");
    $_SESSION['kiosk_request'] = 'sent';
    echo '<script>
      Swal.fire({
        title: "Request Submitted!",
        text: "Your kiosk request has been sent.",
        icon: "success"
      }).then(function(){ window.location.href="profile.php"; });
    </script>';
    exit();
}

// Handle Update Kiosk (for Assigned)
if (isset($_POST['updateKioskBtn']) && $userType == "Vendor") {
    $vendor = mysqli_fetch_assoc(mysqli_query($conn, "SELECT KioskID FROM vendor WHERE VendorID = '$uid'"));
    $kioskID = $vendor['KioskID'];
    // Only update if kioskID is set and fields are present
    if ($kioskID && isset($_POST['kioskNameUpdate'], $_POST['kioskStatusUpdate'])) {
        $kioskName = mysqli_real_escape_string($conn, $_POST['kioskNameUpdate']);
        $kioskStatus = $_POST['kioskStatusUpdate'];
        $update = mysqli_query($conn, "UPDATE kiosk SET KioskName = '$kioskName', OperationStatus = '$kioskStatus' WHERE KioskID = '$kioskID'");
        if ($update) {
            echo '<script>
              Swal.fire({
                title: "Kiosk Updated!",
                icon: "success"
              }).then(function(){ window.location.href="profile.php"; });
            </script>';
        } else {
            echo '<script>
              Swal.fire({
                title: "Update Failed!",
                icon: "error"
              }).then(function(){ window.location.href="profile.php"; });
            </script>';
        }
        exit();
    }
}

// Handle Drop Kiosk (for Assigned)
if (isset($_POST['dropKioskBtn']) && $userType == "Vendor") {
    $vendor = mysqli_fetch_assoc(mysqli_query($conn, "SELECT KioskID FROM vendor WHERE VendorID = '$uid'"));
    $kioskID = $vendor['KioskID'];
    if ($kioskID) {
        mysqli_query($conn, "UPDATE kiosk SET OperationStatus = 'Available' WHERE KioskID = '$kioskID'");
        mysqli_query($conn, "UPDATE vendor SET ApprovalStatus = 'Approved', KioskID = NULL WHERE VendorID = '$uid'");
        echo '<script>
          Swal.fire({
            title: "Kiosk Dropped!",
            text: "You have dropped your kiosk.",
            icon: "success"
          }).then(function(){ window.location.href="profile.php"; });
        </script>';
        exit();
    }
}

// Handle Reset Kiosk Request (for Rejected)
if (isset($_POST['resetKioskRequestBtn']) && $userType == "Vendor") {
    $vendor = mysqli_fetch_assoc(mysqli_query($conn, "SELECT KioskID FROM vendor WHERE VendorID = '$uid'"));
    $kioskID = $vendor['KioskID'];
    if ($kioskID) {
        // Set the rejected kiosk back to available
        mysqli_query($conn, "UPDATE kiosk SET OperationStatus = 'Available' WHERE KioskID = '$kioskID'");
        // Reset vendor status so they can request again
        mysqli_query($conn, "UPDATE vendor SET ApprovalStatus = 'Approved', KioskID = NULL WHERE VendorID = '$uid'");
        echo '<script>
          Swal.fire({
            title: "Reset Successful!",
            text: "You can now request a new kiosk.",
            icon: "success"
          }).then(function(){ window.location.href="profile.php"; });
        </script>';
        exit();
    }
}
?>
</html>