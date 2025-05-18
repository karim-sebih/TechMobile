<?php
require_once __DIR__ . '/../App/config/app.php';

$router = new \App\Core\Router();

$router->get('home', ['App\Controllers\HomeController', 'index']);
$router->get('products', ['App\Controllers\ProductsController', 'index']);
$router->get('about', ['App\Controllers\HomeController', 'about']); // À créer si nécessaire

error_log("router.php - Routeur initialisé, dispatching...");
$router->dispatch();