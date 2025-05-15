document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('nav a');

    navLinks.forEach(link => {
        link.addEventListener('click', async (e) => {
            e.preventDefault();
            const view = link.getAttribute('data-view');
            console.log(`Clic détecté sur le lien : ${view}`); // Ajout de débogage

            const url = `api.php?c=${view}&m=index`;
            const response = await fetch(url);
            const content = await response.text();

            document.querySelector('main').innerHTML = content;
        });
    });
});