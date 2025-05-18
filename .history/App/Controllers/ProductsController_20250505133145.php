<?php
namespace App\Controllers;

use App\Core\Controller;

class ProductsController extends Controller
{
    public function index($params = [])
    {
        $products = ['Produit 1', 'Produit 2', 'Produit 3']; // Remplace par une vraie logique
        echo json_encode(['products' => $products]);
    }
}