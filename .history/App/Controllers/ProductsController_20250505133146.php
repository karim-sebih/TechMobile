<?php
namespace App\Controllers;

use App\Models\ProductComponentItems;

class ProductController {
    public function index(array $filters = []): void {
        ProductComponentItems::init();
        $results = ProductComponentItems::findByFilters($filters);
        echo json_encode($results);
    }
    
    public function show(int $id): void {
        ProductComponentItems::init();
        $details = ProductComponentItems::details($id);
        echo json_encode($details);
    }
    
    public function store(array $data): void {
        ProductComponentItems::init();
        $id = ProductComponentItems::create($data);
        echo json_encode(["success" => true, "id" => $id]);
    }
    
    public function update(array $data): void {
        ProductComponentItems::init();
        $id = $data['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(["error" => "ID manquant."]);
            return;
        }
        unset($data['id']);
        $ok = ProductComponentItems::updateByID($id, $data);
        echo json_encode(["success" => $ok]);
    }
    
    public function destroy(int $id): void {
        ProductComponentItems::init();
        $ok = ProductComponentItems::deleteByID($id);
        echo json_encode(["success" => $ok]);
    }
}