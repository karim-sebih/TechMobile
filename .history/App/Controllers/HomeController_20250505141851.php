<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index($params = [])
    {
        ob_start();
        include __DIR__ . '/../../Public/Views/home.php';
        $htmlContent = ob_get_clean();

        $data = [
            'title' => 'Accueil - TechMobile',
            'content' => $htmlContent
        ];
        echo json_encode($data);
    }
}