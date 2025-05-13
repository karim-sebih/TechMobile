<?php
require_once __DIR__ . '/config/app.php';

use App\Core\Router;

$router = new Router();
$router->route();