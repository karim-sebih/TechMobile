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
<?php
require __DIR__ . '/config/app.php';
$resource = $_GET['resource'] ?? 'home';
echo "<!-- Resource demandé : $resource -->"; // Débogage
switch ($resource) {
    case 'home':
        include __DIR__ . '/views/home.php';
        break;
    case 'products':
        include __DIR__ . '/views/products.php';
        break;
    case 'about':
        include __DIR__ . '/views/about.php';
        break;
    default:
        include __DIR__ . '/views/404.php';
        break;
}


