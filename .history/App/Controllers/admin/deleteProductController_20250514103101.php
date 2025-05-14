<?php
require_once __DIR__ . '/../../Models/Product.php';

$productModel = new Product();

if (isset($_GET['id'])) {
    $productModel->delete($_GET['id']);
    header('Location: index.php?resource=admin/viewAllProducts');
    exit;
}
?>