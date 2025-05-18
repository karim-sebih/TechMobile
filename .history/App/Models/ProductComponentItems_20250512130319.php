<?php
namespace App\Models;

use PDO;



class ProductComponentItems {
    private static $db;

    
    public static function init() {
        try {
            self::$db = \Database::getInstance();
            error_log("ProductComponentItems.php - Initialisation réussie avec la base 'tmobile'");
        } catch (Exception $e) {
            error_log("ProductComponentItems.php - Erreur lors de l'initialisation : " . $e->getMessage());
            self::$db = null; // Assigner null pour éviter des erreurs ultérieures
        }
    }

    public static function findByFilters(array $filters = []): array {
        if (!self::$db) {
            error_log("ProductComponentItems.php - Erreur : Base de données non initialisée");
            return [];
        }

        try {
            $sql = "SELECT * FROM products WHERE 1=1";
            $params = [];

            if (isset($filters['category_id'])) {
                $sql .= " AND category_id = :category_id";
                $params[':category_id'] = $filters['category_id'];
            }
            if (isset($filters['subcategory_id'])) {
                $sql .= " AND subcategory_id = :subcategory_id";
                $params[':subcategory_id'] = $filters['subcategory_id'];
            }
            if (isset($filters['is_featured'])) {
                $sql .= " AND is_featured = :is_featured";
                $params[':is_featured'] = $filters['is_featured'];
            }

            if (isset($filters['order'])) {
                $sql .= " ORDER BY " . $filters['order'];
            }

            if (isset($filters['limit'])) {
                $sql .= " LIMIT :limit";
                $params[':limit'] = (int)$filters['limit'];
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