<?php
// Ignorer l'avertissement de session déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté et a le bon rôle
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'moderateur'])) {
    var_dump("Utilisateur non connecté ou rôle insuffisant");
    header("Location: index.php?resource=login");
    exit;
}

// Chemins relatifs ajustés
$databasePath = __DIR__ . '/../config/database.php';
if (!file_exists($databasePath)) {
    var_dump("Erreur : database.php non trouvé à $databasePath");
    die("Erreur : database.php non trouvé");
}
require_once $databasePath;

// Charger les catégories et marques
try {
    $db = \Database::getInstance();
    var_dump("Connexion à la BDD réussie");

    $stmt = $db->prepare("SELECT category_id, category_name FROM categories ORDER BY category_name ASC");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    var_dump("Catégories récupérées", $categories);

    $stmt = $db->prepare("SELECT brand_id, brand_name FROM brands ORDER BY brand_name ASC");
    $stmt->execute();
    $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);
    var_dump("Marques récupérées", $brands);
} catch (Exception $e) {
    var_dump("Erreur lors de la récupération des catégories ou marques", $e->getMessage());
    die();
}

// Traiter l'ajout d'un produit
$successMessage = null;
$errorMessage = null;

var_dump("Méthode de requête", $_SERVER['REQUEST_METHOD']);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    var_dump("Formulaire soumis avec méthode POST");
    
    var_dump("Contenu de \$_POST", $_POST);
    
    var_dump("Bouton 'add_product' détecté");

    $product_name = $_POST['product_name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $image_url = $_POST['image_url'] ?? '';
    $brand_id = $_POST['brand_id'] ?? null;
    $category_id = $_POST['category_id'] ?? null;
    $sku = $_POST['sku'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    var_dump("Données extraites du formulaire", [
        'product_name' => $product_name,
        'price' => $price,
        'image_url' => $image_url,
        'brand_id' => $brand_id,
        'category_id' => $category_id,
        'sku' => $sku,
        'is_active' => $is_active
    ]);

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
        $brandExists = $stmt->fetchColumn();
        var_dump("Vérification marque", $brand_id, $brandExists);
        if ($brandExists == 0) {
            $errors[] = "La marque sélectionnée n'existe pas.";
        }
    }
    if (empty($category_id) || !is_numeric($category_id)) {
        $errors[] = "Veuillez sélectionner une catégorie valide.";
    } else {
        $stmt = $db->prepare("SELECT COUNT(*) FROM categories WHERE category_id = :category_id");
        $stmt->execute(['category_id' => $category_id]);
        $categoryExists = $stmt->fetchColumn();
        var_dump("Vérification catégorie", $category_id, $categoryExists);
        if ($categoryExists == 0) {
            $errors[] = "La catégorie sélectionnée n'existe pas.";
        }
    }
    if (empty($sku)) {
        $errors[] = "Le SKU est requis.";
    } else {
        $stmt = $db->prepare("SELECT COUNT(*) FROM products WHERE sku = :sku");
        $stmt->execute(['sku' => $sku]);
        $skuExists = $stmt->fetchColumn();
        var_dump("Vérification SKU", $sku, $skuExists);
        if ($skuExists > 0) {
            $errors[] = "Le SKU existe déjà.";
        }
    }

    if (!empty($errors)) {
        $errorMessage = implode('<br>', $errors);
        var_dump("Erreurs de validation", $errorMessage);
    } else {
        try {
            // Requête simplifiée pour tester l'insertion
            $stmt = $db->prepare("INSERT INTO products (product_name, image_url, brand_id, category_id, sku, price, is_active, created_at, updated_at) VALUES (:product_name, :image_url, :brand_id, :category_id, :sku, :price, :is_active, NOW(), NOW())");
            $params = [
                'product_name' => $product_name,
                'image_url' => $image_url,
                'brand_id' => $brand_id,
                'category_id' => $category_id,
                'sku' => $sku,
                'price' => $price,
                'is_active' => $is_active
            ];
            var_dump("Paramètres avant insertion", $params);
            $stmt->execute($params);
            var_dump("Produit ajouté avec succès", $product_name);

            // Vérifier si le produit est bien dans la BDD
            $stmt = $db->prepare("SELECT * FROM products WHERE sku = :sku");
            $stmt->execute(['sku' => $sku]);
            $insertedProduct = $stmt->fetch(PDO::FETCH_ASSOC);
            var_dump("Produit dans la BDD après insertion", $insertedProduct);

            $successMessage = "Produit ajouté avec succès !";
            header("Location: index.php?resource=admin");
            exit;
        } catch (Exception $e) {
            var_dump("Erreur lors de l'ajout du produit", $e->getMessage());
            $errorMessage = "Erreur lors de l'ajout du produit : " . $e->getMessage();
        }
    }
} else {
    var_dump("Requête non POST ou bouton 'add_product' non détecté");
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un produit - TechMobile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .content { max-width: 600px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input, .form-group select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background-color: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #45a049; }
        a { display: inline-block; margin-top: 10px; color: #007BFF; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="content">
        <h1 class="text-2xl font-bold mb-4">Créer un nouveau produit</h1>

        <?php if (isset($successMessage)): ?>
            <p class="text-green-500 mb-4"><?php echo htmlspecialchars($successMessage); ?></p>
        <?php endif; ?>
        <?php if (isset($errorMessage)): ?>
            <p class="text-red-500 mb-4"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="form-group">
                <label for="product_name">Nom :</label>
                <input type="text" id="product_name" name="product_name" value="Test Direct" required>
            </div>
            <div class="form-group">
                <label for="price">Prix :</label>
                <input type="number" id="price" name="price" value="29.99" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="image_url">URL de l'image :</label>
                <input type="text" id="image_url" name="image_url" value="https://example.com/test.jpg">
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
                <input type="text" id="sku" name="sku" value="TEST-DIRECT-001" required>
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