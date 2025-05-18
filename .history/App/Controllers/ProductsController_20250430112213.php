<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $message = "Bienvenue dans ta boutique en ligne !";
        return $this->render('prod', ['message' => $message]); // Ajout de return
    }
}