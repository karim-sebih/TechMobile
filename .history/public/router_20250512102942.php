<?php
header('Content-Type: application/json');
$resource = $_GET['resource'] ?? 'home';
if ($resource === 'home') {
    echo json_encode(['content' => '<div>Bienvenue</div>', 'title' => 'Accueil']);
} else {
    echo json_encode(['error' => 'Ressource non trouvée']);
}
?>