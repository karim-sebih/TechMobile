<?php
require_once __DIR__ . '/../public/config/app.php';

use App\Core\Router;

$router = new Router();
$router->route();