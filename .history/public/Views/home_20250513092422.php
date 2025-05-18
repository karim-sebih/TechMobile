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
            <!-- Contenu existant -->
        </section>

        <div class="features">
            <!-- Contenu existant -->
        </div>

        <div class="products-display">
            <!-- Contenu existant -->
        </div>

        <div class="title"><h2>Les marques que nous vendons :</h2></div>
        <div class="brand">
            <!-- Contenu existant -->
        </div>

        <div class="contact">
            <!-- Contenu existant -->
        </div>
    </main>
</body>
</html>