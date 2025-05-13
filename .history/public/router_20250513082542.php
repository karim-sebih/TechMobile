<?php
require_once __DIR__ . '/App/Controllers/HomeController.php';
use App\Controllers\HomeController;

if (isset($_GET['resource'])) {
    $controller = new HomeController();
    $resource = $_GET['resource'];
    if ($resource === 'home') {
        $controller->index();
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Ressource non trouvée']);
    }
} else {
    header('Location: index.php');
    exit;
}
?>