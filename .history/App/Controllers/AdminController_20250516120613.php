<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\ProductModel;
use App\Models\CategoryModel;

class AdminController extends Controller {
    public function index() {
        $categoryModel = new CategoryModel();
        $productModel = new ProductModel();
        $categories = $categoryModel->getAllCategories();
        $products = $productModel->getActiveProducts();

        $data = [
            'categories' => $categories,
            'products' => $products
        ];

        $this->render('admin_view', $data);
    }

    public function addProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Logique de validation et insertion (comme dans admin.php)
            // Retourner une réponse JSON
        }
    }
}