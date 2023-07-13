<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BSCO E-Commerce</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
<header>
    <div class="logo">
        <img src="images/logo.png" alt="Logo">
    </div>
    <nav>
        <ul>
            <li><a href="#home-section">     Home     </a></li>
            <li><a href="#product-section">     Products     </a></li>
            <li><a href="#contact-section">     Contact     </a></li>
        </ul>
    </nav>
    <a href="#cart-section"><img src="images/cart.png" alt="Cart"></a>
</header>

<section id="home-section">
    <div id="slider-container">
        <div class="slider">
            <img src="images/image1.jpg">
        </div>
        <div class="slider">
            <img src="images/image2.jpg">
        </div>
        <div class="slider">
            <img src="images/image3.jpg">
        </div>

    </div>
</section>

<?php
session_start();

function getProductType($productName) {
    if (strpos($productName, 'Ring') !== false) {
        return 'Ring Set';
    } else if (strpos($productName, 'Necklace') !== false) {
        return 'Necklace';
    } else if (strpos($productName, 'Accessories') !== false) {
        return 'Accessories';
    } else if (strpos($productName, 'Bracelet') !== false) {
        return 'Bracelet';
    } else if (strpos($productName, 'Sweatshirt') !== false) {
        return 'Sweatshirt';
    } else {
        return 'Unknown';
    }
}

$cartItems = isset($_SESSION['cartItems']) ? $_SESSION['cartItems'] : [];


for ($i = 1; $i <= 25; $i++) {
    $productName = 'Product ' . $i;
    $productPrice = rand(150, 250);
    $productDescription = 'A short description about this product.';
    $productImage = 'product' . $i . '.jpg';

    if ($i >= 1 && $i <= 10) {
        $productType = 'Ring Set';
    } elseif ($i >= 11 && $i <= 16) {
        $productType = 'Necklace';
    } elseif ($i >= 17 && $i <= 20) {
        $productType = 'Accessories';
    } elseif ($i == 21 || $i == 22 || $i == 23) {
        $productType = 'Bracelet';
    } elseif ($i == 24 || $i == 25) {
        $productType = 'Sweatshirt';
    }

    if ($i == 8 || $i == 9 || $i == 10 || $i == 16 || $i == 23 || $i == 25) {
        $productGender = 'MEN';
    } else {
        $productGender = 'WOMEN';
    }

    $product = [
        'name' => $productName,
        'price' => $productPrice,
        'description' => $productDescription,
        'image' => $productImage,
        'type' => $productType,
        'gender' => $productGender
    ];
    $products[] = $product;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add-to-cart'])) {
    $selectedProduct = $_POST['add-to-cart'];
    $existingItem = false;

    foreach ($_SESSION['cartItems'] as &$item) {
        if ($item['name'] === $selectedProduct) {
            $item['quantity'] += 1;
            $existingItem = true;
            break;
        }
    }

    if (!$existingItem) {
        $_SESSION['cartItems'][] = [
            'name' => $selectedProduct,
            'quantity' => 1
        ];
    }
}

function getProductByName($productName) {
    global $products;
    foreach ($products as $product) {
        if ($product['name'] === $productName) {
            return $product;
        }
    }
    return null;
}
?>

<section id="product-section">
    <h2>Products</h2>
    <?php foreach ($products as $product): ?>
        <div class="product">
            <form action="" method="POST">
                <img src="images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="100" height="150">
                <h3><?php echo $product['name']; ?></h3>
                <p class="price"><?php echo $product['price']; ?> TL</p>
                <p><?php echo $product['description']; ?></p>
                <p>Type: <?php echo $product['type']; ?></p>
                <p>Gender: <?php echo $product['gender']; ?></p>
                <div class="product-actions">
                    <input type="hidden" name="product" value="<?php echo $product['name']; ?>">
<button type="button" class="add-to-cart" data-product="<?php echo $product['name']; ?>">Add to Cart</button>
                </div>
            </form>
        </div>
    <?php endforeach; ?>
</section>


<section id="cart-section">
    <h2>Cart</h2>
    <?php if (!empty($cartItems)): ?>
        <?php
        $totalPrice = 0;
        foreach ($cartItems as $key => $item) {
            $product = getProductByName($item['name']);
            if ($product) {
                $itemPrice = $product['price'] * $item['quantity'];
                $totalPrice += $itemPrice;
                $cartItems[$key]['itemPrice'] = $itemPrice;
            }
        }
        ?>

        <?php foreach ($cartItems as $key => $item): ?>
            <?php
            $product = getProductByName($item['name']);
            if ($product):
                $itemPrice = $product['price'] * $item['quantity'];
                ?>
                <div class="cart-item">
                    <img src="images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="100" height="150">
                    <h3><?php echo $product['name']; ?></h3>
                    <form action="" method="POST">
                        <input type="hidden" name="product" value="<?php echo $item['name']; ?>">
                        <div class="cart-item-details">
                            <div class="quantity-select-container">
                                <label for="quantity-select-<?php echo $key; ?>">Quantity:</label>
                                <select id="quantity-select-<?php echo $key; ?>" class="quantity-select" name="quantity">
                                    <option value="1" <?php if ($item['quantity'] === 1) echo 'selected'; ?>>1</option>
                                    <option value="2" <?php if ($item['quantity'] === 2) echo 'selected'; ?>>2</option>
                                    <option value="3" <?php if ($item['quantity'] === 3) echo 'selected'; ?>>3</option>
                                    <option value="4" <?php if ($item['quantity'] === 4) echo 'selected'; ?>>4</option>
                                    <option value="5" <?php if ($item['quantity'] === 5) echo 'selected'; ?>>5</option>
                                </select>
                            </div>
                            <p class="item-price"><?php echo $product['price']; ?> TL x <?php echo $item['quantity']; ?> = <?php echo $itemPrice; ?> TL</p>
                            <button type="submit" name="remove-item">Delete</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <div class="cart-total">
            Total Price: <?php echo $totalPrice; ?> TL
            <button type="submit" name="buy">Buy</button>
        </div>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</section>

<section id="contact-section">
    <h2>Contact</h2>
    <p></p>
    <ul>
        <li>
            <img src="images/whatsapp.png" alt="WhatsApp" class="icon">
            <a href="http://wa.me/905439132712">WhatsApp: +90 543 913 2712</a>
        </li>
        <li>
            <img src="images/instagram.png" alt="Instagram" class="icon">
            <a href="https://instagram.com/bsco.shop?igshid=Y2IzZGU1MTFhOQ==">Instagram: @bsco.shop</a>
        </li>
        <li>
            <img src="images/email.png" alt="Email" class="icon">
            <a href="mailto:bscoshop.tr@gmail.com">Email: bscoshop.tr@gmail.com</a>
        </li>
        <li>
            <img src="images/address.png" alt="Address" class="icon">
            Address: Esentepe Mahallesi, 3022 Sokak Sarıçam/Adana
        </li>
    </ul>
</section>

<footer>
    <p>&copy; 2023 BSCO. All rights reserved.</p>
</footer>

</body>
</html>

