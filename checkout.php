<?php
session_start();
$cartItems = $_SESSION['cart'];
$total = array_reduce($cartItems, function ($carry, $item) {
    return $carry + ($item['total_price']);
}, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <title>Checkout</title>
</head>
<body>
    <div class="container">
        <h1>Checkout</h1>
        <?php if (empty($cartItems)): ?>
            <p>Your cart is empty. <a href="index.php">Go back to shop</a></p>
        <?php else: ?>
            <h2>Order Summary</h2>
            <ul>
                <?php foreach ($cartItems as $item): ?>
                    <li><?= htmlspecialchars($item['name']) ?> - Quantity: <?= htmlspecialchars($item['quantity']) ?> - Price: $<?= htmlspecialchars(number_format($item['total_price'], 2)) ?></li>
                <?php endforeach; ?>
            </ul>
            <p>Total: $<?= htmlspecialchars(number_format($total, 2)) ?></p>
            <form action="submit_order.php" method="POST">
                <input type="submit" value="Submit Order">
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
