require_once __DIR__ . '/App/Controllers/HomeController.php';
use App\Controllers\HomeController;

if (isset($_GET['resource']) && $_GET['resource'] === 'home') {
    $controller = new HomeController();
    $controller->index(); // Cette méthode doit générer le contenu de home.php
}