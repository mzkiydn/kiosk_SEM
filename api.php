<?php
session_start();
include('includes/connect.php');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
//     // Code to handle GET request   

// }

//Get Menu List
if ($_GET['getMenu']) {

    $vendorID = $_POST['test'];

    $sql = "SELECT * from menu WHERE KioskID= $vendorID";
    $result = $conn->query($sql);
    $result = $result->fetch_all(MYSQLI_ASSOC);


    foreach ($result as $row) {
        $itemName[] = $row['ItemName'];
        $Stock[] = $row['Stock'];
        $totalMenu = count($itemName);
    }

    echo json_encode([
        "itemName" => $itemName,
        "StockValue" => $Stock,
        "totalMenu" => $totalMenu,
    ],JSON_NUMERIC_CHECK );
    die;
}

//Get Sales
if ($_GET['getSales']) {

    $vendorID = $_POST['test'];

    $sql = "SELECT SUM(OrderTotalPrice) SumTotalPrice, DATE_FORMAT(OrderDate, '%M') OrderMonth from onlineorder WHERE onlineorder.KioskID= $vendorID GROUP BY MONTH(OrderDate)";
    $result = $conn->query($sql);
    $result = $result->fetch_all(MYSQLI_ASSOC);

    $totalSales = 0;

    foreach ($result as $row) {
        $OrderTotal[] = $row['SumTotalPrice'];
        $OrderDate[] = $row['OrderMonth'];
        $totalSales = array_sum($OrderTotal);
    }

    echo json_encode([
        "OrderTotal" => $OrderTotal,
        "OrderDate" => $OrderDate,
        "totalSales" => "RM " . $totalSales,
    ],JSON_NUMERIC_CHECK );
    die;
}

//Get Sales
if ($_GET['getIPSales']) {

    $vendorID = $_POST['test'];

    $sql = "SELECT SUM(InPurchaseTotalPrice) SumTotalPrice, DATE_FORMAT(InPurchaseDate, '%M') OrderMonth from inpurchaseorder WHERE inpurchaseorder.KioskID= $vendorID GROUP BY MONTH(InPurchaseDate)";
    $result = $conn->query($sql);
    $result = $result->fetch_all(MYSQLI_ASSOC);

    $totalSales = 0;

    foreach ($result as $row) {
        $OrderTotal[] = $row['SumTotalPrice'];
        $OrderDate[] = $row['OrderMonth'];
        $totalSales = array_sum($OrderTotal);
    }

    echo json_encode([
        "OrderTotal" => $OrderTotal,
        "OrderDate" => $OrderDate,
        "totalSales" => "RM " . $totalSales,
    ],JSON_NUMERIC_CHECK );
    die;
}

//Get Online Order
if ($_GET['getOrder']) {

    $OrderID = $_POST['test'];

    $sql = "SELECT * FROM onlineorder INNER JOIN orderlist ON onlineorder.OrderID = orderlist.OrderID INNER JOIN menu ON orderlist.MenuID = menu.MenuID INNER JOIN user ON onlineorder.UserID = user.UserID WHERE onlineorder.OrderID = $OrderID";
    $result = $conn->query($sql);
    $result = $result->fetch_all(MYSQLI_ASSOC);


    foreach ($result as $row) {
        $ItemName[] = $row['ItemName'];
        $CustomerName[] = $row['FullName'];
        $OrderTime[] = $row['OrderTime'];
        $Quantity[] = $row['Quantity'];
        $OrderTotalAmount[] = $row['OrderTotalPrice'];
        $orderStatus[] = $row['OrderStatus'];
    }

    echo json_encode([
        "ItemName" => $ItemName,
        "CustomerName" => $CustomerName,
        "OrderTime" => $OrderTime,
        "Quantity" => $Quantity,
        "OrderTotalAmount" => $OrderTotalAmount,
        "orderStatus" => $orderStatus,
    ],JSON_NUMERIC_CHECK );
    die;
}

//Get IP Order
if ($_GET['getIPOrder']) {

    $OrderID = $_POST['test'];

    $sql = "SELECT * FROM inpurchaseorder INNER JOIN inpurchaselist ON inpurchaseorder.InPurchaseID = inpurchaselist.InPurchaseID INNER JOIN user ON inpurchaseorder.UserID = user.UserID WHERE inpurchaselist.InPurchaseID = $OrderID";
    $result = $conn->query($sql);
    $result = $result->fetch_all(MYSQLI_ASSOC);


    foreach ($result as $row) {
        $ItemName[] = $row['ItemName'];
        $CustomerName[] = $row['FullName'];
        $OrderTime[] = $row['InPurchaseTime'];
        $Quantity[] = $row['Quantity'];
        $OrderTotalAmount[] = $row['InPurchaseTotalPrice'];
    }

    echo json_encode([
        "ItemName" => $ItemName,
        "CustomerName" => $CustomerName,
        "OrderTime" => $OrderTime,
        "Quantity" => $Quantity,
        "OrderTotalAmount" => $OrderTotalAmount,
    ],JSON_NUMERIC_CHECK );
    die;
}

// Approve vendor
if ($_GET['postVendorStatus']) {
    $vendorID = $_POST['test'];
    $dateNow = date("Y-m-d");

    $query = mysqli_query($conn, "UPDATE vendor SET ApprovalStatus = 'Approved', ApprovalDate = '$dateNow' WHERE VendorID = '$vendorID'");

    die;
}
