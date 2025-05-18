<?php
session_start(); // Initialiser la session

require __DIR__ . '/config/app.php';

// Vérifier si l'utilisateur est connecté et a le bon rôle pour afficher le lien "Admin"
$showAdminLink = isset($_SESSION['user_id']) && in_array($_SESSION['user_role'], ['admin', 'moderateur']);
// Vérifier si l'utilisateur est connecté pour afficher "Déconnexion" au lieu de "Connexion"
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>TechMobile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="./css/index.css">
     <link rel="shortcut icon" href="./assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
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
                <a href="index.php?resource=home" class="nav-link">Accueil</a>
                <a href="index.php?resource=products" class="nav-link">Produits</a>
                <a href="index.php?resource=about" class="nav-link">À propos</a>
                <?php if ($showAdminLink): ?>
                    <a href="index.php?resource=admin" class="nav-link">Admin</a>
                <?php endif; ?>
                <a href="index.php?resource=<?php echo $isLoggedIn ? 'user_logout' : 'login'; ?>" class="nav-link">
                    <?php echo $isLoggedIn ? 'Déconnexion' : 'Connexion'; ?>
                </a>
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
        <div id="mobile-menu" class="hidden md:hidden bg-white shadow-lg">
            <ul class="flex flex-col items-center gap-4 py-4">
                <li><a href="index.php?resource=home" class="nav-link">Accueil</a></li>
                <li><a href="index.php?resource=products" class="nav-link">Produits</a></li>
                <li><a href="index.php?resource=about" class="nav-link">À propos</a></li>
                <?php if ($showAdminLink): ?>
                    <li><a href="index.php?resource=admin" class="nav-link">Admin</a></li>
                <?php endif; ?>
                <li>
                    <a href="index.php?resource=<?php echo $isLoggedIn ? 'user_logout' : 'login'; ?>" class="nav-link">
                        <?php echo $isLoggedIn ? 'Déconnexion' : 'Connexion'; ?>
                    </a>
                </li>
            </ul>
        </div>
        <div id="search-bar" class="hidden mt-2">
            <div class="search-container">
                <input type="text" id="search-input" placeholder="Rechercher un produit..." class="search-input">
                <button id="search-submit" class="search-button"><i class="fas fa-search"></i></button>
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

    <main id="BodyLine">
        <!-- Le contenu sera chargé dynamiquement par PageLoader.js -->
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
                        Votre source de confiance pour les smartphones et accessoires haut de gamme à des prix compétitifs.
                    </p>
                </div>

                <div class="footer-section">
                    <h4 class="footer-title">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="index.php?resource=home" class="nav-link">Home</a></li>
                        <li><a href="index.php?resource=products" class="nav-link">Products</a></li>
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
    <script type="module" src="./js/core/PageLoader.js"></script>
</body>
</html>