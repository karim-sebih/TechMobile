<?php

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Charger les dépendances
require_once __DIR__ . '/../../vendor/autoloader.php'; // Chemin ajusté vers la racine

// Charger 
require_once __DIR__ . '/database.php';

// Charger les modèles
require_once __DIR__ . '/App/Models/ProductComponentItems.php';

// Charger les contrôleurs
require_once __DIR__ . '/../Controllers/HomeController.php';
require_once __DIR__ . '/../Controllers/ProductsController.php';

// Charger le routeur
require_once __DIR__ . '/../Core/Router.php';

error_log("app.php - Fichier de configuration chargé avec succès");