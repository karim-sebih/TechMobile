<?php
class Category {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function getAllCategories() {
        $stmt = $this->db->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllSubcategories() {
        $stmt = $this->db->query("SELECT s.*, c.name as category_name 
                                  FROM subcategories s 
                                  LEFT JOIN categories c ON s.category_id = c.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($name) {
        $stmt = $this->db->prepare("INSERT INTO categories (name, created_at) VALUES (:name, NOW())");
        $stmt->execute([':name' => $name]);
    }
}