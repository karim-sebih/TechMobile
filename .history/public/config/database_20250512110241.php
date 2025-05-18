<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=tmobile", "username", "password");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            error_log("Database.php - Connexion réussie à la base 'tmobile'");
        } catch (PDOException $e) {
            error_log("Database.php - Erreur de connexion : " . $e->getMessage());
            throw new Exception("Impossible de se connecter à la base de données : " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}
?>