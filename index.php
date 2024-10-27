<?php
session_start();
include 'db.php';

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Fetch products from the database
$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle adding products to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];

    // Find the product in the database
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute([':id' => $productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Add product to the cart in session
    if ($product) {
        $itemId = $product['id'];
        if (isset($_SESSION['cart'][$itemId])) {
            $_SESSION['cart'][$itemId]['quantity'] += 1;
            $_SESSION['cart'][$itemId]['total_price'] = $_SESSION['cart'][$itemId]['quantity'] * $product['price'];
        } else {
            $_SESSION['cart'][$itemId] = [
                'name' => $product['name'],
                'quantity' => 1,
                'price' => $product['price'],
                'total_price' => $product['price']
            ];
        }
    }
}

// Handle clearing the cart
if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
}

// Prepare cart items and total
$cartItems = $_SESSION['cart'];
$total = array_reduce($cartItems, function ($carry, $item) {
    return $carry + ($item['total_price']);
}, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <title>Shopping Cart</title>
</head>
<body>
    <div class="container">
        <div class="products">
            <h1>Product List</h1>
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <h2><?= htmlspecialchars($product['name']) ?></h2>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <p>Price: $<?= htmlspecialchars(number_format($product['price'], 2)) ?></p>
                    <form action="" method="POST">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cart">
            <h2>Your Cart</h2>
            <?php if (empty($cartItems)): ?>
                <p>Your cart is empty.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($cartItems as $item): ?>
                        <li><?= htmlspecialchars($item['name']) ?> - Quantity: <?= htmlspecialchars($item['quantity']) ?> - Price: $<?= htmlspecialchars(number_format($item['total_price'], 2)) ?></li>
                    <?php endforeach; ?>
                </ul>
                <p>Total: $<?= htmlspecialchars(number_format($total, 2)) ?></p>
                <a href="checkout.php">Proceed to Checkout</a>
                <!-- Form to clear the cart -->
                <form action="" method="POST">
                    <input type="hidden" name="clear_cart" value="1">
                    <button type="submit" class="clear-cart-button">Clear Cart</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

