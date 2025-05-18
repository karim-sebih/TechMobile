<?php
session_start(); // Initialiser la session

require_once __DIR__ . '/config/app.php';

use App\Core\Router;

// Instancier le routeur
$Router = new Router();

// Récupérer la ressource demandée
$resource = $_GET['resource'] ?? 'home';

// Définir le chemin des vues
$viewPath = __DIR__ . '/views/' . $resource . '.php';

// Fonction pour retourner une réponse JSON
function sendJsonResponse($data, $statusCode = 200) {
    header('Content-Type: application/json', true, $statusCode);
    echo json_encode($data);
    exit;
}

// Gérer les différentes routes
switch ($resource) {
    case 'session':
        // Route pour vérifier l'état de la session
        $response = [
            'isLoggedIn' => isset($_SESSION['user_id']),
            'role' => $_SESSION['user_role'] ?? null
        ];
        sendJsonResponse($response);
        break;

    case 'home':
    case 'about':
        // Pages publiques, pas de restriction
        if (file_exists($viewPath)) {
            ob_start();
            include $viewPath;
            $content = ob_get_clean();
            sendJsonResponse([
                'content' => $content,
                'title' => ucfirst($resource) . ' - TechMobile'
            ]);
        } else {
            sendJsonResponse([
                'error' => 'Page non trouvée',
                'title' => '404 - Page non trouvée'
            ], 404);
        }
        break;

    case 'login':
        // Page de connexion
        if (isset($_SESSION['user_id']) && in_array($_SESSION['user_role'], ['admin', 'moderateur'])) {
            // Si déjà connecté, rediriger vers admin
            header("Location: index.php?resource=admin");
            exit;
        }
        if (file_exists($viewPath)) {
            ob_start();
            include $viewPath;
            $content = ob_get_clean();
            sendJsonResponse([
                'content' => $content,
                'title' => 'Connexion - TechMobile'
            ]);
        } else {
            sendJsonResponse([
                'error' => 'Page non trouvée',
                'title' => '404 - Page non trouvée'
            ], 404);
        }
        break;

    case 'logout':
        // Déconnexion
        session_unset();
        session_destroy();
        header("Location: index.php?resource=home");
        exit;
        break;

    case 'admin':
    case 'edit_product':
    case 'delete_product':
        // Pages protégées : nécessitent un rôle admin ou moderateur
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'moderateur'])) {
            header("Location: index.php?resource=login");
            exit;
        }
        if (file_exists($viewPath)) {
            ob_start();
            include $viewPath;
            $content = ob_get_clean();
            sendJsonResponse([
                'content' => $content,
                'title' => ucfirst($resource) . ' - TechMobile'
            ]);
        } else {
            sendJsonResponse([
                'error' => 'Page non trouvée',
                'title' => '404 - Page non trouvée'
            ], 404);
        }
        break;



    default:
        // Si la ressource n'existe pas, retourner une erreur 404
        sendJsonResponse([
            'error' => 'Page non trouvée',
            'title' => '404 - Page non trouvée'
        ], 404);
        break;
}