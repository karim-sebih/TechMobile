<?php
namespace App\Controllers;

use App\Core\Controller;

class CategoriesController extends Controller
{
    public function index($params = [])
    {
        // Vérifier si le fichier home.php existe
        $homeFile = __DIR__ . '/../../Public/Views/categories.php';
        if (!file_exists($homeFile)) {
            http_response_code(500);
            echo json_encode(['error' => 'Fichier categore.php non trouvé à : ' . $homeFile]);
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