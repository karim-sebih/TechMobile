<?php
namespace App\Core;

class Controller
{
    protected function render($view, $data = [])
    {
        // Extraire les données pour la vue
        extract($data);

        // Capturer la sortie de la vue dans une variable
        ob_start();
        require dirname(__DIR__, 2) . '/Public/Views/' . $view . '.php';
        $content = ob_get_clean();

        // Retourner le contenu de la vue
        return $content;
    }
}