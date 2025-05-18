<?php

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Charger les dépendances
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../../vendor/autoload.php'; // Si tu utilises Composer

// Charger les modèles
require_once __DIR__ . '/../Models/ProductComponentItems.php';

// Charger les contrôleurs
require_once __DIR__ . '/../Controllers/HomeController.php';
require_once __DIR__ . '/../Controllers/ProductsController.php';

// Charger le routeur
require_once __DIR__ . '/../Core/Router.php';

error_log("app.php - Fichier de configuration chargé avec succès");