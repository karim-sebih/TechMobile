<?php

class Controller
{
    protected function render($view, $data = [])
    {
        extract($data); // Rend les variables accessibles dans la vue
        require "../Views/{$view}.php";
    }
}
// Compare this snippet from controllers/DefaultControlle