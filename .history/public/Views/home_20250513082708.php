<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$databasePath = __DIR__ . '\..\config\database.php';
$productModelPath = __DIR__ . '\..\..\App\Models\ProductComponentItems.php';

if (!file_exists($databasePath)) die('Erreur : database.php non trouvé à ' . $databasePath);
if (!file_exists($productModelPath)) die('Erreur : ProductComponentItems.php non trouvé à ' . $productModelPath);

require_once $databasePath;
require_once $productModelPath;

use App\Models\ProductComponentItems;

try {
    ProductComponentItems::init();
    error_log("home.php - ProductComponentItems initialisé avec succès");
} catch (Exception $e) {
    error_log("home.php - Erreur lors de l'initialisation : " . $e->getMessage());
    die("<p>Erreur lors de l'initialisation : " . htmlspecialchars($e->getMessage()) . "</p>");
}

// Test de connexion
try {
    $db = \Database::getInstance();
    $stmt = $db->query("SELECT COUNT(*) FROM products");
    $count = $stmt->fetchColumn();
    error_log("home.php - Nombre de produits : " . $count);
} catch (Exception $e) {
    error_log("home.php - Erreur de connexion : " . $e->getMessage());
    die("<p>Échec de la connexion : " . htmlspecialchars($e->getMessage()) . "</p>");
}

try {
    $products = ProductComponentItems::findByFilters(['limit' => 4, 'order' => 'created_at DESC']);
    error_log("home.php - Produits : " . json_encode($products));
} catch (Exception $e) {
    error_log("home.php - Erreur lors de la récupération : " . $e->getMessage());
    $products = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
</head>
<body>
    <div class="products-display">
        <h2>Produits :</h2>
        <p>Nombre de produits trouvés : <?php echo count($products); ?></p>
        <div class="products-list">
            <?php 
            if (!empty($products)) {
                foreach ($products as $product) {
                    echo '<div class="product-item">';
                    echo '<img src="' . htmlspecialchars($product['image_url']) . '" alt="' . htmlspecialchars($product['name']) . '" class="product-image">';
                    echo '<h3>' . htmlspecialchars($product['name']) . '</h3>';
                    echo '<p>Prix : ' . htmlspecialchars($product['price']) . ' €</p>';
                    echo '<button class="add-to-cart" data-id="' . $product['id'] . '" data-name="' . htmlspecialchars($product['name']) . '" data-price="' . $product['price'] . '">Ajouter au panier</button>';
                    echo '</div>';
                }
            } else {
                echo '<p>Aucun produit disponible.</p>';
            }
            ?>
        </div>
    </div>
</body>
</html>