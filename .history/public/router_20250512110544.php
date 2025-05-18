<?php
require_once __DIR__ . '/config/app.php';
use App\Core\Router;

header('Content-Type: application/json');

$router = new Router();
$resource = $_GET['resource'] ?? 'home';

try {
    if ($resource === 'home') {
        $homePath = __DIR__ . '/Views/home.php';
        if (!file_exists($homePath)) {
            throw new Exception('Fichier home.php non trouvé à : ' . $homePath);
        }
        ob_start();
        include $homePath;
        $content = ob_get_clean();
        if (empty($content)) {
            throw new Exception('Contenu de home.php vide');
        }
        echo json_encode(['content' => $content, 'title' => 'Accueil']);
    } elseif ($resource === 'products') {
        $products = ProductComponentItems::findByFilters(['limit' => 4, 'order' => 'created_at DESC']);
        if (!is_array($products)) {
            throw new Exception('Résultat inattendu de findByFilters');
        }
        echo json_encode(['products' => $products]);
    } else {
        echo json_encode(['error' => 'Ressource non trouvée']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur interne : ' . $e->getMessage()]);
    error_log('Erreur dans router.php : ' . $e->getMessage());
}
?>