<?php
spl_autoload_register(function ($class) {
    // Définir le répertoire de base (racine du projet)
    $basedir = dirname(__DIR__) . '/'; // C:\xampp\htdocs\techmania\TechMobile\

    // Vérifier si la classe appartient à l'espace de noms App
    if (strpos($class, 'App\\') === 0) {
        // Retirer "App\" du début de $class pour éviter la duplication
        $classPath = str_replace('App\\', '', $class);
        $classPath = str_replace('\\', '/', $classPath);
        $filePath = $basedir . 'App/' . $classPath . '.php';

        if (file_exists($filePath)) {
            require_once $filePath;
        } else {
            die("Impossible de charger la classe : $class<br>FICHIER ATTENDU : $filePath");
        }
    }
});