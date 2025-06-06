<?php
namespace App\Controllers;

// Assurez-vous que App\Core\Controller existe
// Si manquant, créez un fichier App/Core/Controller.php avec :
// <?php
// namespace App\Core;
// class Controller {}
// ?>

class HomeController /* extends Controller */ { // Commentez l'héritage si Controller.php est manquant
    public function index($params = []) {
        $homeFile = __DIR__ . '/../../Public/Views/home.php';
        if (!file_exists($homeFile)) {
            error_log("HomeController.php - Fichier home.php non trouvé à : " . $homeFile);
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Fichier home.php non trouvé']);
            exit;
        }

        ob_start();
        try {
            include $homeFile;
            $htmlContent = ob_get_clean();
            if (empty(trim($htmlContent))) {
                error_log("HomeController.php - Contenu vide généré par home.php");
                $htmlContent = '<p>Contenu vide ou erreur dans home.php.</p>';
            }
            error_log("HomeController.php - Contenu HTML généré : " . substr($htmlContent, 0, 100) . "...");
        } catch (Exception $e) {
            ob_end_clean();
            error_log("HomeController.php - Erreur lors de l'inclusion de home.php : " . $e->getMessage());
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Erreur dans home.php : ' . $e->getMessage()]);
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