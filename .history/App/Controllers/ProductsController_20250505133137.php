<?php
namespace App\Controllers;

use App\Core\Controller;

class ProductsController extends Controller
{
    public function index()
    {
        $message = "Bienvenue dans ta boutique en ligne !";
        return $this->render('Products', ['message' => $message]); // Ajout de return
    }
}