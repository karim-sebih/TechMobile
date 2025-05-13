<?php
require_once __DIR__ . '/config/app.php';
use App\Core\Router;

header('Content-Type: application/json');

$router = new Router();
$resource = $_GET['resource'] ?? 'home';

if ($resource === 'home') {
    ob_start();
    include __DIR__ . '/Views/home.php';
    $content = ob_get_clean();
    echo json_encode(['content' => $content, 'title' => 'Accueil']);
} elseif ($resource === 'products') {
    // Logique pour charger les produits (via ProductComponentItems ou autre)
    try {
        $products = ProductComponentItems::findByFilters(['limit' => 4, 'order' => 'created_at DESC']);
        echo json_encode(['content' => '', 'products' => $products]); // À adapter selon les besoins
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Ressource non trouvée']);
}
?>