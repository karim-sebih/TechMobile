<?php
session_start();

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'moderateur'])) {
    echo '<p>Accès interdit.</p>';
    exit;
}

require_once __DIR__ . '/../config/database.php';

$product = null;
if (isset($_GET['id'])) {
    $db = \Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM products WHERE product_id = :id");
    $stmt->execute(['id' => $_GET['id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    try {
        $db = \Database::getInstance();
        $stmt = $db->prepare("UPDATE products SET product_name = :product_name, price = :price, image_url = :image_url, is_active = :is_active WHERE product_id = :id");
        $stmt->execute([
            'product_name' => $product_name,
            'price' => $price,
            'image_url' => $image_url,
            'is_active' => $is_active,
            'id' => $id
        ]);
        header("Location: index.php?resource=admin");
        exit;
    } catch (Exception $e) {
        echo "Erreur : " . htmlspecialchars($e->getMessage());
    }
}
?>

<h1>Modifier un produit</h1>
<?php if ($product): ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $product['product_id']; ?>">
        <label>Nom : <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required></label><br>
        <label>Prix : <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required></label><br>
        <label>URL de l'image : <input type="text" name="image_url" value="<?php echo htmlspecialchars($product['image_url']); ?>"></label><br>
        <label>Actif : <input type="checkbox" name="is_active" <?php echo $product['is_active'] ? 'checked' : ''; ?>></label><br>
        <button type="submit">Mettre à jour</button>
    </form>
<?php else: ?>
    <p>Produit non trouvé.</p>
<?php endif; ?>