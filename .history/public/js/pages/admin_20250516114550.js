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
    error_log("admin.php - Erreur : database.php non trouvé à $databasePath");
    die("Erreur : database.php non trouvé");
}
require_once $databasePath;

// Connexion à la base de données
try {
    $db = \Database::getInstance();
    $db->query("SELECT 1"); // Test simple
    error_log("admin.php - Connexion à la base de données réussie à " . date('Y-m-d H:i:s'));
} catch (Exception $e) {
    error_log("admin.php - Échec de la connexion à la base : " . $e->getMessage());
    die("Erreur de connexion à la base de données : " . htmlspecialchars($e->getMessage()));
}

// Charger les catégories
try {
    $stmt = $db->prepare("SELECT category_id, category_name FROM categories ORDER BY category_name ASC");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    error_log("admin.php - Catégories récupérées : " . json_encode($categories));
} catch (Exception $e) {
    error_log("admin.php - Erreur lors de la récupération des catégories : " . $e->getMessage());
    $categories = [];
}

// Traiter l'ajout d'un produit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $image_url = $_POST['image_url'] ?? '';
    $category_id = $_POST['category_id'] ?? null;
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Validation côté serveur
    $errors = [];

    // Validation du nom du produit
    if (empty($product_name) || strlen(trim($product_name)) < 3) {
        $errors[] = "Le nom du produit doit contenir au moins 3 caractères.";
    }

    // Validation du prix
    $price = floatval($price);
    if ($price <= 0) {
        $errors[] = "Le prix doit être un nombre positif.";
    }

    // Validation de l'URL de l'image (si fournie)
    if (!empty($image_url)) {
        if (!filter_var($image_url, FILTER_VALIDATE_URL) || !preg_match('/^(https?:\/\/[^\s$.?#].[^\s]*)$/', $image_url)) {
            $errors[] = "L'URL de l'image n'est pas valide.";
        }
    }

    // Validation de la catégorie
    if (empty($category_id) || !is_numeric($category_id)) {
        $errors[] = "Veuillez sélectionner une catégorie valide.";
    } else {
        // Vérifier que la catégorie existe
        $stmt = $db->prepare("SELECT COUNT(*) FROM categories WHERE category_id = :category_id");
        $stmt->execute(['category_id' => $category_id]);
        if ($stmt->fetchColumn() == 0) {
            $errors[] = "La catégorie sélectionnée n'existe pas.";
        }
    }

    // Si des erreurs sont détectées, retourner une réponse JSON avec les erreurs
    if (!empty($errors)) {
        header('Content-Type: application/json');
        echo json_encode(['error' => implode(' ', $errors)]);
        exit;
    }

    // Si aucune erreur, procéder à l'insertion
    try {
        $stmt = $db->prepare("INSERT INTO products (product_name, price, image_url, category_id, is_active, created_at) VALUES (:product_name, :price, :image_url, :category_id, :is_active, NOW())");
        $stmt->execute([
            'product_name' => $product_name,
            'price' => $price,
            'image_url' => $image_url,
            'category_id' => $category_id,
            'is_active' => $is_active
        ]);
        // Retourner une réponse JSON pour l'AJAX
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    } catch (Exception $e) {
        error_log("admin.php - Erreur lors de l'ajout du produit : " . $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Erreur lors de l\'ajout : ' . $e->getMessage()]);
        exit;
    }
}

// Charger les produits
try {
    $stmt = $db->prepare("SELECT p.*, c.category_name FROM products p LEFT JOIN categories c ON p.category_id = c.category_id WHERE p.is_active = 1 ORDER BY p.created_at DESC");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    error_log("admin.php - Produits récupérés : " . json_encode($products));
} catch (Exception $e) {
    error_log("admin.php - Erreur lors de la récupération des produits : " . $e->getMessage());
    $products = [];
}
?>

<!-- Sidebar -->
<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav-list primary-nav">
            <li class="nav-item">
                <a href="index.php?resource=admin" class="nav-link">
                    <span class="material-icons">restaurant</span>
                    <span class="nav-label">Produits</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?resource=categories" class="nav-link">
                    <span class="material-icons">category</span>
                    <span class="nav-label">Catégories</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?resource=clients" class="nav-link">
                    <span class="material-icons">people</span>
                    <span class="nav-label">Clients</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

<!-- Contenu principal -->
<div class="content">
    <h1>Tableau de bord administrateur</h1>

    <!-- Bouton Créer un produit (visible uniquement pour admin) -->
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <button class="btnadd" onclick="showPopup()">Créer un produit</button>
    <?php endif; ?>

    <!-- Popup pour créer un produit -->
    <div class="popup" id="createPopup">
        <div class="popup-content">
            <span class="close" onclick="hidePopup()">×</span>
            <form method="POST" enctype="multipart/form-data" action="index.php?resource=admin">
                <h3>Ajouter un nouveau produit</h3>
                <div class="form-group">
                    <label for="product_name">Nom :</label>
                    <input type="text" id="product_name" name="product_name" required>
                </div>
                <div class="form-group">
                    <label for="price">Prix :</label>
                    <input type="number" id="price" name="price" step="0.01" required>
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
                    <label for="image_url">URL de l'image :</label>
                    <input type="text" id="image_url" name="image_url">
                </div>
                <div class="form-group">
                    <label for="is_active">Actif :</label>
                    <input type="checkbox" id="is_active" name="is_active" checked>
                </div>
                <button type="submit" name="add_product">Ajouter</button>
            </form>
        </div>
    </div>

    <!-- Popup pour les messages de succès/erreur -->
    <div class="message-popup" id="messagePopup">
        <div class="message-popup-content">
            <span class="message-close" onclick="hideMessagePopup()">×</span>
            <p id="messageText"></p>
            <button onclick="hideMessagePopup()">OK</button>
        </div>
    </div>

    <!-- Pageloader -->
    <div class="pageloader" id="pageloader">
        <div class="loader"></div>
        <p>Chargement...</p>
    </div>

    <!-- Liste des produits -->
    <h2>Produits</h2>
    <div class="products-list">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-item">
                    <img src="<?php echo htmlspecialchars($product['image_url'] ?: 'https://via.placeholder.com/150'); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="product-image">
                    <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                    <p>Catégorie : <?php echo htmlspecialchars($product['category_name'] ?: 'Non catégorisé'); ?></p>
                    <p>Prix : <?php echo htmlspecialchars($product['price']); ?> €</p>
                    <p>Statut : <?php echo $product['is_active'] ? 'Actif' : 'Inactif'; ?></p>
                    <button onclick="window.location.href='index.php?resource=edit_product&id=<?php echo $product['product_id']; ?>'">Modifier</button>
                    <button onclick="if(confirm('Supprimer ce produit ?')) window.location.href='index.php?resource=delete_product&id=<?php echo $product['product_id']; ?>'">Supprimer</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun produit disponible.</p>
        <?php endif; ?>
    </div>
</div>

<script src="admin.js"></script>