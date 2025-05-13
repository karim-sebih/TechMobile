<?php
namespace App\Models;

use PDO;

class ProductComponentItems {
    private static $db;

    public static function init() {
        try {
            self::$db = \Database::getInstance();
            error_log("ProductComponentItems::init() - Initialisation réussie");
        } catch (Exception $e) {
            error_log("ProductComponentItems::init() - Erreur : " . $e->getMessage());
            throw $e;
        }
    }

    public static function findByFilters(array $filters = []): array {
        if (!self::$db) {
            error_log("ProductComponentItems::findByFilters() - Erreur : Base de données non initialisée");
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

            error_log("ProductComponentItems::findByFilters() - Requête SQL : $sql");
            error_log("ProductComponentItems::findByFilters() - Paramètres : " . json_encode($params));

            $stmt = self::$db->prepare($sql);
            $stmt->execute($params);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            error_log("ProductComponentItems::findByFilters() - Résultats : " . json_encode($results));
            return $results;
        } catch (Exception $e) {
            error_log("ProductComponentItems::findByFilters() - Erreur : " . $e->getMessage());
            return [];
        }
    }

    public static function details(int $id): ?array {
        if (!self::$db) {
            error_log("ProductComponentItems::details() - Erreur : Base de données non initialisée");
            return null;
        }

        try {
            $stmt = self::$db->prepare("SELECT * FROM products WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("ProductComponentItems::details() - Résultat pour ID $id : " . json_encode($result));
            return $result ?: null;
        } catch (Exception $e) {
            error_log("ProductComponentItems::details() - Erreur : " . $e->getMessage());
            return null;
        }
    }

    public static function create(array $data): int {
        if (!self::$db) {
            error_log("ProductComponentItems::create() - Erreur : Base de données non initialisée");
            return 0;
        }

        try {
            $stmt = self::$db->prepare("INSERT INTO products (name, description, price, image_url, stock, category_id, subcategory_id, is_featured) VALUES (:name, :description, :price, :image_url, :stock, :category_id, :subcategory_id, :is_featured)");
            $stmt->execute([
                ':name' => $data['name'],
                ':description' => $data