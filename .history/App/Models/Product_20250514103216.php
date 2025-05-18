<?php
class Product {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name, s.name as subcategory_name 
                                    FROM products p 
                                    LEFT JOIN categories c ON p.category_id = c.id 
                                    LEFT JOIN subcategories s ON p.subcategory_id = s.id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($name, $description, $price, $stock, $category_id, $subcategory_id) {
        $stmt = $this->db->prepare("INSERT INTO products (name, description, price, stock, category_id, subcategory_id, created_at) 
                                    VALUES (:name, :description, :price, :stock, :category_id, :subcategory_id, NOW())");
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':stock' => $stock,
            ':category_id' => $category_id,
            ':subcategory_id' => $subcategory_id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}