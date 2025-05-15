<?php

class Controller {
    protected function render($view, $data = []) {
        extract($data);
        require "../Views/$view.php";
    }
}
