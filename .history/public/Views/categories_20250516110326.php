<?php
session_start();

// Vérifier si l'utilisateur est connecté et a le bon rôle
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'moderateur'])) {
    header("Location: index.php?resource=login");
    exit;
}

// Chemins relatifs ajustés
$databasePath = __DIR__ . '/../config/database.php';
if (!file_exists($databasePath)) {
    error_log("admin.php - Erreur : database.php non trouvé à $databasePath");
    die("Erreur : database.php non trouvé");
}
require_once $databasePath;

// Connexion à la base de données
try {
    $db = \Database::getInstance();
    $db->query("SELECT 1"); // Test simple
    error_log("admin.php - Connexion à la base de données réussie à " . date('Y-m-d H:i:s'));
} catch (Exception $e) {
    error_log("admin.php - Échec de la connexion à la base : " . $e->getMessage());
    die("Erreur de connexion à la base de données : " . htmlspecialchars($e->getMessage()));
}

<!-- Sidebar -->
<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav-list primary-nav">
            <li class="nav-item">
                <a href="index.php?resource=admin" class="nav-link">
                   
                    <span class="nav-label">Produits</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?resource=categories" class="nav-link">
                  
                    <span class="nav-label">Catégories</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?resource=clients" class="nav-link">
                 
                    <span class="nav-label">Clients</span>
                </a>
            </li>
        </ul>
    </nav>
</div>