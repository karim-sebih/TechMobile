<?php
require __DIR__ . '/config/app.php';

// Instancier le routeur et récupérer le contenu
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
                    <li><a href="?c=home&m=index" data-view="home">Accueil</a></li>
                    <li><a href="?c=products&m=index" data-view="products">Produits</a></li>
                    <li><a href="?c=about&m=index" data-view="about">À propos</a></li>
                </ul>
            </nav>
        </header>
    
        <main>
            <?php echo $content; ?>
        </main>

        <footer>
            <p>WhiteCat © 2025</p>
        </footer>
        
        <script type="module" src="../Public/js"></script>
    </body>
</html>