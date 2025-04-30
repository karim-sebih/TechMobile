<?php

class Controller {
    protected function render($view, $data = []) {
        extract($data);
        require "../Views/$view.php";
    }
}

class HomeController extends Controller {
    public function index() {
        $this->render('home');
    }
}
