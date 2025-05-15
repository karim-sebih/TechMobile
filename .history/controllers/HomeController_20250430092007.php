<?php

class HomeController extends Controller
{
    public function index()
    {
        // Tu peux passer des données à la vue si tu veux
        $message = "Bienvenue sur notre boutique !";
        $this->render('home', ['message' => $message]);
    }
}
