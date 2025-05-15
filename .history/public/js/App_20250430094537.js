document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('nav a');

    navLinks.forEach(link => {
        link.addEventListener('click', async (e) => {
            e.preventDefault();
            const view = link.getAttribute('data-view');

            // Construire l'URL pour le routeur
            const url = `?c=${view}&m=index`;
            
            // Charger le contenu via une requête AJAX
            const response = await fetch(url);
            const content = await response.text();

            // Mettre à jour le contenu de <main>
            document.querySelector('main').innerHTML = content;
        });
    });
});