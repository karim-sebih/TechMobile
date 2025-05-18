<?php
require_once __DIR__ . '/../App/Core/Router.php';

$router = new \App\Core\Router();

$router->get('home', ['App\Controllers\HomeController', 'index']);
$router->get('products', ['App\Controllers\ProductsController', 'index']);
$router->get('about', ['App\Controllers\HomeController', 'about']); // Ajoute si tu fais une page "À propos"

$router->dispatch();