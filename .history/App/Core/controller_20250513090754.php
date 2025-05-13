<?php
namespace App\Core;

class Controller
{
    protected function render($view, $data = [])
    {
        extract($data);
        ob_start();
        $viewPath = dirname(__DIR__, 2) . '/Public/Views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            die("Vue non trouvée : $viewPath");
        }
        require $viewPath;
        $content = ob_get_clean();
        if ($content === false) {
            die("Erreur lors de la capture de la sortie");
        }
        return $content;
    }
}