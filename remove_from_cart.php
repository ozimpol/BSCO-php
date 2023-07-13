<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product'])) {
    $productName = $_POST['product'];

    // Remove the product from the cart
    foreach ($_SESSION['cartItems'] as $key => $item) {
        if ($item['name'] === $productName) {
            unset($_SESSION['cartItems'][$key]);
            break;
        }
    }
    $_SESSION['cartItems'] = array_values($_SESSION['cartItems']);

    // Prepare the response
    $response = [
        'success' => true,
        'cartItems' => $_SESSION['cartItems']
    ];

    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
