<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier les fichiers
$databasePath = __DIR__ . '\..\config\database.php';
if (!file_exists($databasePath)) {
    die('Erreur : database.php non trouvé à ' . $databasePath);
}
$productModelPath = __DIR__ . '\..\..\App\Models\ProductComponentItems.php';
if (!file_exists($productModelPath)) {
    die('Erreur : ProductComponentItems.php non trouvé à ' . $productModelPath);
}

require_once $databasePath;
require_once $productModelPath;

use App\Models\ProductComponentItems;

try {
    ProductComponentItems::init();
    error_log("home.php - ProductComponentItems initialisé avec succès");
} catch (Exception $e) {
    error_log("home.php - Erreur lors de l'initialisation : " . $e->getMessage());
    echo "<p>Erreur lors de l'initialisation : " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}

try {
    $products = ProductComponentItems::findByFilters(['limit' => 4, 'order' => 'created_at DESC']);
    error_log("home.php - Produits récents récupérés via findByFilters : " . json_encode($products));
    if (empty($products)) {
        error_log("home.php - Aucun produit récupéré par findByFilters");
        // Remplacement par un test direct
        $db = \Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM products WHERE 1=1 ORDER BY created_at DESC LIMIT 4");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("home.php - Résultats directs de la base : " . json_encode($products));
    }
} catch (Exception $e) {
    error_log("home.php - Erreur lors de la récupération des produits : " . $e->getMessage());
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
<main id="BodyLine">
    <section class="hero-gradient text-white py-section">
        <div class="container hero-flex">
            <div class="text-content">
                <h2 class="hero-title">Les Derniers Smartphones</h2>
                <p class="hero-subtitle">
                    Découvrez notre collection premium d'appareils mobiles de pointe avec les meilleurs prix et garantie.
                </p>
                <div class="button-group">
                    <button class="btn-primary">Shop Now</button>
                    <button class="btn-secondary">View Deals</button>
                </div>
            </div>
            <div class="image-content">
                <img
                    src="https://imgs.search.brave.com/5E37AR9HklxRGjsqiG8MPZD1zt8csn99eDyKzVdOp6I/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93d3cu/emRuZXQuZnIvd3At/Y29udGVudC91cGxv/YWRzL3pkbmV0LzIw/MjUvMDQvaXBob25l/LTE1LXByby1tYXgt/d2hpdGUtMS03NTB4/NDEwLndlYnA"
                    alt="Premium Smartphones"
                    class="phone-image"
                />
            </div>
        </div>
    </section>

    <div class="features">
        <div class="feature-content">
            <h2 class="title">Shop by Category</h2>
            <br>
            <div class="grid">
                <div class="card">
                    <div class="icon-wrapper indigo">
                        <i class="fas fa-mobile-alt icon indigo-color"></i>
                    </div>
                    <h3 class="card-title">Flagship Phones</h3>
                    <p class="card-desc">Premium devices</p>
                </div>
                <div class="card">
                    <div class="icon-wrapper blue">
                        <i class="fas fa-dollar-sign icon blue-color"></i>
                    </div>
                    <h3 class="card-title">Budget Phones</h3>
                    <p class="card-desc">Great value</p>
                </div>
                <div class="card">
                    <div class="icon-wrapper green">
                        <i class="fas fa-camera icon green-color"></i>
                    </div>
                    <h3 class="card-title">Camera Phones</h3>
                    <p class="card-desc">Best photography</p>
                </div>
                <div class="card">
                    <div class="icon-wrapper purple">
                        <i class="fas fa-battery-full icon purple-color"></i>
                    </div>
                    <h3 class="card-title">Long Battery Life</h3>
                    <p class="card-desc">Power that lasts</p>
                </div>
            </div>
        </div>
        <br>
    </div>

    <div class="products-display">
        <h2>Produits :</h2>
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
                
            }
            ?>
        </div>
    </div>

    <div class="title"><h2>Les marques que nous vendons :</h2></div>

    <div class="brand">
        <img src="https://download.logo.wine/logo/Apple_Inc./Apple_Inc.-Logo.wine.png" alt="Logo Apple" class="brand-logo">
        <img src="https://download.logo.wine/logo/Honor_(brand)/Honor_(brand)-Logo.wine.png" alt="Logo Honor" class="brand-logo">
        <img src="https://download.logo.wine/logo/Samsung/Samsung-Logo.wine.png" alt="Logo Samsung" class="brand-logo">
        <img src="https://download.logo.wine/logo/OnePlus/OnePlus-Logo.wine.png" alt="Logo OnePlus" class="brand-logo">
        <img src="https://download.logo.wine/logo/Redmi/Redmi-Logo.wine.png" alt="Logo Redmi" class="brand-logo">
    </div>
    
    <div class="contact">
        <!-- À remplir si nécessaire -->
    </div>
    </main>
</body>
</html>