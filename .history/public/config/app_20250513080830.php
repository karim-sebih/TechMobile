<?php
// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Charger l'autoloader (si vous utilisez Composer)
require_once __DIR__ . '/../../vendor/autoloader.php'; // Chemin vers l'autoloader si installé via Composer

// Charger la configuration de la base de données
require_once __DIR__ . '/..'; // Ajustez selon l'emplacement réel

// Charger les modèles
require_once __DIR__ . '/../../App/Models/ProductComponentItems.php'; // Chemin corrigé

// Charger les contrôleurs
require_once __DIR__ . '/../../App/Controllers/HomeController.php';
require_once __DIR__ . '/../../App/Controllers/ProductsController.php';

// Charger le routeur
require_once __DIR__ . '/../../App/Core/Router.php';

error_log("app.php - Fichier de configuration chargé avec succès");
?>