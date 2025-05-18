<?php
require __DIR__ . '/config/app.php';

$router = new App\Core\Router();
$content = $router->handleRequest();

echo $content;