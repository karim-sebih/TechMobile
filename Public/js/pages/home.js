export function init() {
    // Charger les produits pour la section "products-display"
    fetch('router.php?resource=products', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        const productsDisplay = document.querySelector('.products-display');
        if (!Array.isArray(data) || data.length === 0) {
            productsDisplay.innerHTML += '<p>Aucun produit trouvé.</p>';
            return;
        }
        productsDisplay.innerHTML += `
            <div class="products-list">
                ${data.map(product => `
                    <div class="product-item">
                        <h3>${product.name}</h3>
                        <p>Prix : ${product.price} €</p>
                        <button class="add-to-cart" data-id="${product.id}" data-name="${product.name}" data-price="${product.price}">Ajouter au panier</button>
                    </div>
                `).join('')}
            </div>
        `;
    })
    .catch(err => {
        console.error('Erreur lors du chargement des produits :', err);
        document.querySelector('.products-display').innerHTML += '<p>Erreur lors du chargement des produits.</p>';
    });
}