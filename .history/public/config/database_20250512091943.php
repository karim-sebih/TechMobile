<?php

class Database {
    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $dbname = 'techmobile';
    private $user = 'root';
    private $pass = '';

    private function __construct() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("SET CHARACTER SET utf8");
            error_log("Database.php - Connexion à la base de données réussie");
        } catch (PDOException $e) {
            error_log("Database.php - Erreur de connexion à la base de données : " . $e->getMessage());
            echo "Erreur de connexion à la base de données : " . htmlspecialchars($e->getMessage());
            exit;
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}