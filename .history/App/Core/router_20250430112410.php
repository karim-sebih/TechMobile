<?php
namespace App\Core;

class Router
{
    public function handleRequest()
    {
        $controllerName = isset($_GET['c']) ? ucfirst($_GET['c']) . 'Controller' : 'HomeController' . 'C'; ;
        $methodName = isset($_GET['m']) ? $_GET['m'] : 'index';

        $controllerClass = 'App\\Controllers\\' . $controllerName;

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();

            if (method_exists($controller, $methodName)) {
                return call_user_func([$controller, $methodName]);
            } else {
                return "Erreur : Méthode $methodName non trouvée dans $controllerClass";
            }
        } else {
            return "Erreur : Contrôleur $controllerClass non trouvé";
        }
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if (method_exists($controller, $methodName)) {
                $result = call_user_func([$controller, $methodName]);
                var_dump($result); // Débogage
                return $result;
            } else {
                return "Erreur : Méthode $methodName non trouvée dans $controllerClass";
            }
        }
    }
}