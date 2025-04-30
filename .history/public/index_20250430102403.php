<?php
require __DIR__ . '/config/app.php';

// Si aucun paramètre 'c' n'est spécifié, rediriger vers la page d'accueil
if (!isset($_GET['c'])) {
    header('Location: index.php?c=home&m=index');
    exit;
}

$router = new App\Core\Router();
$content = $router->handleRequest();
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>MVC Project</title>
        <meta charset="utf-8">
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
    
        <main>
            <?php echo $content; ?>
        </main>

        <footer>
            <p>WhiteCat © 2025</p>
        </footer>
    </body>
</html>