<?php
require_once __DIR__ . '/../pub/config/app.php';

use App\Core\Router;

$router = new Router();
$router->route();