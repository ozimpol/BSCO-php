<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product']) && isset($_POST['quantity'])) {
    $productName = $_POST['product'];
    $quantity = $_POST['quantity'];

    // Update the quantity of the item in the cart
    foreach ($_SESSION['cartItems'] as &$item) {
        if ($item['name'] === $productName) {
            $item['quantity'] = $quantity;
            break;
        }
    }

    // Prepare the response
    $response = [
        'success' => true,
        'cartItems' => $_SESSION['cartItems']
    ];

    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
