<?php
/*
try {
    ProductComponentItems::init();
    error_log("home.php - ProductComponentItems initialisé avec succès");
} catch (Exception $e) {
    error_log("home.php - Erreur lors de l'initialisation : " . $e->getMessage());
    echo "<p>Erreur lors de l'initialisation : " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}
*/
try {
    $products = ProductComponentItems::findByFilters(['limit' => 4, 'order' => 'created_at DESC']);
    error_log("home.php - Produits récents récupérés : " . json_encode($products));
} catch (Exception $e) {
    error_log("home.php - Erreur lors de la récupération des produits : " . $e->getMessage());
    echo "<p>Erreur lors de la récupération des produits : " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}
?>

<section class="hero-gradient text-white py-section">
    <div class="container hero-flex">
        <div class="text-content">
            <h2 class="hero-title">Les Derniers Smartphones</h2>
            <p class="hero-subtitle">
                Découvrez notre collection premium d'appareils mobiles de pointe avec les meilleurs prix et garantie.
            </p>
            <div class="button-group">
                <button class="btn-primary">Shop Now</button>
                <button class="btn-secondary">View Deals</button>
            </div>
        </div>
        <div class="image-content">
            <img
                src="https://imgs.search.brave.com/5E37AR9HklxRGjsqiG8MPZD1zt8csn99eDyKzVdOp6I/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93d3cu/emRuZXQuZnIvd3At/Y29udGVudC91cGxv/YWRzL3pkbmV0LzIw/MjUvMDQvaXBob25l/LTE1LXByby1tYXgt/d2hpdGUtMS03NTB4/NDEwLndlYnA"
                alt="Premium Smartphones"
                class="phone-image"
            />
        </div>
    </div>
</section>

<div class="features">
    <div class="feature-content">
        <h2 class="title">Shop by Category</h2>
        <br>
        <div class="grid">
            <div class="card">
                <div class="icon-wrapper indigo">
                    <i class="fas fa-mobile-alt icon indigo-color"></i>
                </div>
                <h3 class="card-title">Flagship Phones</h3>
                <p class="card-desc">Premium devices</p>
            </div>
            <div class="card">
                <div class="icon-wrapper blue">
                    <i class="fas fa-dollar-sign icon blue-color"></i>
                </div>
                <h3 class="card-title">Budget Phones</h3>
                <p class="card-desc">Great value</p>
            </div>
            <div class="card">
                <div class="icon-wrapper green">
                    <i class="fas fa-camera icon green-color"></i>
                </