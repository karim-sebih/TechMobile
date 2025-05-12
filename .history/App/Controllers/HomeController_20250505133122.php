<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index($params = [])
    {
        $message = "Bienvenue dans ta boutique en ligne !";
        echo json_encode(['message' => $message]);
    }
}