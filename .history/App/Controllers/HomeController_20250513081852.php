<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index($params = [])
    {
        $homeFile = __DIR__ . '/../../Public/Views/home.php';
        if (!file_exists($homeFile)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'home.php introuvable']);
            exit;
        }
    
        ob_start();
        include $homeFile;
        $content = ob_get_clean();
    
        $data = [
            'title' => 'Accueil - TechMobile',
            'content' => $content
        ];
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
