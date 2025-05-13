<?php
namespace App\Models;

use PDO;

class ProductComponentItems {
    private static $db;

    public static function init() {
        self::$db = \Database::getInstance();
    }

    public static function findByFilters(array $filters = []): array {
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

        // Ajout de l'ordre
        if (isset($filters['order'])) {
            $sql .= " ORDER BY " . $filters['order'];
        }

        // Ajout de la limite
        if (isset($filters['limit'])) {
            $sql .= " LIMIT :limit";
            $params[':limit'] = (int)$filters['limit'];
        }

        $stmt = self::$db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function details(int $id): ?array {
        $stmt = self::$db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function create(array $data): int {
        $stmt = self::$db->prepare("INSERT INTO products (name, description, price, image_url, stock, category_id, subcategory_id, is_featured) VALUES (:name, :description, :price, :image_url, :stock, :category_id, :subcategory_id, :is_featured)");
        $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':image_url' => $data['image_url'] ?? null,
            ':stock' => $data['stock'] ?? 0,
            ':category_id' => $data['category_id'],
            ':subcategory_id' => $data['subcategory_id'] ?? null,
            ':is_featured' => $data['is_featured'] ?? 0
        ]);
        return self::$db->lastInsertId();
    }

    public static function updateByID(int $id, array $data): bool {
        $sql = "UPDATE products SET name = :name, description = :description, price = :price, image_url = :image_url, stock = :stock, category_id = :category_id, subcategory_id = :subcategory_id, is_featured = :is_featured WHERE id = :id";
        $stmt = self::$db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':image_url' => $data['image_url'] ?? null,
            ':stock' => $data['stock'] ?? 0,
            ':category_id' => $data['category_id'],
            ':subcategory_id' => $data['subcategory_id'] ?? null,
            ':is_featured' => $data['is_featured'] ?? 0
        ]);
    }

    public static function deleteByID(int $id): bool {
        $stmt = self::$db->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}