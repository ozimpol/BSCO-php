<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($cartItems)) {
        // Clear the cart items
        $cartItems = [];

        // Prepare the response
        $response = [
            'success' => true,
            'message' => 'Purchase completed successfully'
        ];

        // Send the response as JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // Prepare the response
        $response = [
            'success' => false,
            'message' => 'Cart is empty'
        ];

        // Send the response as JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
?>
