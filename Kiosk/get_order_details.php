<?php
// Include your database connection logic here
include('../includes/connect.php');

// Check if InPurchaseID is provided
if (isset($_GET['id'])) {
    $inPurchaseID = $_GET['id'];

    // Fetch details from the database based on InPurchaseID
    $sql = "SELECT 
            DATE_FORMAT(io.InPurchaseDate, '%d/%m/%y') AS FormattedInPurchaseDate,
            io.InPurchaseTime, 
            io.InPurchaseSubtotal, 
            io.InPurchaseTotalPrice, 
            io.TotalPointsRedeemed, 
            io.TotalPointsEarned, 
            p.PaymentType,
            il.Quantity, 
            il.ItemName, 
            il.ItemsTotalAmount
        FROM inpurchaseorder io
        LEFT JOIN inpurchaselist il ON io.InPurchaseID = il.InPurchaseID
        LEFT JOIN payment p ON io.InPurchaseID = p.InPurchaseID
        WHERE io.InPurchaseID = $inPurchaseID";

    $result = $conn->query($sql);

    $query = "SELECT ItemName, Quantity, ItemsTotalAmount FROM inpurchaselist WHERE InPurchaseID = $inPurchaseID";
    $result2 = $conn->query($query);


    if ($result) {
        // Fetch the data from the result set
        $data = $result->fetch_assoc();

        $itemDetails = array();

        // Fetch data from the result set
        while ($row = $result2->fetch_assoc()) {
            $itemDetails[] = array(
                'ItemName' => $row['ItemName'],
                'Quantity' => $row['Quantity'],
                'Price' => number_format($row['ItemsTotalAmount'], 2, '.', '')
            );
        }
    
        // Organize the data into the required structure
        $response = array(
            'InPurchaseDate' => $data['FormattedInPurchaseDate'],
            'InPurchaseTime' => $data['InPurchaseTime'],
            'InPurchaseSubtotal' => number_format($data['InPurchaseSubtotal'], 2, '.', ''),
            'InPurchaseTotalPrice' => number_format($data['InPurchaseTotalPrice'], 2, '.', ''),
            'TotalPointsRedeemed' => $data['TotalPointsRedeemed'],
            'TotalPointsEarned' => $data['TotalPointsEarned'],
            'PaymentType' => $data['PaymentType'],
            'ItemDetails' => $itemDetails  // Use the array of item details
        );
    
        // Send data as JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
     else {
        // Handle query error
        echo json_encode(array('error' => $conn->error));
    }

    // Close the database connection
    $conn->close();
} else {
    // Handle error, InPurchaseID not provided
    echo json_encode(array('error' => 'InPurchaseID not provided'));
}
?>
