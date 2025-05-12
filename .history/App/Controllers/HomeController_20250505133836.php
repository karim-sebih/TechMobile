<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index($params = [])
    {
        // Données dynamiques
        $heroTitle = "Les Derniers Smartphones";
        $heroSubtitle = "Découvrez notre collection premium d'appareils mobiles de pointe avec les meilleurs prix et garantie.";

        // Capturer le contenu de home.php
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