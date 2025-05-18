<?php
require_once __DIR__ . '/../config/app.php'; // Chemin vers le fichier de configuration

use App\Core\Router;

$router = new Router();
$router->route();