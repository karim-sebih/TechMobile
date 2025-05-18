import { MAIN_ID } from '../config.js';

console.log('PageLoader.js chargé, MAIN_ID:', MAIN_ID);

const MAIN_CONTAINER = document.getElementById(MAIN_ID);

async function fileExists(filepath) {
    try {
        const response = await fetch(filepath, { method: 'HEAD' });
        return response.ok;
    } catch {
        return false;
    }
}

async function checkSession() {
    try {
        const response = await fetch('router.php?resource=session', {
            method: 'GET',
            credentials: 'same-origin',
        });
        const data = await response.json();
        return data.isLoggedIn && (data.role === 'admin' || data.role === 'moderateur');
    } catch (err) {
        console.error('Erreur lors de la vérification de la session:', err);
        return false;
    }
}

async function getApiContent(resource) {
    console.log('Récupération du contenu pour:', resource);
    try {
        const response = await fetch(`router.php?resource=${encodeURIComponent(resource)}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin',
        });
        if (!response.ok) {
            console.error('Réponse non OK pour', resource, response.status, response.statusText);
            return '<p>Erreur : Page non accessible (Erreur HTTP ' + response.status + ')</p>';
        }

        const data = await response.json();
        if (data.error) {
            console.warn('Erreur retournée par le serveur:', data.error);
            return `<p>${data.error}</p>`;
        }

        if (resource === 'products') {
            if (!Array.isArray(data) || data.length === 0) {
                return '<p>Aucun produit trouvé.</p>';
            }
            return `
                <h1>Nos Produits</h1>
                <div class="products-list">
                    ${data.map(product => `
                        <div class="product-item">
                            <img src="${product.image_url || 'https://via.placeholder.com/150'}" alt="${product.product_name}" class="product-image"><br>
                            <h3>${product.product_name}</h3>
                            <p>Prix : ${product.price} €</p>
                            <button class="add-to-cart" 
                                    data-id="${product.product_id}" 
                                    data-name="${product.product_name}" 
                                    data-price="${product.price}" 
                                    data-image="${product.image_url || 'https://via.placeholder.com/150'}">
                                Ajouter au panier
                            </button>
                        </div>
                    `).join('')}
                </div>
            `;
        }

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
        console.error('Erreur dans getApiContent:', err);
        return '<p>Erreur lors du chargement de la page : ' + err.message + '</p>';
    }
}

async function submitForm(form) {
    const formData = new FormData(form);
    const resource = form.action.split('resource=')[1].split('&')[0];
    const action = form.action.split('action=')[1] || 'add_product';

    console.log('Soumission du formulaire détectée pour:', resource, 'action:', action, 'data:', Object.fromEntries(formData));

    try {
        const response = await fetch(`router.php?resource=${encodeURIComponent(resource)}&action=${encodeURIComponent(action)}`, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
        });
        console.log('Réponse reçue:', response);

        if (!response.ok) {
            console.error('Réponse non OK pour la soumission', response.status, response.statusText);
            return '<p>Erreur : Soumission échouée (Erreur HTTP ' + response.status + ')</p>';
        }

        const data = await response.json();
        console.log('Données JSON:', data);

        if (data.error) {
            console.warn('Erreur retournée par le serveur:', data.error);
            return `<p>${data.error}</p>`;
        }

        if (data.success) {
            console.log('Succès:', data.success);
            await loadPage('admin');
            return `<p>${data.success}</p>`;
        }

        if (data.content) {
            return data.content;
        }

        return '<p>Soumission traitée, voir la console pour détails.</p>';
    } catch (err) {
        console.error('Erreur dans submitForm:', err);
        return '<p>Erreur lors de la soumission : ' + err.message + '</p>';
    }
}

let CURRENT_PAGE = null;

export async function loadPage(page, args = []) {
    console.log('Chargement de la page:', page);
    if (page !== CURRENT_PAGE) {
        clearMainContainer();
        CURRENT_PAGE = page;
    }
    removeOldLinks(page);

    if (page === 'admin') {
        const isAuthorized = await checkSession();
        if (!isAuthorized) {
            console.log('Accès refusé à admin, redirection vers login');
            page = 'login';
        }
    }

    const content = await getApiContent(page);
    if (!content || content.includes('Page non trouvée')) {
        page = '404';
    }

    await displayPageHTML(content);
    await loadPageStyle(page, `css/${page}.css`);
    await loadPageScript(`js/pages/${page}.js`, args);
}

async function displayPageHTML(html) {
    console.log('Affichage du HTML:', html);
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
        if (typeof script.init === 'function') {
            console.log('Exécution de init() pour', filepath);
            script.init(...args);
        }
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

// Gérer la navigation et les formulaires
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

    // Utiliser la délégation d'événements pour capturer les soumissions de formulaire
    document.addEventListener('submit', async (e) => {
        if (e.target.matches('form')) {
            console.log('Événement submit détecté sur:', e.target);
            e.preventDefault();
            const form = e.target;
            const content = await submitForm(form);
            await displayPageHTML(content);
        }
    });

    // Débogage pour les clics sur les boutons
    document.addEventListener('click', (e) => {
        if (e.target.matches('button[type="submit"]')) {
            console.log('Clic sur un bouton de soumission détecté:', e.target);
        }
    });
});