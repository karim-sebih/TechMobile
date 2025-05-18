<?php
// Activer l'affichage des erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté et a le bon rôle
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'moderateur'])) {
    echo json_encode(['error' => 'Utilisateur non connecté ou rôle insuffisant']);
    exit;
}

// Chemins relatifs ajustés
$databasePath = __DIR__ . '/../config/database.php';
if (!file_exists($databasePath)) {
    echo json_encode(['error' => 'Erreur : database.php non trouvé']);
    exit;
}
require_once $databasePath;

// Traiter l'ajout d'un produit uniquement si POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $db = \Database::getInstance();

    $product_name = $_POST['product_name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $image_url = $_POST['image_url'] ?? '';
    $brand_id = $_POST['brand_id'] ?? null;
    $category_id = $_POST['category_id'] ?? null;
    $sku = $_POST['sku'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $errors = [];

    if (empty($product_name) || strlen(trim($product_name)) < 3) {
        $errors[] = "Le nom du produit doit contenir au moins 3 caractères.";
    }
    $price = floatval($price);
    if ($price <= 0) {
        $errors[] = "Le prix doit être un nombre positif.";
    }
    if (!empty($image_url) && (!filter_var($image_url, FILTER_VALIDATE_URL) || !preg_match('/^(https?:\/\/[^\s$.?#].[^\s]*)$/', $image_url))) {
        $errors[] = "L'URL de l'image n'est pas valide.";
    }
    if (empty($brand_id) || !is_numeric($brand_id)) {
        $errors[] = "Veuillez sélectionner une marque valide.";
    } else {
        $stmt = $db->prepare("SELECT COUNT(*) FROM brands WHERE brand_id = :brand_id");
        $stmt->execute(['brand_id' => $brand_id]);
        if ($stmt->fetchColumn() == 0) {
            $errors[] = "La marque sélectionnée n'existe pas.";
        }
    }
    if (empty($category_id) || !is_numeric($category_id)) {
        $errors[] = "Veuillez sélectionner une catégorie valide.";
    } else {
        $stmt = $db->prepare("SELECT COUNT(*) FROM categories WHERE category_id = :category_id");
        $stmt->execute(['category_id' => $category_id]);
        if ($stmt->fetchColumn() == 0) {
            $errors[] = "La catégorie sélectionnée n'existe pas.";
        }
    }
    if (empty($sku)) {
        $errors[] = "Le SKU est requis.";
    } else {
        $stmt = $db->prepare("SELECT COUNT(*) FROM products WHERE sku = :sku");
        $stmt->execute(['sku' => $sku]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Le SKU existe déjà.";
        }
    }

    if (!empty($errors)) {
        echo json_encode(['error' => implode('<br>', $errors)]);
        exit;
    }

    try {
        $stmt = $db->prepare("INSERT INTO products (product_name, image_url, brand_id, category_id, sku, price, is_active, created_at, updated_at) VALUES (:product_name, :image_url, :brand_id, :category_id, :sku, :price, :is_active, NOW(), NOW())");
        $stmt->execute([
            'product_name' => $product_name,
            'image_url' => $image_url,
            'brand_id' => $brand_id,
            'category_id' => $category_id,
            'sku' => $sku,
            'price' => $price,
            'is_active' => $is_active
        ]);

        $stmt = $db->prepare("SELECT * FROM products WHERE sku = :sku");
        $stmt->execute(['sku' => $sku]);
        $insertedProduct = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['success' => 'Produit ajouté avec succès !', 'product' => $insertedProduct]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['error' => 'Erreur lors de l\'ajout du produit : ' . $e->getMessage()]);
        exit;
    }
}

// Si GET, retourner le formulaire
$db = \Database::getInstance();
$categories = $db->query("SELECT category_id, category_name FROM categories ORDER BY category_name ASC")->fetchAll(PDO::FETCH_ASSOC);
$brands = $db->query("SELECT brand_id, brand_name FROM brands ORDER BY brand_name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Ajout d'un log pour confirmer que le formulaire est généré
error_log('Formulaire create_product généré pour affichage');

echo json_encode([
    'content' => '
        <div class="content">
            <h1 class="text-2xl font-bold mb-4">Créer un nouveau produit</h1>
            <form id="create-product-form" method="POST" action="index.php?resource=create_product&action=add_product">
                <div class="form-group">
                    <label for="product_name">Nom :</label>
                    <input type="text" id="product_name" name="product_name" value="Test Direct" required>
                </div>
                <div class="form-group">
                    <label for="price">Prix :</label>
                    <input type="number" id="price" name="price" value="29.99" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="image_url">URL de l\'image :</label>
                    <input type="text" id="image_url" name="image_url" value="https://example.com/test.jpg">
                </div>
                <div class="form-group">
                    <label for="brand_id">Marque :</label>
                    <select id="brand_id" name="brand_id" required>
                        <option value="">Sélectionner une marque</option>
                        ' . implode('', array_map(function($brand) {
                            return '<option value="' . htmlspecialchars($brand['brand_id']) . '">' . htmlspecialchars($brand['brand_name']) . '</option>';
                        }, $brands)) . '
                    </select>
                </div>
                <div class="form-group">
                    <label for="category_id">Catégorie :</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Sélectionner une catégorie</option>
                        ' . implode('', array_map(function($category) {
                            return '<option value="' . htmlspecialchars($category['category_id']) . '">' . htmlspecialchars($category['category_name']) . '</option>';
                        }, $categories)) . '
                    </select>
                </div>
                <div class="form-group">
                    <label for="sku">SKU :</label>
                    <input type="text" id="sku" name="sku" value="TEST-DIRECT-001" required>
                </div>
                <div class="form-group">
                    <label for="is_active">Actif :</label>
                    <input type="checkbox" id="is_active" name="is_active" checked>
                </div>
                <button type="submit" name="add_product">Ajouter</button>
                <a href="index.php?resource=admin">Retour</a>
            </form>
            <script>
                document.getElementById("create-product-form").addEventListener("submit", function(e) {
                    console.log("Événement submit déclenché directement depuis le formulaire");
                });
            </script>
        </div>
    ',
    'title' => 'Créer un produit - TechMobile'
]);
?>