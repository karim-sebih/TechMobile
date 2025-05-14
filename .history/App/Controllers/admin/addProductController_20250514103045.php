<?php
require_once __DIR__ . '/../../Models/Product.php';

$productModel = new Product();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productModel->create($_POST['name'], $_POST['description'], $_POST['price'], $_POST['stock'], $_POST['category_id'], $_POST['subcategory_id']);
    header('Location: index.php?resource=admin/viewAllProducts');
    exit;
}
?>
