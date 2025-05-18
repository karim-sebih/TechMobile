<?php
namespace App\Core;

final class Router {
    public function redirect(): void {
        header('Content-Type: application/json');
        
        $method = $_SERVER['REQUEST_METHOD'];
        $resource = $_GET['resource'] ?? null;aa
        
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
                if (isset($_GET['id'])) {
                    $controller->show((int) $_GET['id']);
                } else {
                    $controller->index($_GET);
                }
                break;
            
            case 'POST':
                $controller->store($input);
                break;
            
            case 'PUT':
            case 'PATCH':
                $controller->update($input);
                break;
            
            case 'DELETE':
                if (isset($_GET['id'])) {
                    $controller->destroy((int) $_GET['id']);
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "ID requis pour suppression."]);
                }
                break;
            
            default:
                http_response_code(405);
                echo json_encode(["error" => "Méthode $method non autorisée."]);
        }
    }
}
}