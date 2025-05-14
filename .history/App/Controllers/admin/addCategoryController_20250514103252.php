<?php
require_once __DIR__ . '/../../Models/Category.php';

$categoryModel = new Category();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryModel->create($_POST['name']);
    header('Location: index.php?resource=admin/viewCategories');
    exit;
}
?>