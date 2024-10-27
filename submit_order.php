<?php
session_start();

// Process the order
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    // You can implement order processing logic here
    // e.g., save to database, send email, etc.

    // Clear the cart
    $_SESSION['cart'] = [];
    $message = "Thank you for your order!";
} else {
    $message = "Your cart is empty.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <title>Order Processed</title>
</head>
<body>
    <div class="container">
        <h1><?= $message ?></h1>
        <form action="index.php" method="GET">
            <button type="submit">Go Back to Main Page</button>
        </form>
    </div>
</body>
</html>
