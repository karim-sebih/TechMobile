<?php
namespace App\Controllers;

use App\Core\Controller;

class CAController extends Controller
{
    public function index($params = [])
    {
        // Vérifier si le fichier home.php existe
        $homeFile = __DIR__ . '/../../Public/Views/home.php';
        if (!file_exists($homeFile)) {
            http_response_code(500);
            echo json_encode(['error' => 'Fichier home.php non trouvé à : ' . $homeFile]);
            exit;
        }

        ob_start();
        include $homeFile;
        $htmlContent = ob_get_clean();

        $data = [
            'title' => 'Accueil - TechMobile',
            'content' => $htmlContent
        ];
        echo json_encode($data);
    }
}