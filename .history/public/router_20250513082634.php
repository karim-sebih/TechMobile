<?php
require_once __DIR__ . '/config/app.php';

use App\Core\Router;

$Router = new Router();
$Router->redirect();

require_once __DIR__ . '/App/Controllers/HomeController.php';
use App\Controllers\HomeController;

if (isset($_GET['resource']) && $_GET['resource'] === 'home') {
    $controller = new HomeController();
    $controller->index(); // Cette méthode doit générer le contenu de home.php
}

try {
    $db = \Database::getInstance();
    error_log("router.php - Connexion à la base de données réussie");
} catch (Exception $e) {
    error_log("router.php - Erreur de connexion : " . $e->getMessage());
    echo json_encode(['error' => 'Erreur de connexion à la base : ' . $e->getMessage()]);
    exit;
}