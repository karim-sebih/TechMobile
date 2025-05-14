<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../App/Models/Category.php';

$categoryModel = new Category();
$categories = $categoryModel->getAllCategories();
$subcategories = $categoryModel->getAllSubcategories();
?>

<main>

    <?php include '../adminHeader.php'; ?>
    <?php include '../sidebar.php'; ?>
    <h2>Gestion des Catégories</h2>
    <button onclick="document.getElementById('addCategoryModal').style.display='block'">Ajouter une catégorie</button>
    <div class="categories-table">
        <?php foreach ($categories as $category): ?>
            <div class="category-card">
                <span><?php echo htmlspecialchars($category['name']); ?></span>
                <!-- Ajouter lien pour supprimer/modifier -->
            </div>
        <?php endforeach; ?>
    </div>
    <div id="addCategoryModal" style="display:none;">
        <form action="index.php?resource=admin/addCategory" method="POST">
            <label>Nom: <input type="text" name="name" required></label>
            <button type="submit">Ajouter</button>
            <button type="button" onclick="document.getElementById('addCategoryModal').style.display='none'">Annuler</button>
        </form>
    </div>
        </main>
