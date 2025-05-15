<?php
session_start();

require_once('../includes/connect.php');

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 1. Try to find user in user table (Admin or Customer)
    $queryUser = "SELECT * FROM user WHERE UserName='$username' AND Password='$password' LIMIT 1";
    $resultUser = mysqli_query($conn, $queryUser);

    if ($rowUser = mysqli_fetch_assoc($resultUser)) {
        $_SESSION['User'] = $rowUser['UserID'];

        if ($rowUser['UserType'] == "Admin") {
            $_SESSION['Role'] = 3;
            header("location:../admin/admin_dashboard.php");
            exit();
        } else if ($rowUser['UserType'] == "Customer") {
            $_SESSION['Role'] = 2;
            header("location:../user/displayKiosk.php");
            exit();
        } else {
            // Unknown user type in user table
            echo "<script>alert('Invalid Login'); window.location='../login.php';</script>";
            exit();
        }
    }

    // 2. If not found in user, try vendor table
    $queryVendor = "SELECT * FROM vendor WHERE VendorUsername='$username' AND VendorPassword='$password' LIMIT 1";
    $resultVendor = mysqli_query($conn, $queryVendor);

    if ($rowVendor = mysqli_fetch_assoc($resultVendor)) {
        if ($rowVendor['ApprovalStatus'] == "Pending") {
            echo "<script>alert('Your application is being review. Try again later.'); window.location='../login.php';</script>";
            exit();
        } else {
            $_SESSION['User'] = $rowVendor['VendorID'];
            $_SESSION['Role'] = 1;
            $_SESSION['KioskID'] = $rowVendor['KioskID'];
            header("location:../Kiosk/kiosk_dashboard.php");
            exit();
        }
    }

    // 3. Not found in either table
    echo "<script>alert('Invalid Login'); window.location='../login.php';</script>";
    exit();

} else if(isset($_POST['guest'])) {
    $_SESSION['User'] = 22;
    $_SESSION['Role'] = 2;
    header("location:../user/displayKiosk.php");
    exit();
} else {
    echo 'Not Working Now Guys';
}

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $username = $_POST['username'];
//     $password = $_POST['password'];

//     $sql = "SELECT ID, password FROM login WHERE username = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("s", $username);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($result->num_rows === 1) {
//         $user = $result->fetch_assoc();

//         if (password_verify($password, $user['password'])) {
//             $_SESSION['ID'] = $user['ID'];
//             header("Location: home.php");
//             exit();
//         } else {
//             echo "Invalid password.";
//         }
//     } else {
//         echo "User not found.";
//     }
// }
