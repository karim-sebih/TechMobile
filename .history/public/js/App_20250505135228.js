import { handleNavigation } from './core/Navigation.js';
import { initSearchBar } from './core/SearchBar.js';
import { Favorites } from './modules/Favorites.js';
import { Rating } from './modules/Rating.js';
import { ProductService } from './services/ProductService.js';

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    handleNavigation();
    initSearchBar();
    Favorites.loadCache();
    Rating.add();
    ProductService.getAll().then(products => console.log('Produits chargés:', products));
});