<?php
require_once __DIR__ . './config/app.php'; // Chemin vers le fichier de configuration
require_once __DIR__ . '/../../vendor/autoload.php'; // Chemin vers l'autoloader de Composer

use App\Core\Router;

$router = new Router();
$router->route();