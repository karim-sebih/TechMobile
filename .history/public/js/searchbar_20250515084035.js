// Mobile Menu Toggle
const mobileMenuBtn = document.getElementById('mobile-menu-btn');
const mobileMenu = document.getElementById('mobile-menu');
if (mobileMenuBtn && mobileMenu) {
    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
}

// Search Bar Toggle
const searchBtn = document.getElementById('search-btn');
const searchBar = document.getElementById('search-bar');
const searchInput = document.getElementById('search-input');
const searchSubmit = document.getElementById('search-submit');

if (searchBtn && searchBar && searchInput && searchSubmit) {
    searchBtn.addEventListener('click', () => {
        searchBar.classList.toggle('hidden');
        if (!searchBar.classList.contains('hidden')) {
            searchInput.focus();
        }
    });

    searchSubmit.addEventListener('click', () => {
        const query = searchInput.value.trim();
        if (query) {
            window.location.href = `index.php?resource=products&search=${encodeURIComponent(query)}`;
        }
    });

    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            searchSubmit.click();
        }
    });
}

// Shopping Cart Functionality
const cartBtn = document.getElementById('cart-btn');
const closeCart = document.getElementById('close-cart');
const cartPanel = document.getElementById('cart-panel');
const cartOverlay = document.getElementById('cart-overlay');
const cartCount = document.getElementById('cart-count');
const cartItems = document.getElementById('cart-items');
const cartSubtotal = document.getElementById('cart-subtotal');

// Charger le panier depuis localStorage au démarrage
let cart = JSON.parse(localStorage.getItem('cart')) || [];

if (cartBtn && closeCart && cartPanel && cartOverlay && cartCount && cartItems && cartSubtotal) {
    cartBtn.addEventListener('click', () => {
        cartPanel.classList.remove('cart-closed');
        cartPanel.classList.add('cart-open');
        cartOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    closeCart.addEventListener('click', () => {
        cartPanel.classList.remove('cart-open');
        cartPanel.classList.add('cart-closed');
        cartOverlay.classList.add('hidden');
        document.body.style.overflow = 'auto';
    });

    cartOverlay.addEventListener('click', () => {
        cartPanel.classList.remove('cart-open');
        cartPanel.classList.add('cart-closed');
        cartOverlay.classList.add('hidden');
        document.body.style.overflow = 'auto';
    });

    // Mettre à jour le panier au démarrage
    updateCart();
}

// Add to Cart Functionality avec délégation d'événements
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('add-to-cart')) {
        const button = e.target;
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const price = parseFloat(button.getAttribute('data-price'));
        const image = button.getAttribute('data-image');

        // Validation renforcée
        if (!id || !name || isNaN(price) || price <= 0 || !image) {
            console.error('Données produit invalides :', { id, name, price, image });
            alert('Erreur : Produit invalide. Veuillez réessayer.');
            return;
        }

        // Vérifier si l'élément existe déjà
        const existingItem = cart.find(item => item.id === id);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                id,
                name,
                price,
                image,
                quantity: 1
            });
        }
        updateCart();
        cartPanel.classList.remove('cart-closed');
        cartPanel.classList.add('cart-open');
        cartOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
});

function updateCart() {
    if (!cartCount || !cartItems || !cartSubtotal) {
        console.error('Un ou plusieurs éléments du panier sont manquants.');
        return;
    }

    // Sauvegarder dans localStorage
    localStorage.setItem('cart', JSON.stringify(cart));

    // Mettre à jour le compte total
    const totalItems = cart.reduce((total, item) =>