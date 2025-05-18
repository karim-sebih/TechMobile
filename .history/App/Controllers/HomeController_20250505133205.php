<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index($params = [])
    {
        $data = [
            'title' => 'Accueil - TechMobile',
            'content' => '<p>Bienvenue dans ta boutique en ligne ! Découvrez nos <a href="index.php?resource=products">produits</a>.</p>',
            'styles' => 'home'
        ];
        echo json_encode($data);
    }
}