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
        <button class="btnadd" onclick="window.location.href='index.php?resource=create_product'">Créer un produit</button>
    <?php endif; ?>

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