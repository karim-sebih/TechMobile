export function init() {
    const importPath = `/path/to/your/js/pages/${filepath}`; // Ajustez s
    console.log('Page d\'accueil chargée !');
    const productsDisplay = document.querySelector('.products-display');
    if (!productsDisplay) {
        console.error('.products-display non trouvé dans le DOM');
        return;
    }
    console.log('Contenu de products-display:', productsDisplay.innerHTML);
}