import { MAIN_ID, ROUTER_PATH } from '../config.js';

console.log('PageLoader.js chargé');

let MAIN_CONTAINER = null;

document.addEventListener('DOMContentLoaded', () => {
    MAIN_CONTAINER = document.getElementById(MAIN_ID);
    console.log('MAIN_CONTAINER:', MAIN_CONTAINER);
    if (!MAIN_CONTAINER) {
        console.error('MAIN_CONTAINER non trouvé avec l\'ID:', MAIN_ID);
    }
});

async function fileExists(filepath) {
    try {
        const response = await fetch(filepath, { method: 'HEAD' });
        return response.ok;
    } catch (error) {
        console.warn('fileExists failed for:', filepath, error);
        return false;
    }
}

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
            console.error('Réponse non OK pour', resource, response.status, response.statusText);
            throw new Error(`Erreur HTTP : ${response.status}`);
        }
        const text = await response.text();
        console.log('Réponse brute:', text);
        const data = JSON.parse(text);
        console.log('Données analysées:', data);
        if (data.error) return `<p>${data.error}</p>`;
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

async function loadPageStyle(filename, filepath) {
    const adjustedFilepath = `/TechMobile/Public/css/${filename}.css`; // Chemin absolu
    console.log('Tentative de chargement du CSS:', adjustedFilepath);
    if (await fileExists(adjustedFilepath) && !linkAlreadyExists(filename)) {
        document.head.appendChild(createCSSLink(filename, adjustedFilepath));
    } else {
        console.warn(`Le fichier CSS ${adjustedFilepath} n'existe pas ou est déjà chargé.`);
        // Essayer un chemin relatif comme solution de secours
        const relativePath = `../css/${filename}.css`;
        console.log('Tentative avec chemin relatif:', relativePath);
        if (await fileExists(relativePath) && !linkAlreadyExists(filename)) {
            document.head.appendChild(createCSSLink(filename, relativePath));
        } else {
            console.error(`Échec du chargement du CSS avec chemin relatif: ${relativePath}`);
        }
    }
}

async function loadPageScript(filepath, args = []) {
    const importPath = `/TechMobile/Public/js/pages/${filepath}`; // Chemin absolu
    console.log('Tentative d\'import de:', importPath);
    try {
        const scriptModule = await import(importPath);
        if (typeof scriptModule.init === 'function') {
            console.log('Exécution de init pour:', filepath);
            scriptModule.init(...args);
        } else {
            console.warn('Aucune fonction init trouvée dans:', filepath);
        }
    } catch (err) {
        if (err.message.includes('404')) {
            console.warn(`Le script ${filepath} n'existe pas à ${importPath}`);
            // Essayer un chemin relatif
            const relativePath = `../js/pages/${filepath}`;
            console.log('Tentative d\'import avec chemin relatif:', relativePath);
            try {
                const scriptModule = await import(relativePath);
                if (typeof scriptModule.init === 'function') {
                    console.log('Exécution de init pour:', filepath);
                    scriptModule.init(...args);
                }
            } catch (relativeErr) {
                console.error(`Échec de l'import avec chemin relatif: ${relativePath}`, relativeErr);
            }
        } else {
            console.error(`Erreur lors de l'import de ${filepath}:`, err);
        }
    }
}

function createCSSLink(filename, filepath) {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = filepath;
    link.id = `CSSLink-${filename}`;
    return link;
}

function removeOldLinks(currentFilename) {
    document.head.querySelectorAll('[id^="CSSLink-"]').forEach(link => {
        const oldFileName = link.id.split('-')[1];
        if (oldFileName !== currentFilename) link.remove();
    });
}

function linkAlreadyExists(filename) {
    return document.head.querySelector(`#CSSLink-${filename}`);
}

function clearMainContainer() {
    if (MAIN_CONTAINER) {
        MAIN_CONTAINER.innerHTML = '';
    } else {
        console.error('MAIN_CONTAINER non défini lors du nettoyage');
    }
}

export async function loadPage(page, args = []) {
    console.log('Chargement de la page:', page);
    clearMainContainer();
    removeOldLinks(page);

    const content = await getApiContent(page);
    if (MAIN_CONTAINER) {
        MAIN_CONTAINER.innerHTML = content;
    } else {
        console.error('MAIN_CONTAINER non disponible pour afficher:', content);
        return;
    }
    console.log('HTML affiché:', content);

    await loadPageStyle(page, `/css/${page}.css`); // Chemin absolu pour CSS
    await loadPageScript(`${page}.js`, args);
}

// Gérer la navigation
document.addEventListener('DOMContentLoaded', () => {
    if (!MAIN_CONTAINER) {
        console.error('MAIN_CONTAINER non initialisé');
        return;
    }
    const navLinks = document.querySelectorAll('.nav-link');
    const initialPage = new URLSearchParams(window.location.search).get('resource') || 'home';
    loadPage(initialPage);

    navLinks.forEach(link => {
        link.addEventListener('click', async (e) => {
            e.preventDefault();
            const page = link.getAttribute('href').match(/resource=([^&]+)/)?.[1] || 'home';
            history.pushState({ page }, '', `index.php?resource=${page}`);
            await loadPage(page);
        });
    });

    window.addEventListener('popstate', (e) => {
        const page = e.state?.page || 'home';
        loadPage(page);
    });
});