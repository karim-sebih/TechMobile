import { MAIN_ID, ROUTER_PATH, DEFAULT_PAGE_NAME } from './config.js';

console.log('PageLoader.js chargé');

// Définir le conteneur principal
let MAIN_CONTAINER = null;

document.addEventListener('DOMContentLoaded', () => {
    MAIN_CONTAINER = document.getElementById(MAIN_ID);
    if (!MAIN_CONTAINER) {
        console.error('MAIN_CONTAINER non trouvé avec l\'ID:', MAIN_ID);
        return;
    }
    // Charger la page initiale
    const initialPage = new URLSearchParams(window.location.search).get('resource') || DEFAULT_PAGE_NAME;
    loadPage(initialPage);
});

// Fonction pour récupérer le contenu via router.php
async function getApiContent(resource) {
    console.log('Récupération du contenu pour:', resource);
    try {
        const response = await fetch(`${ROUTER_PATH}?resource=${encodeURIComponent(resource)}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });
        if (!response.ok) {
            throw new Error(`Erreur HTTP : ${response.status} ${response.statusText}`);
        }
        const data = await response.json();
        console.log('Données reçues:', data);
        if (data.error) {
            return `<p>Erreur : ${data.error}</p>`;
        }
        if (data.content) {
            if (data.title) document.title = data.title;
            return data.content;
        }
        return `<p>Contenu non défini pour ${resource}</p>`;
    } catch (err) {
        console.error('Erreur dans getApiContent:', err);
        return `<p>Erreur lors du chargement : ${err.message}</p>`;
    }
}

// Fonction pour charger un script JS dynamiquement
async function loadPageScript(page, args = []) {
    try {
        const scriptPath = `/techmania/TechMobile/Public/js/${page}.js`; // Chemin corrigé
        console.log('Chargement du script:', scriptPath);
        const scriptModule = await import(scriptPath);
        if (typeof scriptModule.init === 'function') {
            scriptModule.init(...args);
        } else {
            console.warn('Aucune fonction init trouvée dans:', page);
        }
    } catch (err) {
        console.error(`Erreur lors du chargement du script ${page}.js:`, err);
    }
}

// Fonction pour nettoyer le conteneur principal
function clearMainContainer() {
    if (MAIN_CONTAINER) {
        MAIN_CONTAINER.innerHTML = '';
    } else {
        console.error('MAIN_CONTAINER non défini lors du nettoyage');
    }
}

// Fonction principale pour charger une page
export async function loadPage(page, args = []) {
    console.log('Chargement de la page:', page);
    clearMainContainer();

    const content = await getApiContent(page);
    if (MAIN_CONTAINER) {
        MAIN_CONTAINER.innerHTML = content;
    } else {
        console.error('MAIN_CONTAINER non disponible pour afficher:', content);
        return;
    }

    // Charger le script JS associé à la page
    await loadPageScript(page, args);
}

// Gérer la navigation via les liens
document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', async (e) => {
            e.preventDefault();
            const page = link.getAttribute('href')?.match(/resource=([^&]+)/)?.[1] || DEFAULT_PAGE_NAME;
            history.pushState({ page }, '', `index.php?resource=${page}`);
            await loadPage(page);
        });
    });

    // Gérer la navigation arrière/avant
    window.addEventListener('popstate', (e) => {
        const page = e.state?.page || DEFAULT_PAGE_NAME;
        loadPage(page);
    });
});