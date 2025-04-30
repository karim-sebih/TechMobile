import { MAIN_CONTAINER } from '/config.js';
import { fileExists, getTextFileContent } from "../utils/File.js";

let CURRENT_PAGE = null;

export async function loadPage(page, args) {
    if (page !== CURRENT_PAGE) {
        clearMainContainer();
        CURRENT_PAGE = page;
    }
    removeOldLinks(page);

    if (!await fileExists(`./src/Views/pages/${page}/index.html`))
        page = '404';

    await displayPageHTML(`./src/Views/pages/${page}/index.html`)
    await loadPageStyle(page, `./src/Views/css/${page}.css`);
    await loadPageScript(`../../pages/${page}/index.js`, args);
}

async function displayPageHTML(filepath) {
    const html = await getTextFileContent(filepath);
    MAIN_CONTAINER.innerHTML = html ?? '<p>Erreur 404</p>';
}

async function loadPageStyle(filename, filepath) {
    if (await fileExists(filepath) && ! linkAlreadyExists(filename))
        document.head.appendChild(createCSSLink(filename, filepath));
}

async function loadPageScript(filepath, args) {
    try {
        const script = await import(filepath);
        if (typeof script.init === 'function') script.init(...args);
    } catch (err) {
        if (err.message.includes('404'))
            console.warn(`Le script ${filepath} n'existe pas`);
        else
            console.error(`Erreur dans le fichier ${filepath}`, err)
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
    return (document.head.querySelector(`#CSSLink-${filename}`));
}

function clearMainContainer() {
    MAIN_CONTAINER.innerHTML = '';
}