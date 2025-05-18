<?php
namespace App\Controllers;

use App\Core\Controller;

class PrController extends Controller
{
    public function index()
    {
        $message = "Bienvenue dans ta boutique en ligne !";
        return $this->render('home', ['message' => $message]); // Ajout de return
    }
}