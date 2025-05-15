<?php
// Active le chargement automatique (tu peux aussi utiliser Composer plus tard)
spl_autoload_register(function ($class) {
    $paths = ['../Core/', '../Controllers/', '../Models/'];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            break;
        }
    }
});

// Lancer le routeur
$router = new Router();
$router->handleRequest();
// Lancer le contrôleur par défaut si aucune route n'est trouvée    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>