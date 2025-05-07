<?php

function getVendorUsername($session)
{
    global $conn;

    $sql = "SELECT VendorName FROM vendor WHERE VendorID = '$session'";
    $result = $conn->query($sql);

    $row = $result->fetch_assoc();
    return $row['VendorName'];
}

function getUsername($session)
{
    global $conn;

    $sql = "SELECT * FROM user WHERE UserID = '$session'";
    $result = $conn->query($sql);

    $row = $result->fetch_assoc();
    return $row['UserName'];
    // return $_SESSION['User'];
}

function getListKiosk()
{
    global $conn;

    $sql = "SELECT * FROM kiosk";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $arr[] = array(
            'KioskID' => trim($row['KioskID']),
            'KioskName' => trim($row['KioskName']),
            'OperationStatus' => trim($row['OperationStatus']),
            'KioskLogo' => trim($row['KioskLogo']),
            'KioskNum' => trim($row['KioskNum']),
        );
    }

    return $arr;
}

?>