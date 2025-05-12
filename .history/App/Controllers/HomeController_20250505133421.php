<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index($params = [])
    {
        $data = [
            'message' => 'Bienvenue dans ta boutique en ligne ! Découvrez nos <a href="index.php?resource=products">produits</a>.'
        ];
        echo json_encode($data);
    }
}