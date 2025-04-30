document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('nav a');

    navLinks.forEach(link => {
        link.addEventListener('click', async (e) => {
            e.preventDefault();
            const view = link.getAttribute('data-view');
            console.log(`Clic détecté sur le lien : ${view}`);

            const url = `api.php?c=${view}&m=index`;
            console.log(`URL de la requête : ${url}`); // Ajout de débogage

            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`Erreur HTTP : ${response.status}`);
                }
                const content = await response.text();
                console.log(`Contenu reçu : ${content}`); // Ajout de débogage

                document.querySelector('main').innerHTML = content;
            } catch (error) {
                console.error(`Erreur lors de la requête : ${error}`);
            }
        });
    });
});