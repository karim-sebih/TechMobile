<?php
require __DIR__ . '/config/app.php';

$router = new App\Core\Router();
$content = $router->handleRequest();

// Déterminer la classe de la page en fonction du contrôleur
$pageClass = isset($_GET['c']) ? strtolower($_GET['c']) : 'home'; // Par défaut, c'est "home"
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>MVC Project</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/">
    </head>
    <body>
        <header>
            <h1>Bienvenue sur le Shop</h1>
            <nav>
                <ul>
                    <li><a href="index.php?c=home&m=index">Accueil</a></li>
                    <li><a href="index.php?c=products&m=index">Produits</a></li>
                    <li><a href="index.php?c=about&m=index">À propos</a></li>
                </ul>
            </nav>
        </header>
    
        <main class="page-<?php echo htmlspecialchars($pageClass); ?>">
            <?php echo $content; ?>
        </main>

        <footer>
            <p>WhiteCat © 2025</p>
        </footer>
    </body>
</html>