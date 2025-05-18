<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller {
    public function index($params = []) {
        $htmlContent = $this->render('home');
        if ($htmlContent === false) {
            error_log("HomeController.php - Échec du rendu de home.php");
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Échec du rendu de la vue']);
            exit;
        }
        error_log("HomeController.php - Contenu rendu : " . substr($htmlContent, 0, 100) . "...");
        $data = ['title' => 'Accueil - TechMobile', 'content' => $htmlContent];
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function about($params = []) {
        $data = ['title' => 'À propos - TechMobile', 'content' => '<h2>À propos</h2><p>Bienvenue sur TechMobile !</p>'];
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}