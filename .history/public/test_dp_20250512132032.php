<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../Public/config/da';

try {
    $db = \Database::getInstance();
    $result = $db->query("SELECT * FROM products LIMIT 4")->fetchAll(PDO::FETCH_ASSOC);
    echo "Connexion réussie ! Produits : <pre>" . print_r($result, true) . "</pre>";
} catch (Exception $e) {
    echo "Erreur de connexion : " . htmlspecialchars($e->getMessage());
}
?>