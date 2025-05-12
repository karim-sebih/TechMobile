// Public/js/App.js

// Définir MAIN_CONTAINER comme l'élément <main>
const MAIN_CONTAINER = document.querySelector('main');

let CURRENT_PAGE = null;

// Fonction pour vérifier si un fichier existe (simule fileExists)
async function fileExists(filepath) {
    try {
        const response = await fetch(filepath, { method: 'HEAD' });
        return response.ok;
    } catch {
        return false;
    }
}

// Fonction pour récupérer le contenu d'un fichier (simule getTextFileContent)
async function getTextFileContent(filepath) {
    try {
        const response = await fetch(filepath);
        if (!response.ok) return null;
        return await response.text();
    } catch {
        return null;
    }
}

export async function loadPage(page, args = []) {
    if (page !== CURRENT_PAGE) {
        clearMainContainer();
        CURRENT_PAGE = page;
    }
    removeOldLinks(page);

    // Charger le contenu via api.php
    const content = await getTextFileContent(`api.php?c=${page}&m=index`);
    if (!content) page = '404';

    await displayPageHTML(content || '<p>Erreur 404</p>');
    await loadPageStyle(page, `css/${page}.css`);
    await loadPageScript(`js/pages/${page}.js`, args);
}

async function displayPageHTML(html) {
    MAIN_CONTAINER.innerHTML = html;
}

async function loadPageStyle(filename, filepath) {
    if (await fileExists(filepath) && !linkAlreadyExists(filename)) {
        document.head.appendChild(createCSSLink(filename, filepath));
    }
}

async function loadPageScript(filepath, args) {
    try {
        const script = await import(`./pages/${filepath}`);
        if (typeof script.init === 'function') script.init(...args);
    } catch (err) {
        if (err.message.includes('404')) {
            console.warn(`Le script ${filepath} n'existe pas`);
        } else {
            console.error(`Erreur dans le fichier ${filepath}`, err);
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
    MAIN_CONTAINER.innerHTML = '';
}

// Gérer la navigation
document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('.nav-link');

    // Charger la page initiale
    const initialPage = new URLSearchParams(window.location.search).get('c') || 'home';
    loadPage(initialPage);

    // Gérer les clics sur les liens
    navLinks.forEach(link => {
        link.addEventListener('click', async (e) => {
            e.preventDefault();
            const page = link.getAttribute('href').match(/c=([^&]+)/)[1];
            history.pushState({ page }, '', `index.php?c=${page}&m=index`);
            await loadPage(page);
        });
    });

    // Gérer les retours en arrière/avant
    window.addEventListener('popstate', (e) => {
        const page = e.state?.page || 'home';
        loadPage(page);
    });
});