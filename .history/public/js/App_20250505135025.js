import { handleNavigation } from './Navigation.js';

import { Favorites } from './Favorites.js';
import { Rating } from './Rating.js';
import { ProductService } from './services/ProductService.js';

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    handleNavigation();
    initSearchBar();
    Favorites.loadCache();
    Rating.add();
    ProductService.getAll().then(products => console.log('Produits chargés:', products));
});