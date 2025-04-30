<?php
namespace App\Core;

class Router
{
    public function handleRequest()
    {
        $controllerName = isset($_GET['c']) ? ucfirst($_GET['c']) . 'Controller' : 'HomeController';
        $methodName = isset($_GET['m']) ? $_GET['m'] : 'index';

        $controllerClass = 'App\\Controllers\\' . $controllerName;

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();

            if (method_exists($controller, $methodName)) {
                call_user_func([$controller, $methodName]);
            } else {
                die("Méthode $methodName non trouvée dans $controllerClass");
            }
        } else {
            die("Contrôleur $controllerClass non trouvé");
        }
    }
}
