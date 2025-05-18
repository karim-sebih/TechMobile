async function getApiContent(resource) {
    console.log("Chargement de :", resource);
    try {
        const response = await fetch(`index.php?resource=${resource}`, {
            method: 'GET',
            headers: { 'Accept': 'application/json' }
        });
        if (!response.ok) {
            console.error("Erreur réseau :", response.status);
            return "<p>Erreur de chargement</p>";
        }
        const text = await response.text();
        console.log("Réponse reçue :", text);
        const data = JSON.parse(text);
        if (data.content) {
            if (data.title) document.title = data.title;
            return data.content;
        }
        return "<p>Pas de contenu</p>";
    } catch (err) {
        console.error("Erreur :", err);
        return "<p>Erreur : " + err.message + "</p>";
    }
}

async function loadPage(resource) {
    const content = await getApiContent(resource);
    document.getElementById("BodyLine").innerHTML = content;
}