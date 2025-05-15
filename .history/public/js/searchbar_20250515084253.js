// Add to Cart Functionality (avec délégation d'événements)
document.addEventListener('click', (e) => {
    if (e.target.closest('.add-to-cart')) {
        const button = e.target.closest('.add-to-cart');
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