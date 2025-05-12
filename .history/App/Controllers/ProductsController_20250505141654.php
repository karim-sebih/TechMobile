<?php
namespace App\Controllers;

use App\Core\Controller;

class ProductsController extends Controller
{
    public function index(array $params = [])
    {
        // Charger le fichier products.php
        $productsFile = __DIR__ . '/../../Public/Views/products.php';
        if (!file_exists($productsFile)) {
            http_response_code(500);
            echo json_encode(['error' => 'Fichier products.php non trouvé à : ' . $productsFile]);
            exit;
        }

        ob_start();
        include $productsFile;
        $htmlContent = ob_get_clean();

        // Optionnel : Ajouter des données dynamiques (ex. liste de produits)
        // $products = ProductComponentItems::findByFilters($params); // À décommenter si tu veux intégrer des produits
        // $htmlContent = str_replace('{{products}}', json_encode($products), $htmlContent); // Remplacer un placeholder

        $data = [
            'title' => 'Produits - TechMobile',
            'content' => $htmlContent
        ];
        echo json_encode($data);
    }

    public function show(int $id): void
    {
        ProductComponentItems::init();
        $details = ProductComponentItems::details($id);
        echo json_encode($details);
    }

    public function store(array $data): void
    {
        ProductComponentItems::init();
        $id = ProductComponentItems::create($data);
        echo json_encode(["success" => true, "id" => $id]);
    }

    public function update(array $data): void
    {
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

    public function destroy(int $id): void
    {
        ProductComponentItems::init();
        $ok = ProductComponentItems::deleteByID($id);
        echo json_encode(["success" => $ok]);
    }
}