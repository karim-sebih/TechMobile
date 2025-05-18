<?php
session_start();
$_SESSION['user_id'] = 5; // Karim (admin)
$_SESSION['user_role'] = 'admin';
// Vérifier si l'utilisateur est connecté et a le bon rôle
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'moderateur'])) {
    echo '<p>Accès interdit : vous devez être administrateur ou modérateur pour voir cette page.</p>';
    exit;
}

// Chemins relatifs ajustés
$databasePath = __DIR__ . '/../config/database.php';
if (!file_exists($databasePath)) {
    error_log("admin.php - Erreur : database.php non trouvé à $databasePath");
    die("Erreur : database.php non trouvé");
}
require_once $databasePath;

// Test de connexion à la base de données
try {
    $db = \Database::getInstance();
    $db->query("SELECT 1"); // Test simple
    error_log("admin.php - Connexion à la base de données réussie à " . date('Y-m-d H:i:s'));
} catch (Exception $e) {
    error_log("admin.php - Échec de la connexion à la base : " . $e->getMessage());
    die("Erreur de connexion à la base de données : " . htmlspecialchars($e->getMessage()));
}

// Charger les produits
try {
    $stmt = $db->prepare("SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC LIMIT 10");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    error_log("admin.php - Produits récupérés : " . json_encode($products));
} catch (Exception $e) {
    error_log("admin.php - Erreur lors de la récupération des produits : " . $e->getMessage());
    $products = [];
}

// Charger les utilisateurs
try {
    $stmt = $db->prepare("SELECT * FROM users ORDER BY created_at DESC LIMIT 10");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    error_log("admin.php - Utilisateurs récupérés : " . json_encode($users));
} catch (Exception $e) {
    error_log("admin.php - Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
    $users = [];
}
?>

<h1>Tableau de bord administrateur</h1>

<h2>Produits récents</h2>
<div class="products-list">
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
            <div class="product-item">
                <img src="<?php echo htmlspecialchars($product['image_url'] ?: 'https://via.placeholder.com/150'); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="product-image" style="max-width: 100px;"><br>
                <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
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

<h2>Utilisateurs récents</h2>
<div class="users-list">
    <?php if (!empty($users)): ?>
        <?php foreach ($users as $user): ?>
            <div class="user-item">
                <h3><?php echo htmlspecialchars($user['username']); ?></h3>
                <p>Email : <?php echo htmlspecialchars($user['email']); ?></p>
                <p>Rôle : <?php echo htmlspecialchars($user['role']); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun utilisateur trouvé.</p>
    <?php endif; ?>
</div>

<style>
.users-list, .products-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    padding: 20px;
}
.user-item, .product-item {
    border: 1px solid #ddd;
    padding: 15px;
    border-radius: 8px;
    background-color: #f9f9f9;
}
button {
    margin: 5px;
    padding: 5px 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
button:hover {
    background-color: #0056b3;
}
</style>