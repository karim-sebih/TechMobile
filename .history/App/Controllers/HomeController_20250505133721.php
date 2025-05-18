<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index($params = [])
    {
        // Capturer le contenu de home.php
        ob_start();
        include __DIR__ . '/../../Public/Views/home.php';
        $htmlContent = ob_get_clean();

        // Retourner le contenu HTML dans un JSON
        $data = [
            'title' => 'Accueil - TechMobile',
            'content' => $htmlContent
        ];
        echo json_encode($data);
    }
}