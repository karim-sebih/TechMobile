<?php

class Router {
    public function handleRequest() {
        $controllerName = $_GET['c'] ?? 'Home';
        $method = $_GET['m'] ?? 'index';

        $controllerClass = ucfirst($controllerName) . 'Controller';
        $controllerFile = "../Controllers/$controllerClass.php";

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new $controllerClass();

            if (method_exists($controller, $method)) {
                $controller->$method();
            } else {
                echo "Méthode '$method' inexistante.";
            }
        } else {
            echo "Contrôleur '$controllerClass' non trouvé.";
        }
    }
}
