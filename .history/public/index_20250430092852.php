<?php
require_once dirname(__DIR__) . '/vendor/autoloader.php';

$router = new App\Core\Router();
$router->handleRequest();
