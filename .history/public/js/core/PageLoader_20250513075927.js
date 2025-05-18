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
                'Accept': 'application/json'
            },
        });
        if (!response.ok) {
            console.error('Réponse non OK pour', resource, response.status, response.statusText);
            throw new Error(`Erreur HTTP : ${response.status}`);
        }
        const text = await response.text();
        console.log('Réponse brute:', text.substring(0, 100) + '...');

        // Tenter de parser comme JSON
        try {
            const data = JSON.parse(text);
            console.log('Données analysées:', data);
            if (data.error) {
                console.error('Erreur renvoyée par le serveur:', data.error);
                return `<p>Erreur : ${data.error}</p>`;
            }
            if (data.content) {
                if (data.title) document.title = data.title;
                return data.content;
            }
            return `<p>Contenu non défini pour ${resource}</p>`;
        } catch (e) {
            console.warn('Réponse non JSON, extraction du contenu de main#BodyLine...');
            // Créer un élément temporaire pour parser le HTML
            const parser = new DOMParser();
            const doc = parser.parseFromString(text, 'text/html');
            const mainContent = doc.querySelector(`#${MAIN_ID}`);
            if (mainContent) {
                const content = mainContent.innerHTML;
                console.log('Contenu extrait de main#BodyLine:', content.substring(0, 100) + '...');
                // Extraire le titre de la page si disponible
                const title = doc.querySelector('title')?.textContent || `Page ${resource}`;
                document.title = title;
                return content;
            } else {
                console.error('Aucun élément main#BodyLine trouvé dans la réponse');
                return `<p>Erreur : Contenu principal non trouvé pour ${resource}</p>`;
            }
        }
    } catch (err) {
        console.error('Erreur dans getApiContent:', err);
        return `<p>Erreur lors du chargement : ${err.message}</p>`;
    }
}

async function loadPageStyle(filename, filepath) {
    if (await fileExists(filepath)) {
        if (!linkAlreadyExists(filename)) {
            document.head.append