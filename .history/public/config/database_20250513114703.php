<?php
class Database {
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    'mysql:host=localhost;dbname=tmobile;charset=utf8',
                    'root',
                    '',
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
                error_log("database.php - Connexion à la base de données réussie");
            } catch (PDOException $e) {
                error_log("database.php - Erreur de connexion : " . $e->getMessage());
                throw new Exception("Erreur de connexion à la base : " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}