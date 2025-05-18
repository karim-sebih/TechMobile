<?php
class ProductComponentItems extends Database
{
    public static function create($data)
    {
        $db = new Database();
        $stmt = $db->prepare("INSERT INTO products (name, price, stock, image_url) VALUES (:name, :price, :stock, :image_url)");
        $stmt->execute([
            ':name' => $data['name'],
            ':price' => $data['price'],
            ':stock' => $data['stock'],
            ':image_url' => $data['image_url']
        ]);
        return ['id' => $db->lastInsertId(), 'name' => $data['name'], 'price' => $data['price'], 'stock' => $data['stock'], 'image_url' => $data['image_url']];
    }

    public static function delete($id)
    {
        $db = new Database();
        $stmt = $db->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Méthode existante pour récupérer les produits
    public static function findByFilters($filters = [])
    {
        $db = new Database();
        $query = "SELECT * FROM products";
        $params = [];
        if (!empty($filters)) {
            $conditions = [];
            foreach ($filters as $key => $value) {
                $conditions[] = "$key = :$key";
                $params[":$key"] = $value;
            }
            $query .= " WHERE " . implode(' AND ', $conditions);
        }
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour mettre à jour (à implémenter)
    public static function update($id, $data)
    {
        $db = new Database();
        $stmt = $db->prepare("UPDATE products SET name = :name, price = :price, stock = :stock, image_url = :image_url WHERE id = :id");
        return $stmt->execute(array_merge([':id' => $id], $data));
    }
}