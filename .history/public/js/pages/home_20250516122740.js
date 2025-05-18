export function init() {
    // Pas besoin de refaire un fetch ici, car home.php charge déjà les produits
    console.log('Initialisation de home.js');
    const productsDisplay = document.querySelector('.products-display');
    if (!productsDisplay) {
        console.warn('Élément .products-display non trouvé dans home.php');
    }
}export function init() {
    // Pas besoin de refaire un fetch ici, car home.php charge déjà les produits
    console.log('Initialisation de home.js');
    const productsDisplay = document.querySelector('.products-display');
    if (!productsDisplay) {
        console.warn('Élément .products-display non trouvé dans home.php');
    }
}