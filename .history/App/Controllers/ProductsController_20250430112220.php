<?php
namespace App\Controllers;

use App\Core\Controller;

class ProductController extends Controller
{
    public function index()
    {
        $message = "Bienvenue dans ta boutique en ligne !";
        return $this->render('product', ['message' => $message]); // Ajout de return
    }
}