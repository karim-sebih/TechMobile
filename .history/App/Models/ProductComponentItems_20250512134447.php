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
            self::$db = null;
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
            if (isset($filters['is_featured'])) {
                $sql .= " AND is_featured = :is_featured";
                $params[':is_featured'] = $filters['is_featured'];
            }

            if (isset($filters['order'])) {
                $validOrders = ['created_at DESC', 'created_at ASC', 'price DESC', 'price ASC'];
                if (in_array($filters['order'], $validOrders)) {
                    $sql .= " ORDER BY " . $filters['order'];
                } else {
                    error_log("ProductComponentItems.php - Valeur d'ordre invalide : " . $filters['order']);
                    $sql .= " ORDER BY created_at DESC";
                }
            }
            if (isset($filters['limit'])) {
                $sql .= " LIMIT " . (int)$filters['limit']; // Pas de paramètre nommé
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