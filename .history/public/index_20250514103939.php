<?php
require __DIR__ . '/config/app.php';

session_start(); // Ajout pour gérer la session admin

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>TechMobile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css">
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
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="index.php?resource=admin/viewAllProducts" class="nav-link">Admin</a>
                <?php endif; ?>
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
        <!-- Reste du header identique -->
    </header>

    <main id="BodyLine">
        <?php
        $resource = isset($_GET['resource']) ? $_GET['resource'] : 'home';

        if (strpos($resource, 'admin/') === 0) {
            if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
                echo "<p>Accès refusé. Connectez-vous en tant qu'admin.</p>";
            } else {
                $action = substr($resource, 6); // Supprime "admin/" pour obtenir l'action
                $viewFile = __DIR__ . '/Views/admin/' . $action . '.php';
                if (file_exists($viewFile)) {
                    require_once $viewFile;
                } else {
                    echo "<p>Page admin non trouvée.</p>";
                }
            }
        } else {
            // Chargement dynamique via PageLoader.js pour les autres ressources
            echo "<script type='module' src='./js/core/PageLoader.js'></script>";
        }
        ?>
    </main>

    <footer class="footer">
        <!-- Reste du footer identique -->
    </footer>

    <script src="./js/searchbar.js"></script>
    <script src="./js/cart.js"></script>
</body>
</html>