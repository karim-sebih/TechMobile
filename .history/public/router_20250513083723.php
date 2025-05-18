<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/App/Controllers/HomeController.php';
require_once __DIR__ . '/App/Controllers/ProductsController.php'; // À implémenter si nécessaire

use App\Controllers\HomeController;
use App\Controllers\ProductsController;

try {
    $db = \Database::getInstance();
    error_log("router.php - Connexion à la base de données réussie");
} catch (Exception $e) {
    error_log("router.php - Erreur de connexion : " . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Erreur de connexion à la base : ' . $e->getMessage()]);
    exit;
}

if (isset($_GET['resource'])) {
    $resource = $_GET['resource'];
    switch ($resource) {
        case 'home':
            $controller = new HomeController();
            $controller->index();
            break;
        case 'products':
            if (class_exists('App\Controllers\ProductsController')) {
                $controller = new ProductsController();
                $controller->index();
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Contrôleur Products non trouvé']);
            }
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
}

header('Location: index.php');
exit;