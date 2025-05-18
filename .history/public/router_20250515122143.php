<?php
require_once __DIR__ . '/config/app.php';

use App\Core\Router;

// Instancier le routeur
$Router = new Router();

// Récupérer la ressource demandée
$resource = $_GET['resource'] ?? 'home';

// Définir le chemin des vues
$viewPath = __DIR__ . '/views/' . $resource . '.php';

// Vérifier si le fichier existe
if (file_exists($viewPath)) {
    // Capturer le contenu HTML du fichier PHP
    ob_start();
    include $viewPath;
    $content = ob_get_clean();

    // Retourner un JSON avec le contenu
    header('Content-Type: application/json');
    echo json_encode([
        'content' => $content,
        'title' => ucfirst($resource) . ' - TechMobile'
    ]);
} else {
    // Si la ressource n'existe pas, retourner une erreur 404
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'Page non trouvée',
        'title' => '404 - Page non trouvée'
    ]);
}

exit;