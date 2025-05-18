<?php
namespace App\Controllers;

class HomeController {
    public function index($params = []) {
        $homeFile = __DIR__ . '/../../Public/Views/home.php';
        if (!file_exists($homeFile)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Fichier home.php non trouvé']);
            exit;
        }
        ob_start();
        include $homeFile;
        $content = ob_get_clean();
        $data = ['title' => 'Accueil - TechMobile', 'content' => $content];
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
?>