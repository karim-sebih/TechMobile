<?php

class Database {
    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $dbname = 'techmobile';
    private $user = 'root'; // Par défaut pour XAMPP
    private $pass = '';     // Par défaut pour XAMPP

    private function __construct() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("SET CHARACTER SET utf8");
            // Débogage : Confirmer que la connexion est établie
            error_log("Connexion à la base de données réussie");
        } catch (PDOException $e) {
            // Afficher l'erreur pour déboguer
            error_log("Erreur de connexion à la base de données : " . $e->getMessage());
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}