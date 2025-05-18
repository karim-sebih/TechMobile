<?php
namespace App\Models;

use PDO;

class ProductComponentItems {
    private static $db;

    public static function init() {
        try {
            self::$db = \Database::getInstance();
            error_log("ProductComponentItems.php - Initialisation réussie avec la base 'tmobile' à " . date('Y-m-d H:i:s'));
        } catch (Exception $e) {
            error_log("ProductComponentItems.php - Erreur lors de l'initialisation : " . $e->getMessage());
            self::$db = null;
        }
    }

    public static function findByFilters(array $filters = []): array {
        if (!self::$db) {
            error_log("ProductComponentItems.php - Erreur : Base de données non initialisée");
            return [];
        }

        try {
            $sql = "SELECT p.*, pi.image_url 
                    FROM products p 
                    LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1";
            $params = [];

            $sql .= " ORDER BY p.created_at DESC"; // Ordre par défaut

            if (isset($filters['limit'])) {
                $sql .= " LIMIT " . (int)$filters['limit'];
            }

            error_log("ProductComponentItems.php - Requête SQL : $sql");
            error_log("ProductComponentItems.php - Paramètres : " . json_encode($params));

            $stmt = self::$db->prepare($sql);
            $stmt->execute($params);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            error_log("ProductComponentItems.php - Résultats : " . json_encode($results));
            return $results;
        } catch (Exception $e) {
            error_log("ProductComponentItems.php - Erreur dans findByFilters : " . $e->getMessage());
            return [];
        }
    }
}