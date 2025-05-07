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

    $sql = "SELECT * from menu";
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

// Get combined sales data from onlineorder and instorepurchaseorder
if ($_GET['getCombinedSales']) {

    $currentYear = date("Y");
    $currentMonth = date("m");
    
    $onlineOrderQuery = "SELECT SUM(OrderTotalPrice) AS TotalPrice, 'Online Order' AS Source FROM onlineorder WHERE YEAR(OrderDate) = $currentYear AND MONTH(OrderDate) = $currentMonth";
    $inPurchaseOrderQuery = "SELECT SUM(InPurchaseTotalPrice) AS TotalPrice, 'In-Store Purchase' AS Source FROM inpurchaseorder WHERE YEAR(InPurchaseDate) = $currentYear AND MONTH(InPurchaseDate) = $currentMonth";

    // Get overall total for both Online Orders and In-Store Purchases
    $overallTotalQuery = "SELECT SUM(TotalPrice) AS OverallTotal FROM (($onlineOrderQuery) UNION ALL ($inPurchaseOrderQuery)) AS combinedSales";

    $overallResult = $conn->query($overallTotalQuery);
    $overallTotal = $overallResult->fetch_assoc()["OverallTotal"];

    $combinedQuery = "($onlineOrderQuery) UNION ALL ($inPurchaseOrderQuery)";

    $result = $conn->query($combinedQuery);
    $result = $result->fetch_all(MYSQLI_ASSOC);

    $data = [];
    foreach ($result as $row) {
        $data[] = [
            "value" => $row['TotalPrice'],
            "label" => $row['Source'],
        ];
    }


    echo json_encode($data, JSON_NUMERIC_CHECK);
    die;
}

//Get Sales
if ($_GET['getSales']) {

    $sql = "SELECT SUM(OrderTotalPrice) SumTotalPrice, DATE_FORMAT(OrderDate, '%M') OrderMonth from onlineorder INNER JOIN orderlist ON onlineorder.OrderID = orderlist.OrderID INNER JOIN menu ON orderlist.MenuID = menu.MenuID GROUP BY MONTH(OrderDate)";
    $result = $conn->query($sql);
    $result = $result->fetch_all(MYSQLI_ASSOC);


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

    $sql = "SELECT SUM(InPurchaseTotalPrice) SumTotalPrice, DATE_FORMAT(InPurchaseDate, '%M') OrderMonth from inpurchaseorder  GROUP BY MONTH(InPurchaseDate)";
    $result = $conn->query($sql);
    $result = $result->fetch_all(MYSQLI_ASSOC);


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

    $sql = "SELECT * FROM onlineorder INNER JOIN orderlist ON onlineorder.OrderID = orderlist.OrderID INNER JOIN menu ON orderlist.MenuID = menu.MenuID INNER JOIN user ON onlineorder.UserID = user.UserID";
    $result = $conn->query($sql);
    $result = $result->fetch_all(MYSQLI_ASSOC);


    foreach ($result as $row) {
        $ItemName[] = $row['ItemName'];
        $CustomerName[] = $row['FullName'];
        $OrderTime[] = $row['OrderTime'];
        $Quantity[] = $row['Quantity'];
        $OrderTotalAmount[] = $row['OrderTotalAmount'];
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

if ($_GET['getVendorCount']) {
    $query = "SELECT COUNT(*) AS VendorCount FROM vendor WHERE ApprovalStatus = 'Approved'";
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        $vendorCount = $row['VendorCount'];

        echo json_encode([
            'vendorCount' => $vendorCount
        ],JSON_NUMERIC_CHECK );
        die;
    }
}

