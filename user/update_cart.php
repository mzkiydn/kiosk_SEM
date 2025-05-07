<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['item_id']) && isset($_POST['new_quantity'])) {
        $itemId = $_POST['item_id'];
        $newQuantity = $_POST['new_quantity'];

        // Update quantity in the session cart data
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $itemId) {
                $item['quantity'] = $newQuantity;
                break;
            }
        }

        // Respond with updated cart data (JSON format)
        echo json_encode($_SESSION['cart']);
        exit;
    }
}

?>