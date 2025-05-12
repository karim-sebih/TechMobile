import { USER_ID } from "./config.js";
import Routers from "./services/ProductService.js";
import Favorites from "./modules/Favorites.js";
import Rating from "./modules/Rating.js";

import { handleNavigation } from "./core/Navigation.js";
import { initSearchBar } from "./core/SearchBar.js";

document.addEventListener('DOMContentLoaded',async () => {
    window.addEventListener('hashchange', handleNavigation);
    await handleNavigation();
    await setRatingValues();
    await initSearchBar();
    await Favorites.loadCache();
    console.log(USER_ID);
    console.log(Favorites.cachedFavorites);
});

async function setRatingValues() {
    const data = await Routers.productComponentItems.getAll();
    Rating.add(data.map(item => item.id));
}

