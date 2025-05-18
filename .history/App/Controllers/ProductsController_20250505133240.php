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
        return `
            <h1>${data.title || 'Page'}</h1>
            <div>${data.content || 'Contenu non défini'}</div>
        `;
    } catch (err) {
        console.error('Erreur lors de la récupération des données :', err);
        return '<p>Erreur lors du chargement de la page.</p>';
    }
}