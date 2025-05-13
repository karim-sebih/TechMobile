<?php
require_once __DIR__ . '/config/app.php';

use App\Core\Router;

$Router = new Router();
$Router->redirect();

try {
    $db = \Database::getInstance();
    error_log("router.php - Connexion à la base de données réussie");
} catch (Exception $e) {
    error_log("router.php - Erreur de connexion : " . $e->getMessage());
    echo json_encode(['error' => 'Erreur de connexion à la base : ' . $e->getMessage()]);
    exit;
}
