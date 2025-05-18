<?php
require __DIR__ . '/config/app.php';
require __DIR__ . '/components/ProductComponentItems.php';
require __DIR__ . '/components/CategoryComponentItems.php';

header('Content-Type: application/json');

$resource = $_GET['resource'] ?? '';
$action = $_GET['action'] ?? '';



if ($resource === 'admin') {
    session_start();
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'Accès refusé']);
        exit;
    }

    switch ($action) {
        case 'add_product':
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $stock = $_POST['stock'] ?? 0;
            $image_url = $_POST['image_url'] ?? '';

            if ($name && is_numeric($price) && is_numeric($stock)) {
                $product = ProductComponentItems::create([
                    'name' => $name,
                    'price' => floatval($price),
                    'stock' => intval($stock),
                    'image_url' => $image_url
                ]);
                echo json_encode(['success' => true, 'message' => 'Produit ajouté']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Données invalides']);
            }
            break;

        case 'get_products':
            $products = ProductComponentItems::findByFilters();
            echo json_encode($products);
            break;

        case 'delete_product':
            $id = $_GET['id'] ?? '';
            if ($id && ProductComponentItems::delete($id)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
            break;

        case 'add_category':
            $name = $_POST['name'] ?? '';
            if ($name) {
                $category = CategoryComponentItems::create(['name' => $name]);
                echo json_encode(['success' => true, 'message' => 'Catégorie ajoutée']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Nom invalide']);
            }
            break;

        case 'get_categories':
            $categories = CategoryComponentItems::findByFilters();
            echo json_encode($categories);
            break;

        case 'delete_category':
            $id = $_GET['id'] ?? '';
            if ($id && CategoryComponentItems::delete($id)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Action non reconnue']);
            break;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Ressource non autorisée']);
}