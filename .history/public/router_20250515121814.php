<?php
require_once __DIR__ . '/config/app.php';

use App\Core\Router;

// Définir l'en-tête pour une réponse JSON
header('Content-Type: application/json; charset=UTF-8');

// Récupérer la ressource demandée
$resource = $_GET['resource'] ?? 'home';

try {
    $db = \Database::getInstance();
    error_log("router.php - Connexion à la base de données réussie");

    // Gérer les différentes ressources
    switch ($resource) {
        case 'home':
            // Contenu statique pour la page d'accueil
            $response = [
                'title' => 'TechMobile - Accueil',
                'content' => '
                    <h1>Bienvenue chez TechMobile</h1>
                    <p>Découvrez notre sélection de smartphones et accessoires haut de gamme.</p>
                    <a href="index.php?resource=products" class="nav-link">Voir nos produits</a>
                '
            ];
            break;

        case 'products':
            // Récupérer les produits depuis la base de données
            $stmt = $db->query("SELECT product_id AS id, product_name AS name, price FROM products WHERE is_active = 1");
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response = $products; // PageLoader.js attend un tableau pour 'products'
            break;

        case 'about':
            // Contenu statique pour la page "À propos"
            $response = [
                'title' => 'TechMobile - À propos',
                'content' => '
                    <h1>À propos de TechMobile</h1>
                    <p>Nous sommes une boutique spécialisée dans les smartphones et accessoires high-tech.</p>
                '
            ];
            break;

        default:
            // Page 404
            $response = [
                'title' => 'TechMobile - Page non trouvée',
                'content' => '<h1>Erreur 404</h1><p>La page demandée n\'existe pas.</p>'
            ];
            break;
    }

    // Renvoyer la réponse JSON
    echo json_encode($response);
} catch (Exception $e) {
    error_log("router.php - Erreur de connexion : " . $e->getMessage());
    echo json_encode(['error' => 'Erreur de connexion à la base : ' . $e->getMessage()]);
    exit;
}