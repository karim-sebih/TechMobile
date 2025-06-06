import { MAIN_CONTAINER } from './config.js';

// Fonction pour vérifier si un fichier existe
async function fileExists(filepath) {
    try {
        const response = await fetch(filepath, { method: 'HEAD' });
        return response.ok;
    } catch {
        return false;
    }
}

// Fonction pour récupérer le contenu via l'API
async function getApiContent(resource) {
    try {
        const response = await fetch(`router.php?resource=${encodeURIComponent(resource)}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });
        if (!response.ok) return null;
        const data = await response.json();
        if (data.error) return `<p>${data.error}</p>`;

        // Cas spécifique pour les produits
        if (resource === 'products') {
            if (!Array.isArray(data) || data.length === 0) {
                return '<p>Aucun produit trouvé.</p>';
            }
            return `
                <h1>Nos Produits</h1>
                <div class="products-list">
                    ${data.map(product => `
                        <div class="product-item">
                            <h3>${product.name}</h3>
                            <p>Prix : ${product.price} €</p>
                            <button class="add-to-cart" data-id="${product.id}" data-name="${product.name}" data-price="${product.price}">Ajouter au panier</button>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        // Cas général : extraire le contenu HTML du JSON
        if (data.content) {
            if (data.title) {
                document.title = data.title;
            }
            return data.content;
        }

        return `
            <h1>${resource.charAt(0).toUpperCase() + resource.slice(1)}</h1>
            <div>${data.message || data.info || 'Contenu non défini'}</div>
        `;
    } catch (err) {
        console.error('Erreur lors de la récupération des données :', err);
        return '<p>Erreur lors du chargement de la page.</p>';
    }
}

let CURRENT_PAGE = null;

export async function loadPage(page, args = []) {
    if (page !== CURRENT_PAGE) {
        clearMainContainer();
        CURRENT_PAGE = page;
    }
    removeOldLinks(page);

    const content = await getApiContent(page);
    if (!content) page = '404';

    await displayPageHTML(content);
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
        const script = await import(`./${filepath}`);
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


document.addEventListener('DOMContentLoaded', () => {
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