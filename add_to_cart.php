<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product'])) {
    $selectedProduct = $_POST['product'];

    // Add the product to the cart
    $_SESSION['cartItems'][] = [
        'name' => $selectedProduct,
        'quantity' => 1
    ];

    // Send a success response
    echo json_encode(['success' => true, 'cartItems' => $_SESSION['cartItems']]);
}
?>
