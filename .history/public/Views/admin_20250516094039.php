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

// Charger les produits
try {
    $stmt = $db->prepare("SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC");
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
        <div class="sidebar-header">
            <div class="header-logo">
                <img src="https://via.placeholder.com/46" alt="Logo">
            </div>
            <button class="toggler sidebar-toggler" onclick="toggleSidebar()">
                <span class="material-icons">chevron_left</span>
            </button>
        </div>
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
            <ul class="nav-list secondary-nav">
                <li class="nav-item">
                    <a href="index.php?resource=login" class="nav-link">
                        <span class="material-icons">account_circle</span>
                        <span class="nav-label">Login</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?resource=logout" class="nav-link">
                        <span class="material-icons">logout</span>
                        <span class="nav-label">Logout</span>
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

        <!-- Liste des produits -->
        <h2>Produits</h2>
        <div class="products-list">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-item">
                        <img src="<?php echo htmlspecialchars($product['image_url'] ?: 'https://via.placeholder.com/150'); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="product-image">
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
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('collapsed');
        }

        function showPopup() {
            document.getElementById('createPopup').style.display = 'flex';
        }

        function hidePopup() {
            document.getElementById('createPopup').style.display = 'none';
        }

        // Gérer l'ajout de produit
        document.querySelector('form[action="index.php?resource=admin"]').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            try {
                const response = await fetch('index.php?resource=admin', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    hidePopup();
                    location.reload();
                } else {
                    alert(data.error || 'Erreur lors de l\'ajout.');
                }
            } catch (err) {
                alert('Erreur : ' + err.message);
            }
        });
    </script>
</body>
</html>