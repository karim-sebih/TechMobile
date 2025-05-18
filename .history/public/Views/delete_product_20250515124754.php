<?php
session_start();

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'moderateur'])) {
    echo '<p>Accès interdit.</p>';
    exit;
}

require_once __DIR__ . '/../config/database.php';

if (isset($_GET['id'])) {
    try {
        $db = \Database::getInstance();
        $stmt = $db->prepare("DELETE FROM products WHERE product_id = :id");
        $stmt->execute(['id' => $_GET['id']]);
        header("Location: index.php?resource=admin");
        exit;
    } catch (Exception $e) {
        echo "Erreur : " . htmlspecialchars($e->getMessage());
    }
} else {
    echo "ID de produit manquant.";
}
?>