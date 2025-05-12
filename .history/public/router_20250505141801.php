<?php
require_once __DIR__ . '/config/app.php';

use App\Core\Router;

$Router = new Router();
$Router->redirect();