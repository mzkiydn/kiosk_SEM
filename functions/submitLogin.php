<?php
session_start();

require_once('../includes/connect.php');

if (isset($_POST['submit'])) {

    $Admin = "Admin";

    if($_POST['userType'] == ""){

        $query = "SELECT * FROM user WHERE UserName='" . $_POST['username'] . "' AND Password='" . $_POST['password'] . "' AND UserType ='$Admin'";
        $result = mysqli_query($conn, $query);

        if ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['User'] = $row['UserID'];

            $_SESSION['Role'] = 3;
            header("location:../admin/admin_dashboard.php");
            
        } else {
            echo "<script>alert('Invalid Login'); window.location='../login.php';</script>";
        }


    }else if ($_POST['userType'] == "Vendor") {
        $query = "select * from vendor where VendorEmail='" . $_POST['username'] . "' and VendorPassword='" . $_POST['password'] . "'";
        $result = mysqli_query($conn, $query);

        if ($row = mysqli_fetch_assoc($result)) {
            if ($row['ApprovalStatus'] == "Pending") {
                echo "<script>alert('Your application is being review. Try again later.'); window.location='../login.php';</script>";
            } else {
                $_SESSION['User'] = $row['VendorID'];
                $_SESSION['Role'] = 1;
                $_SESSION['KioskID'] = $row['KioskID'];
                header("location:../Kiosk/kiosk_dashboard.php");
            }
        } else {
            echo "<script>alert('Invalid Login'); window.location='../login.php';</script>";
        }
    } else if ($_POST['userType'] == "Customer") {
        $query = "select * from user where UserName='" . $_POST['username'] . "' and Password='" . $_POST['password'] . "' and UserType='".$_POST['userType']."' ";
        $result = mysqli_query($conn, $query);

        if ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['User'] = $row['UserID'];

            if ($row['UserType'] == "Customer") {
                $_SESSION['Role'] = 2;
                header("location:../user/displayKiosk.php");
            }

        } else {
            echo "<script>alert('Invalid Login'); window.location='../login.php';</script>";
        }
    }
} else if(isset($_POST['guest'])){

    $_SESSION['User'] = 22;
    $_SESSION['Role'] = 2;
    header("location:../user/displayKiosk.php");
    
} else{
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
