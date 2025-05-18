<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\ProductModel;
use App\Models\CategoryModel;

class AdminController extends Controller {
    public function index() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'moderateur'])) {
            header("Location: index.php?resource=login");
            exit;
        }

        $categoryModel = new CategoryModel();
        $productModel = new ProductModel();
        $categories = $categoryModel->getAllCategories();
        $products = $productModel->getActiveProducts();

        $data = [
            'categories' => $categories,
            'products' => $products
        ];

        $this->render('admin', $data);
    }

    public function addProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
            $product_name = $_POST['product_name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $image_url = $_POST['image_url'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            $errors = [];
            if (empty($product_name) || strlen(trim($product_name)) < 3) {
                $errors[] = "Le nom du produit doit contenir au moins 3 caractères.";
            }
            $price = floatval($price);
            if ($price <= 0) {
                $errors[] = "Le prix doit être un nombre positif.";
            }
            if (!empty($image_url)) {
                if (!filter_var($image_url, FILTER_VALIDATE_URL) || !preg_match('/^(https?:\/\/[^\s$.?#].[^\s]*)$/', $image_url)) {
                    $errors[] = "L'URL de l'image n'est pas valide.";
                }
            }
            if (empty($category_id) || !is_numeric($category_id)) {
                $errors[] = "Veuillez sélectionner une catégorie valide.";
            } else {
                $db = \Database::getInstance();
                $stmt = $db->prepare("SELECT COUNT(*) FROM categories WHERE category_id = :category_id");
                $stmt->execute(['category_id' => $category_id]);
                if ($stmt->fetchColumn() == 0) {
                    $errors[] = "La catégorie sélectionnée n'existe pas.";
                }
            }

            if (!empty($errors)) {
                header('Content-Type: application/json');
                echo json_encode(['error' => implode(' ', $errors)]);
                exit;
            }

            try {
                $db = \Database::getInstance();
                $stmt = $db->prepare("INSERT INTO products (product_name, price, image_url, category_id, is_active, created_at) VALUES (:product_name, :price, :image_url, :category_id, :is_active, NOW())");
                $stmt->execute([
                    'product_name' => $product_name,
                    'price' => $price,
                    'image_url' => $image_url,
                    'category_id' => $category_id,
                    'is_active' => $is_active
                ]);
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Erreur lors de l\'ajout : ' . $e->getMessage()]);
                exit;
            }
        }
    }
}