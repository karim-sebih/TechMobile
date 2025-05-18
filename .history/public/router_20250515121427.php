<?php
require __DIR__ . '/config/app.php'; // Inclut les configurations ou connexions à la base de données

$resource = $_GET['resource'] ?? 'home'; // Par défaut, charge 'home' si aucun resource n'est spécifié

switch ($resource) {
    case 'home':
        include __DIR__ . '/views/home.php';
        break;
    case 'products':
        include __DIR__ . '/views/products.php';
        break;
    case 'about':
        include __DIR__ . '/views/about.php';
        break;
    default:
        include __DIR__ . '/views/404.php'; // Page 404 si la resource n'existe pas
        break;
}