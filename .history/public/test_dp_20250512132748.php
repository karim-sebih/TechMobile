<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . './config/database.php'; // Chemin corrigé

try {
    $db = \Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM products WHERE 1=1 ORDER BY created_at DESC LIMIT 4");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Connexion réussie ! Produits : <pre>" . print_r($result, true) . "</pre>";
} catch (Exception $e) {
    echo "Erreur : " . htmlspecialchars($e->getMessage());
}
?>