<?php
require __DIR__ . '/config/app.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>TechMobile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/techmania/TechMobile/Public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- ... (header, main, footer inchangés) ... -->

    <!-- Commenté si searchbar.js est manquant -->
    <!-- <script src="/techmania/TechMobile/Public/js/searchbar.js"></script> -->
    <script type="module" src="/techmania/TechMobile/Public/js/App.js"></script>
    <script type="module" src="/techmania/TechMobile/Public/js/PageLoader.js"></script>
</body>
</html>