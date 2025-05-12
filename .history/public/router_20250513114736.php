<?php
namespace App\Core;

final class Router {
    public function redirect(): void {
        header('Content-Type: application/json');
        
        $method = $_SERVER['REQUEST_METHOD'];
        $resource = $_GET['resource'] ?? 'home';
        
        $controllerName = ucfirst($resource) . 'Controller';
        $controllerClass = "\\App\\Controllers\\$controllerName";
        
        if (!class_exists($controllerClass)) {
            http_response_code(404);
            echo json_encode(["error" => "Contrôleur $controllerName introuvable."]);
            exit;
        }
        
        $controller = new $controllerClass();
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        
        switch ($method) {
            case 'GET':
                $controller->index($_GET); // Appeler index directement
                break;
            
            case 'POST':
                if (method_exists($controller, 'store')) {
                    $controller->store($input);
                } else {
                    http_response_code(405);
                    echo json_encode(["error" => "Méthode store non disponible."]);
                }
                break;
            
            case 'PUT':
            case 'PATCH':
                if (method_exists($controller, 'update')) {
                    $controller->update($input);
                } else {
                    http_response_code(405);
                    echo json_encode(["error" => "Méthode update non disponible."]);
                }
                break;
            
            case 'DELETE':
                if (isset($_GET['id']) && method_exists($controller, 'destroy')) {
                    $controller->destroy((int) $_GET['id']);
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "ID requis pour suppression ou méthode destroy non disponible."]);
                }
                break;
            
            default:
                http_response_code(405);
                echo json_encode(["error" => "Méthode $method non autorisée."]);
        }
    }
}