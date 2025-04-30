<?php
require __DIR__ . '/config/app.php';

$router = new App\Core\Router();
$content = $router->handleRequest();

$pageClass = isset($_GET['c']) ? strtolower($_GET['c']) : 'home';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>MVC Project</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body class="body">
<header class="header">
    <div class="container nav-bar">
        <div class="logo-section">
            <i class="fas fa-mobile-alt icon-mobile"></i>
            <h1 class="site-title">TechMobile</h1>
        </div>
        <nav class="nav-links">
            <a href="#" class="nav-link">Home</a>
            <a href="#products" class="nav-link">Products</a>
            <a href="#brands" class="nav-link">Brands</a>
            <a href="#features" class="nav-link">Features</a>
            <a href="#contact" class="nav-link">Contact</a>
        </nav>
        <div class="icons">
            <button id="search-btn" class="icon-btn"><i class="fas fa-search"></i></button>
            <button id="cart-btn" class="icon-btn cart-btn">
                <i class="fas fa-shopping-cart"></i>
                <span id="cart-count" class="cart-count">0</span>
            </button>
            <button id="mobile-menu-btn" class="icon-btn"><i class="fas fa-bars"></i></button>
        </div>
    </div>

    <div id="mobile-menu" class="mobile-menu">
        <a href="#" class="mobile-link">Home</a>
        <a href="#products" class="mobile-link">Products</a>
        <a href="#brands" class="mobile-link">Brands</a>
        <a href="#features" class="mobile-link">Features</a>
        <a href="#contact" class="mobile-link">Contact</a>
    </div>

    <div id="search-bar" class="search-bar">
        <div class="container search-container">
            <input type="text" placeholder="Search for phones, brands..." class="search-input">
            <button class="search-button"><i class="fas fa-search"></i></button>
        </div>
    </div>
</header>

<div id="cart-panel" class="cart-panel cart-closed">
    <div class="cart-header">
        <h3>Your Cart</h3>
        <button id="close-cart" class="icon-btn"><i class="fas fa-times"></i></button>
    </div>
    <div id="cart-items" class="cart-items">
        <p>Your cart is empty</p>
    </div>
    <div class="cart-footer">
        <div class="subtotal">
            <span>Subtotal:</span>
            <span id="cart-subtotal">$0.00</span>
        </div>
        <button class="checkout-btn">Proceed to Checkout</button>
    </div>
</div>
<div id="cart-overlay" class="cart-overlay"></div>

<main class="page-<?php echo htmlspecialchars($pageClass); ?>">
    <?php echo $content; ?>
</main>

<footer>
    <p>WhiteCat © 2025</p>
</footer>
<script src="./js/searchbar.js"></script>
</body>
</html>
