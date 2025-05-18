<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$databasePath = __DIR__ . '../Public/';
if (!file_exists($databasePath)) {
    die('Erreur : database.php non trouvé à ' . $databasePath);
}

require_once $databasePath;

try {
    $db = \Database::getInstance();
    $sql = "SELECT * FROM products WHERE 1=1 ORDER BY created_at DESC LIMIT :limit";
    $stmt = $db->prepare($sql);
    $stmt->execute([':limit' => 4]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Connexion réussie ! Produits : <pre>" . print_r($result, true) . "</pre>";
} catch (Exception $e) {
    echo "Erreur : " . htmlspecialchars($e->getMessage());
}
?>