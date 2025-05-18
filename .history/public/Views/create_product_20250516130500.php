<?php
session_start();

// Vérifier si l'utilisateur est connecté et a le bon rôle
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'moderateur'])) {
    header("Location: index.php?resource=login");
    exit;
}

// Chemins relatifs ajustés
$databasePath = __DIR__ . '/../config/database.php';
if (!file_exists($databasePath)) {
    error_log("create_product.php - Erreur : database.php non trouvé à $databasePath");
    die("Erreur : database.php non trouvé");
}
require_once $databasePath;

// Charger les catégories et marques
try {
    $db = \Database::getInstance();
    $stmt = $db->prepare("SELECT category_id, category_name FROM categories ORDER BY category_name ASC");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    error_log("create_product.php - Catégories récupérées : " . json_encode($categories));

    $stmt = $db->prepare("SELECT brand_id, brand_name FROM brands ORDER BY brand_name ASC");
    $stmt->execute();
    $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);
    error_log("create_product.php - Marques récupérées : " . json_encode($brands));
} catch (Exception $e) {
    error_log("create_product.php - Erreur lors de la récupération des catégories ou marques : " . $e->getMessage());
    $categories = [];
    $brands = [];
}

// Traiter l'ajout d'un produit
$successMessage = null;
$errorMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("create_product.php - Formulaire soumis avec méthode POST");
    
    if (isset($_POST['add_product'])) {
        error_log("create_product.php - Bouton 'add_product' détecté");

        $product_name = $_POST['product_name'] ?? '';
        $price = $_POST['price'] ?? 0;
        $image_url = $_POST['image_url'] ?? '';
        $brand_id = $_POST['brand_id'] ?? null;
        $category_id = $_POST['category_id'] ?? null;
        $sku = $_POST['sku'] ?? '';
        $description = $_POST['description'] ?? '';
        $short_description = $_POST['short_description'] ?? '';
        $discount_price = $_POST['discount_price'] ?? null;
        $color = $_POST['color'] ?? '';
        $release_date = $_POST['release_date'] ?? null;
        $operating_system = $_POST['operating_system'] ?? '';
        $display_size = $_POST['display_size'] ?? null;
        $resolution = $_POST['resolution'] ?? '';
        $storage_capacity = $_POST['storage_capacity'] ?? '';
        $ram = $_POST['ram'] ?? '';
        $processor = $_POST['processor'] ?? '';
        $camera_details = $_POST['camera_details'] ?? '';
        $battery_capacity = $_POST['battery_capacity'] ?? '';
        $connectivity = $_POST['connectivity'] ?? '';
        $weight_grams = $_POST['weight_grams'] ?? null;
        $dimensions = $_POST['dimensions'] ?? '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        error_log("create_product.php - Données reçues : " . json_encode([
            'product_name' => $product_name,
            'price' => $price,
            'image_url' => $image_url,
            'brand_id' => $brand_id,
            'category_id' => $category_id,
            'sku' => $sku,
            'description' => $description,
            'short_description' => $short_description,
            'discount_price' => $discount_price,
            'color' => $color,
            'release_date' => $release_date,
            'operating_system' => $operating_system,
            'display_size' => $display_size,
            'resolution' => $resolution,
            'storage_capacity' => $storage_capacity,
            'ram' => $ram,
            'processor' => $processor,
            'camera_details' => $camera_details,
            'battery_capacity' => $battery_capacity,
            'connectivity' => $connectivity,
            'weight_grams' => $weight_grams,
            'dimensions' => $dimensions,
            'is_active' => $is_active
        ]));

        // Validation côté serveur
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
        if (!empty($discount_price) && floatval($discount_price) >= $price) {
            $errors[] = "Le prix de réduction doit être inférieur au prix normal.";
        }
        if (!empty($release_date) && !strtotime($release_date)) {
            $errors[] = "La date de sortie n'est pas valide.";
        }
        if (!empty($display_size) && !is_numeric($display_size)) {
            $errors[] = "La taille de l'écran doit être un nombre.";
        }
        if (!empty($weight_grams) && !is_numeric($weight_grams)) {
            $errors[] = "Le poids doit être un nombre.";
        }

        if (!empty($errors)) {
            $errorMessage = implode('<br>', $errors);
            error_log("create_product.php - Erreurs de validation : " . $errorMessage);
        } else {
            try {
                $stmt = $db->prepare("INSERT INTO products (product_name, image_url, brand_id, category_id, sku, description, short_description, price, discount_price, color, release_date, operating_system, display_size, resolution, storage_capacity, ram, processor, camera_details, battery_capacity, connectivity, weight_grams, dimensions, is_active, created_at, updated_at) VALUES (:product_name, :image_url, :brand_id, :category_id, :sku, :description, :short_description, :price, :discount_price, :color, :release_date, :operating_system, :display_size, :resolution, :storage_capacity, :ram, :processor, :camera_details, :battery_capacity, :connectivity, :weight_grams, :dimensions, :is_active, NOW(), NOW())");
                $stmt->execute([
                    'product_name' => $product_name,
                    'image_url' => $image_url,
                    'brand_id' => $brand_id,
                    'category_id' => $category_id,
                    'sku' => $sku,
                    'description' => $description,
                    'short_description' => $short_description,
                    'price' => $price,
                    'discount_price' => $discount_price ?: null,
                    'color' => $color,
                    'release_date' => $release_date ? date('Y-m-d', strtotime($release_date)) : null,
                    'operating_system' => $operating_system,
                    'display_size' => $display_size ? floatval($display_size) : null,
                    'resolution' => $resolution,
                    'storage_capacity' => $storage_capacity,
                    'ram' => $ram,
                    'processor' => $processor,
                    'camera_details' => $camera_details,
                    'battery_capacity' => $battery_capacity,
                    'connectivity' => $connectivity,
                    'weight_grams' => $weight_grams ? floatval($weight_grams) : null,
                    'dimensions' => $dimensions,
                    'is_active' => $is_active
                ]);
                error_log("create_product.php - Produit ajouté avec succès : " . $product_name);
                $successMessage = "Produit ajouté avec succès !";
                header("Location: index.php?resource=admin");
                exit;
            } catch (Exception $e) {
                error_log("create_product.php - Erreur lors de l'ajout du produit : " . $e->getMessage());
                $errorMessage = "Erreur lors de l'ajout : " . $e->getMessage();
            }
        }
    } else {
        error_log("create_product.php - Bouton 'add_product' non détecté dans le formulaire");
    }
} else {
    error_log("create_product.php - Requête non POST");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un produit - TechMobile</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Ajuste le chemin selon ton projet -->
</head>
<body>
    <div class="content">
        <h1>Créer un nouveau produit</h1>

        <?php if (isset($successMessage)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($successMessage); ?></p>
        <?php endif; ?>
        <?php if (isset($errorMessage)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" action="index.php?resource=create_product">
            <div class="form-group">
                <label for="product_name">Nom :</label>
                <input type="text" id="product_name" name="product_name" required>
            </div>
            <div class="form-group">
                <label for="price">Prix :</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="image_url">URL de l'image :</label>
                <input type="text" id="image_url" name="image_url">
            </div>
            <div class="form-group">
                <label for="brand_id">Marque :</label>
                <select id="brand_id" name="brand_id" required>
                    <option value="">Sélectionner une marque</option>
                    <?php foreach ($brands as $brand): ?>
                        <option value="<?php echo htmlspecialchars($brand['brand_id']); ?>">
                            <?php echo htmlspecialchars($brand['brand_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="category_id">Catégorie :</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Sélectionner une catégorie</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['category_id']); ?>">
                            <?php echo htmlspecialchars($category['category_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="sku">SKU :</label>
                <input type="text" id="sku" name="sku" required>
            </div>
            <div class="form-group">
                <label for="description">Description :</label>
                <textarea id="description" name="description"></textarea>
            </div>
            <div class="form-group">
                <label for="short_description">Description courte :</label>
                <input type="text" id="short_description" name="short_description">
            </div>
            <div class="form-group">
                <label for="discount_price">Prix de réduction :</label>
                <input type="number" id="discount_price" name="discount_price" step="0.01">
            </div>
            <div class="form-group">
                <label for="color">Couleur :</label>
                <input type="text" id="color" name="color">
            </div>
            <div class="form-group">
                <label for="release_date">Date de sortie :</label>
                <input type="date" id="release_date" name="release_date">
            </div>
            <div class="form-group">
                <label for="operating_system">Système d'exploitation :</label>
                <input type="text" id="operating_system" name="operating_system">
            </div>
            <div class="form-group">
                <label for="display_size">Taille d'écran (pouces) :</label>
                <input type="number" id="display_size" name="display_size" step="0.1">
            </div>
            <div class="form-group">
                <label for="resolution">Résolution :</label>
                <input type="text" id="resolution" name="resolution">
            </div>
            <div class="form-group">
                <label for="storage_capacity">Capacité de stockage :</label>
                <input type="text" id="storage_capacity" name="storage_capacity">
            </div>
            <div class="form-group">
                <label for="ram">RAM :</label>
                <input type="text" id="ram" name="ram">
            </div>
            <div class="form-group">
                <label for="processor">Processeur :</label>
                <input type="text" id="processor" name="processor">
            </div>
            <div class="form-group">
                <label for="camera_details">Détails caméra :</label>
                <textarea id="camera_details" name="camera_details"></textarea>
            </div>
            <div class="form-group">
                <label for="battery_capacity">Capacité batterie :</label>
                <input type="text" id="battery_capacity" name="battery_capacity">
            </div>
            <div class="form-group">
                <label for="connectivity">Connectivité :</label>
                <input type="text" id="connectivity" name="connectivity">
            </div>
            <div class="form-group">
                <label for="weight_grams">Poids (grammes) :</label>
                <input type="number" id="weight_grams" name="weight_grams" step="0.01">
            </div>
            <div class="form-group">
                <label for="dimensions">Dimensions :</label>
                <input type="text" id="dimensions" name="dimensions">
            </div>
            <div class="form-group">
                <label for="is_active">Actif :</label>
                <input type="checkbox" id="is_active" name="is_active" checked>
            </div>
            <button type="submit" name="add_product">Ajouter</button>
            <a href="index.php?resource=admin">Retour</a>
        </form>
    </div>
</body>
</html>