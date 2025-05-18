export function init() {
    console.log('Page d\'accueil chargée !');
    const productsDisplay = document.querySelector('.products-display');
    if (!productsDisplay) {
        console.error('.products-display non trouvé dans le DOM');
        return;
    }
    console.log('Contenu de products-display:', productsDisplay.innerHTML);

    const productsList = productsDisplay.querySelector('.products-list');
    if (!productsList || productsList.children.length === 0) {
        console.log('Aucun produit trouvé dans products-list');
    } else {
        console.log('Produits affichés:', productsList.children.length);
    }
}