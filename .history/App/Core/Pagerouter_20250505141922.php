<?php
namespace App\Core;

final class PageRouter
{
    public function handleRequest()
    {
        $controllerName = isset($_GET['c']) ? ucfirst($_GET['c']) . 'Controller' : 'HomeController';
        $methodName = isset($_GET['m']) ? $_GET['m'] : 'index';

        $controllerClass = "\\App\\Controllers\\$controllerName";

        if (!class_exists($controllerClass)) {
            return '<p>Erreur 404 : Contrôleur introuvable.</p>';
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $methodName)) {
            return '<p>Erreur 404 : Méthode introuvable.</p>';
        }

        return $controller->$methodName();
    }
}