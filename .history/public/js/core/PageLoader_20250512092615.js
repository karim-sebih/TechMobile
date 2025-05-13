import { MAIN_CONTAINER, ROUTER_PATH } from './config.js';

class PageLoader {
    static navLinks = document.querySelectorAll('.nav-link');
    static mainContainer = document.querySelector(MAIN_CONTAINER);

    static init() {
        console.log("PageLoader.js - Initialisation...");
        this.loadPage(window.location.search || '?resource=home');
        this.setupEventListeners();
    }

    static setupEventListeners() {
        this.navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const url = new URL(link.href);
                const searchParams = url.search;
                history.pushState({}, '', searchParams);
                this.loadPage(searchParams);
            });
        });

        window.addEventListener('popstate', () => {
            this.loadPage(window.location.search);
        });
    }

    static async loadPage(searchParams) {
        console.log(`PageLoader.js - Chargement de la page : ${searchParams}`);
        try {
            const response = await fetch(`${ROUTER_PATH}${searchParams}`);
            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }
            const data = await response.json();
            console.log('PageLoader.js - Données reçues :', data);

            if (data.content) {
                this.mainContainer.innerHTML = data.content;
                document.title = data.title || 'TechMobile';
            } else {
                this.mainContainer.innerHTML = '<p>Erreur : Contenu non trouvé.</p>';
            }
        } catch (error) {
            console.error('PageLoader.js - Erreur lors du chargement de la page :', error);
            this.mainContainer.innerHTML = '<p>Erreur lors du chargement de la page : ' + error.message + '</p>';
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    PageLoader.init();
    // Supprime ou corrige l'import dynamique de home.js si présent
    // import('../pages/home.js').catch(err => console.error('Erreur d\'import de home.js :', err));
});