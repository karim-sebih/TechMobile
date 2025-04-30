<?php
namespace App\Core;

class Controller
{
    protected function render($view, $data = [])
    {
        extract($data);
        require dirname(__DIR__, 2) . '/Public/Views/' . $view . '.php';
    }
}
