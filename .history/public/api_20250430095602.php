<?php
require __DIR__ . '/config/app.php';

// Instancier le routeur et récupérer le contenu
$router = new App\Core\Router();
$content = $router->handleRequest();

// Retourner uniquement le contenu de la vue (sans layout)
echo $content;