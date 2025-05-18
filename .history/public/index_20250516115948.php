<?php
session_start(); // Initialiser la session
require __DIR__ . '/config/app.php';

// Vérifier si l'utilisateur est connecté et a le bon rôle pour afficher le lien "Admin"
$showAdminLink = isset($_SESSION['user_id']) && in_array($_SESSION['user_role'], ['admin', 'moderateur']);
// Vérifier si l'utilisateur est connecté pour afficher "Déconnexion" au lieu de "Connexion"
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>TechMobile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="import { MAIN_ID } from '../config.js';

console.log('PageLoader.js chargé, MAIN_ID:', MAIN_ID);

const MAIN_CONTAINER = document.getElementById(MAIN_ID);

async function fileExists(filepath) {
    try {
        const response = await fetch(filepath, { method: 'HEAD' });
        return response.ok;
    } catch {
        return false;
    }
}

async function checkSession() {
    try {
        const response = await fetch('router.php?resource=session', {
            method: 'GET',
            credentials: 'same-origin',
        });
        const data = await response.json();
        return data.isLoggedIn && (data.role === 'admin' || data.role === 'moderateur');
    } catch (err) {
        console.error('Erreur lors de la vérification de la session:', err);
        return false;
    }
}

async function getApiContent(resource) {
    console.log('Récupération du contenu pour:', resource);
    try {
        const response = await fetch(`router.php?resource=${encodeURIComponent(resource)}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin',
        });
        if (!response.ok) {
            console.error('Réponse non OK pour', resource, response.status, response.statusText);
            return '<p>Erreur : Page non accessible (Erreur HTTP ' + response.status + ')</p>';
        }

        const data = await response.json();
        if (data.error) {
            console.warn('Erreur retournée par le serveur:', data.error);
            return `<p>${data.error}</p>`;
        }

        if (resource === 'products') {
            if (!Array.isArray(data) || data.length === 0) {
                return '<p>Aucun produit trouvé.</p>';
            }
            return `
                <h1>Nos Produits</h1>
                <div class="products-list">
                    ${data.map(product => `
                        <div class="product-item">
                            <img src="${product.image_url || 'https://via.placeholder.com/150'}" alt="${product.product_name}" class="product-image"><br>
                            <h3>${product.product_name}</h3>
                            <p>Prix : ${product.price} €</p>
                            <button class="add-to-cart" 
                                    data-id="${product.product_id}" 
                                    data-name="${product.product_name}" 
                                    data-price="${product.price}" 
                                    data-image="${product.image_url || 'https://via.placeholder.com/150'}">
                                Ajouter au panier
                            </button>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        if (data.content) {
            if (data.title) {
                document.title = data.title;
            }
            return data.content;
        }

        return `
            <h1>${resource.charAt(0).toUpperCase() + resource.slice(1)}</h1>
            <div>${data.message || data.info || 'Contenu non défini'}</div>
        `;
    } catch (err) {
        console.error('Erreur dans getApiContent:', err);
        return '<p>Erreur lors du chargement de la page : ' + err.message + '</p>';
    }
}

let CURRENT_PAGE = null;

export async function loadPage(page, args = []) {
    console.log('Chargement de la page:', page);
    if (page !== CURRENT_PAGE) {
        clearMainContainer();
        CURRENT_PAGE = page;
    }
    removeOldLinks(page);

    // Vérification spéciale pour la page admin
    if (page === 'admin') {
        const isAuthorized = await checkSession();
        if (!isAuthorized) {
            console.log('Accès refusé à admin, redirection vers login');
            page = 'login';
        }
    }

    const content = await getApiContent(page);
    if (!content || content.includes('Page non trouvée')) {
        page = '404';
    }

    await displayPageHTML(content);
    await loadPageStyle(page, `css/${page}.css`);
    await loadPageScript(`js/pages/${page}.js`, args);
}

async function displayPageHTML(html) {
    MAIN_CONTAINER.innerHTML = html;
}

async function loadPageStyle(filename, filepath) {
    if (await fileExists(filepath) && !linkAlreadyExists(filename)) {
        document.head.appendChild(createCSSLink(filename, filepath));
    }
}

async function loadPageScript(filepath, args) {
    try {
        const script = await import(`./${filepath}`);
        if (typeof script.init === 'function') {
            console.log('Exécution de init() pour', filepath);
            script.init(...args);
        }
    } catch (err) {
        if (err.message.includes('404')) {
            console.warn(`Le script ${filepath} n'existe pas`);
        } else {
            console.error(`Erreur dans le fichier ${filepath}`, err);
        }
    }
}

function createCSSLink(filename, filepath) {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = filepath;
    link.id = `CSSLink-${filename}`;
    return link;
}

function removeOldLinks(currentFilename) {
    document.head.querySelectorAll('[id^="CSSLink-"]').forEach(link => {
        const oldFileName = link.id.split('-')[1];
        if (oldFileName !== currentFilename) link.remove();
    });
}

function linkAlreadyExists(filename) {
    return document.head.querySelector(`#CSSLink-${filename}`);
}

function clearMainContainer() {
    MAIN_CONTAINER.innerHTML = '';
}

// Gérer la navigation
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM chargé, initialisation de PageLoader');
    const navLinks = document.querySelectorAll('.nav-link');

    const initialPage = new URLSearchParams(window.location.search).get('resource') || 'home';
    loadPage(initialPage);

    navLinks.forEach(link => {
        link.addEventListener('click', async (e) => {
            e.preventDefault();
            const page = link.getAttribute('href').match(/resource=([^&]+)/)?.[1] || 'home';
            history.pushState({ page }, '', `index.php?resource=${page}`);
            await loadPage(page);
        });
    });

    window.addEventListener('popstate', (e) => {
        const page = e.state?.page || 'home';
        loadPage(page);
    });
});

// Exporter les fonctions pour être utilisées par d'autres modules
export { showPageloader, hidePageloader };">
    <link rel="shortcut icon" href="./assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="container nav-bar">
            <div class="logo-section">
                <i class="fas fa-mobile-alt icon-mobile"></i>
                <h1 class="site-title">TechMobile</h1>
            </div>
            <nav class="nav-links">
                <a href="index.php?resource=home" class="nav-link">Accueil</a>
                <a href="index.php?resource=products" class="nav-link">Produits</a>
                <a href="index.php?resource=about" class="nav-link">À propos</a>
                <?php if ($showAdminLink): ?>
                    <a href="index.php?resource=admin" class="nav-link">Admin</a>
                <?php endif; ?>
                <a href="index.php?resource=<?php echo $isLoggedIn ? 'logout' : 'login'; ?>" class="nav-link">
                    <?php echo $isLoggedIn ? 'Déconnexion' : 'Connexion'; ?>
                </a>
            </nav>
            <div class="icons">
                <button id="search-btn" class="icon-btn"><i class="fas fa-search"></i></button>
                <button id="cart-btn" class="icon-btn cart-btn">
                    <i class="fas fa-shopping-cart"></i>
                    <span id="cart-count" class="cart-count">0</span>
                </button>
                <button id="mobile-menu-btn" class="icon-btn"><i class="fas fa-bars"></i></button>
            </div>
        </div>
        <div id="mobile-menu" class="hidden md:hidden bg-white shadow-lg">
            <ul class="flex flex-col items-center gap-4 py-4">
                <li><a href="index.php?resource=home" class="nav-link">Accueil</a></li>
                <li><a href="index.php?resource=products" class="nav-link">Produits</a></li>
                <li><a href="index.php?resource=about" class="nav-link">À propos</a></li>
                <?php if ($showAdminLink): ?>
                    <li><a href="index.php?resource=admin" class="nav-link">Admin</a></li>
                <?php endif; ?>
                <li>
                    <a href="index.php?resource=<?php echo $isLoggedIn ? 'logout' : 'login'; ?>" class="nav-link">
                        <?php echo $isLoggedIn ? 'Déconnexion' : 'Connexion'; ?>
                    </a>
                </li>
            </ul>
        </div>
        <div id="search-bar" class="hidden mt-2">
            <div class="search-container">
                <input type="text" id="search-input" placeholder="Rechercher un produit..." class="search-input">
                <button id="search-submit" class="search-button"><i class="fas fa-search"></i></button>
            </div>
        </div>
        <div id="cart-panel" class="cart-panel cart-closed">
            <div class="cart-header">
                <h3>Your Cart</h3>
                <button id="close-cart" class="icon-btn"><i class="fas fa-times"></i></button>
            </div>
            <div id="cart-items" class="cart-items">
                <p>Your cart is empty</p>
            </div>
            <div class="cart-footer">
                <div class="subtotal">
                    <span>Subtotal:</span>
                    <span id="cart-subtotal">$0.00</span>
                </div>
                <button class="checkout-btn">Proceed to Checkout</button>
            </div>
        </div>
        <div id="cart-overlay" class="cart-overlay"></div>
    </header>

    <main id="BodyLine">
        <!-- Le contenu sera chargé dynamiquement par PageLoader.js -->
    </main>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="brand-header">
                        <i class="fas fa-mobile-alt brand-icon"></i>
                        <h3 class="brand-title">TechMobile</h3>
                    </div>
                    <p class="brand-description">
                        Votre source de confiance pour les smartphones et accessoires haut de gamme à des prix compétitifs.
                    </p>
                </div>

                <div class="footer-section">
                    <h4 class="footer-title">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="index.php?resource=home" class="nav-link">Home</a></li>
                        <li><a href="index.php?resource=products" class="nav-link">Products</a></li>
                        <li><a href="#brands">Brands</a></li>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4 class="footer-title">Customer Service</h4>
                    <ul class="footer-links">
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Shipping Policy</a></li>
                        <li><a href="#">Return Policy</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4 class="footer-title">Payment Methods</h4>
                    <div class="payment-grid">
                        <div class="payment-method"><i class="fab fa-cc-visa"></i></div>
                        <div class="payment-method"><i class="fab fa-cc-mastercard"></i></div>
                        <div class="payment-method"><i class="fab fa-cc-amex"></i></div>
                        <div class="payment-method"><i class="fab fa-cc-paypal"></i></div>
                        <div class="payment-method"><i class="fab fa-apple-pay"></i></div>
                        <div class="payment-method"><i class="fab fa-google-pay"></i></div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2025 TechMobile. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="./js/searchbar.js"></script>
    <script type="module" src="./js/core/PageLoader.js"></script>
    
</body>
</html>