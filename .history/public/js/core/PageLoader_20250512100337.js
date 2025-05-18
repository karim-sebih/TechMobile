import { MAIN_CONTAINER, ROUTER_PATH } from './config.js'; // Chemin correct pour config.js

// Ajout de débogage
console.log('PageLoader.js chargé, MAIN_CONTAINER:', MAIN_CONTAINER);

async function fileExists(filepath) {
    try {
        const response = await fetch(filepath, { method: 'HEAD' });
        return response.ok;
    } catch {
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
        const data = await response.json();
        console.log('Données reçues:', data);
        if (data.error) return `<p>${data.error}</p>`;

        if (data.content) {
            if (data.title) document.title = data.title;
            return data.content;
        }
        return `<p>Contenu non défini pour ${resource}</p>`;
    } catch (err) {
        console.error('Erreur dans getApiContent:', err);
        return `<p>Erreur lors du chargement de la page : ${err.message}</p>`;
    }
}

async function loadPageStyle(filename, filepath) {
    if (await fileExists(filepath) && !linkAlreadyExists(filename)) {
        document.head.appendChild(createCSSLink(filename, filepath));
    }
}

async function loadPageScript(filepath, args = []) {
    try {
        const script = await import(`../../${filepath}`); // Chemin corrigé pour pointer vers Public/js/pages/
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
    document.querySelector(MAIN_CONTAINER).innerHTML = '';
}

export async function loadPage(page, args = []) {
    console.log('Chargement de la page:', page);
    clearMainContainer();
    removeOldLinks(page);

    const content = await getApiContent(page);
    document.querySelector(MAIN_CONTAINER).innerHTML = content;
    console.log('HTML affiché:', content);

    await loadPageStyle(page, `css/${page}.css`);
    await loadPageScript(`/Public/js/core${page}.js`, args); // Chemin corrigé pour home.js
}

document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM chargé, initialisation de PageLoader');
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