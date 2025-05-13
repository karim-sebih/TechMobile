<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/App/Controllers/HomeController.php';

use App\Controllers\HomeController;

try {
    $db = \Database::getInstance();
    error_log("router.php - Connexion à la base de données réussie");
} catch (Exception $e) {
    error_log("router.php - Erreur de connexion : " . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Erreur de connexion à la base : ' . $e->getMessage()]);
    exit;
}

$resource = $_GET['resource'] ?? 'home';
switch ($resource) {
    case 'home':
        $controller = new HomeController();
        $controller->index();
        break;
    case 'products':
        $controller = new HomeController(); // À remplacer par ProductsController si existant
        $controller->index();
        break;
    case 'about':
        $controller = new HomeController();
        $controller->about();
        break;
    default:
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Ressource non trouvée']);
        break;
}
exit;