export function init() {
    console.log('Page d\'accueil chargée !');
    fetch('router.php?resource=products', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return response.json();
    })
    .then(data => {
        const productsDisplay = document.querySelector('.products-display');
        if (!productsDisplay) {
            console.error('.products-display non trouvé dans le DOM');
            return;
        }
        if (!data.products || data.products.length === 0) {
            productsDisplay.innerHTML += '<p>Aucun produit trouvé.</p>';
            return;
        }
        productsDisplay.innerHTML += `
            <div class="products-list">
                ${data.products.map(product => `
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
        const productsDisplay = document.querySelector('.products-display');
        if (productsDisplay) productsDisplay.innerHTML += '<p>Erreur lors du chargement des produits.</p>';
    });
}