<?php
require_once __DIR__ . '/config/app.php';

use App\Core\Router;

$Router = new Router();

// Récupérer le paramètre 'resource'
$resource = $_GET['resource'] ?? 'home';

try {
    $db = \Database::getInstance();
    error_log("router.php - Connexion à la base de données réussie");

    // Logique pour charger le contenu selon la ressource
    if ($resource === 'home') {
        // Inclure home.php et capturer son contenu
        ob_start();
        include __DIR__ . '/Views/home.php';
        $content = ob_get_clean();

        $response = [
            'title' => 'Accueil',
            'content' => $content
        ];
    } else {
        $response = [
            'error' => 'Ressource non trouvée : ' . htmlspecialchars($resource)
        ];
    }

    // Réponse JSON
    header('Content-Type: application/json');
    echo json_encode($response);

} catch (Exception $e) {
    error_log("router.php - Erreur de connexion : " . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Erreur de connexion à la base : ' . $e->getMessage()]);
    exit;
}