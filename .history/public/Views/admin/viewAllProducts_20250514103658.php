<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../App/Models/Product.php';

$productModel = new Product();
$products = $productModel->getAll();
?>
    <?php include '../adminHeader.php'; ?>
    <?php include '../sidebar.php'; ?>
    <main
    <h2>Gestion des Produits</h2>
    <button onclick="document.getElementById('addProductModal').style.display='block'">Ajouter un produit</button>
    <div class="products-table">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <span><?php echo htmlspecialchars($product['name']); ?></span>
                <span>Prix: <?php echo htmlspecialchars($product['price']); ?> €</span>
                <span>Stock: <?php echo htmlspecialchars($product['stock']); ?></span>
                <a href="index.php?resource=admin/deleteProduct&id=<?php echo $product['id']; ?>">Supprimer</a>
                <!-- Ajouter un lien pour modification plus tard -->
            </div>
        <?php endforeach; ?>
    </div>
    <div id="addProductModal" style="display:none;">
        <form action="index.php?resource=admin/addProduct" method="POST">
            <label>Nom: <input type="text" name="name" required></label>
            <label>Description: <textarea name="description"></textarea></label>
            <label>Prix: <input type="number" name="price" step="0.01" required></label>
            <label>Stock: <input type="number" name="stock" required></label>
            <label>Catégorie: <input type="text" name="category_id" required></label> <!-- À améliorer avec un select -->
            <label>Sous-catégorie: <input type="text" name="subcategory_id"></label> <!-- À améliorer -->
            <button type="submit">Ajouter</button>
            <button type="button" onclick="document.getElementById('addProductModal').style.display='none'">Annuler</button>
        </form>
    </div>
        </main>