<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!file_exists(__DIR__ . '/')) {
    die('Erreur : database.php non trouvé à ' . __DIR__ . '/../config/database.php');
}
if (!file_exists(__DIR__ . '/../../App/Models/ProductComponentItems.php')) {
    die('Erreur : ProductComponentItems.php non trouvé à ' . __DIR__ . '/../../App/Models/ProductComponentItems.php');
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../../App/Models/ProductComponentItems.php';

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
    error_log("home.php - Produits récents récupérés : " . json_encode($products));
    if (empty($products)) {
        error_log("home.php - Aucun produit récupéré par findByFilters");
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
    <style>
        body, .products-display, .hero-gradient, .features, .brand, .contact {
            display: block !important;
            visibility: visible !important;
        }
    </style>
</head>
<body>
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
        <?php if (!empty($products)): ?>
            <div class="products-list">
                <?php foreach ($products as $product): ?>
                    <div class="product-item">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p>Prix : <?php echo htmlspecialchars($product['price']); ?> €</p>
                        <button class="add-to-cart" 
                                data-id="<?php echo $product['id']; ?>" 
                                data-name="<?php echo htmlspecialchars($product['name']); ?>" 
                                data-price="<?php echo $product['price']; ?>">
                            Ajouter au panier
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucun produit disponible.</p>
        <?php endif; ?>
    </div>

    <div class="title"><h2>Les marques que nous vendons :</h2></div>

    <div class="brand">
        <img src="https://download.logo.wine/logo/Apple_Inc./Apple_Inc.-Logo.wine.png" alt="Logo Apple" class="brand-logo">
        <img src="https://download.logo.wine/logo/Honor_(brand)/Honor_(brand)-Logo.wine.png" alt="Logo Honor" class="brand-logo">
        <img src="https://download.logo.wine/logo/Samsung/Samsung-Logo.wine.png" alt="Logo Samsung" class="brand-logo">
        <img src="https://download.logo.wine/logo/Redmi/Redmi-Logo.wine.png" alt="Logo Redmi" class="brand-logo">
    </div>

    <div class="contact">
        <!-- À remplir si nécessaire -->
    </div>
</body>
</html>