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