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
</head>
<body class="body">
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
        <!-- Menus mobile et autres composants peuvent rester ici -->
    </header>

    <main class="page-<?php echo htmlspecialchars($pageClass); ?>">
        <?php echo $content; ?>
    </main>

    <footer>
        <p>WhiteCat © 2025</p>
    </footer>

    <script src="./js/searchbar.js"></script>
</body>
</html>
