<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/App/Controllers/HomeController.php';

use App\Controllers\HomeController;

try {
    $db = \Database::getInstance();
    error_log("router.php - Connexion à la base de données réussie");
} catch (Exception $e) {
    error_log("router.php - Erreur de connexion : " . $e->getMessage());
    echo json_encode(['error' => 'Erreur de connexion à la base : ' . $e->getMessage()]);
    exit;
}

// Gérer les requêtes AJAX
if (isset($_GET['resource'])) {
    $controller = new HomeController();
    $resource = $_GET['resource'];

    switch ($resource) {
        case 'home':
            $controller->index();
            break;
        case 'about':
            $controller->about();
            break;
        case 'products':
            echo json_encode(['title' => 'Produits - TechMobile', 'content' => '<h2>Produits</h2><p>Liste des produits à venir...</p>']);
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Ressource non trouvée']);
            break;
    }
    exit;
}

// Si ce n'est pas une requête AJAX, rediriger vers index.php
header('Location: index.php');
exit;