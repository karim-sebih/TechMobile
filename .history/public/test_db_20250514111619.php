<?php
require_once __DIR__ . '';

try {
    $db = \Database::getInstance();
    echo "Connexion à la base de données réussie !";
} catch (Exception $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}