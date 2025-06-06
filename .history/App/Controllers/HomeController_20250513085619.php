<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller {
    public function index($params = []) {
        $data = [];
        $htmlContent = $this->render('home', $data);
        if ($htmlContent === false) {
            error_log("HomeController.php - Échec du rendu de home.php");
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Échec du rendu de la vue']);
            exit;
        }
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