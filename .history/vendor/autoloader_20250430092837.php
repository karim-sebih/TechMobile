<?php
spl_autoload_register(function ($class) {
    $basedir = dirname(__DIR__) . '/App/';
    $classPath = str_replace('\\', '/', $class);
    $filePath = $basedir . $classPath . '.php';

    if (file_exists($filePath)) {
        require_once $filePath;
    } else {
        die("Impossible de charger la classe : $class<br>Fichier attendu : $filePath");
    }
});
