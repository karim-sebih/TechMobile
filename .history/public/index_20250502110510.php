<?php
require __DIR__ . '/config/app.php';

// Rediriger vers la page d'accueil si aucun contrôleur spécifié
if (!isset($_GET['c'])) {
    header('Location: index.php?c=home&m=index');
    exit;
}

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="container nav-bar">
            <div class="logo-section">
                <i class="fas fa-mobile-alt icon-mobile"></i>
                <h1 class="site-title">TechMobile</h1>
            </div>
            <nav class="nav-links">
                <a href="index.php?c=home&m=index" class="nav-link">Accueil</a>
                <a href="index.php?c=products&m=index" class="nav-link">Produits</a>
                <a href="index.php?c=about&m=index" class="nav-link">À propos</a>
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
    </header>

    <main class="page-<?php echo htmlspecialchars($pageClass); ?>">
        <?php echo $content; ?>
    </main>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="brand-header">
                        <i class="fas fa-mobile-alt brand-icon"></i>
                        <h3 class="brand-title">TechMobile</h3>
                    </div>
                    <p class="brand-description">
                        Your trusted source for premium smartphones and accessories at competitive prices.
                    </p>
                </div>

                <div class="footer-section">
                    <h4 class="footer-title">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="index.php?c=home&m=index" class="nav-link">Home</a></li>
                        <li><a href="index.php?c=products&m=index" class="nav-link">Products</a></li>
                        <li><a href="#brands">Brands</a></li>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4 class="footer-title">Customer Service</h4>
                    <ul class="footer-links">
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Shipping Policy</a></li>
                        <li><a href="#">Return Policy</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4 class="footer-title">Payment Methods</h4>
                    <div class="payment-grid">
                        <div class="payment-method"><i class="fab fa-cc-visa"></i></div>
                        <div class="payment-method"><i class="fab fa-cc-mastercard"></i></div>
                        <div class="payment-method"><i class="fab fa-cc-amex"></i></div>
                        <div class="payment-method"><i class="fab fa-cc-paypal"></i></div>
                        <div class="payment-method"><i class="fab fa-apple-pay"></i></div>
                        <div class="payment-method"><i class="fab fa-google-pay"></i></div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2025 TechMobile. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="./js/searchbar.js"></script>
    <script type="module" src="./js/App.js"></script>
</body>
</html>