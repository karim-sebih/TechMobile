import { showPageloader, hidePageloader } from './PageLoader.js';

function showPopup() {
    document.getElementById('createPopup').style.display = 'flex';
}

function hidePopup() {
    document.getElementById('createPopup').style.display = 'none';
}

function showMessagePopup(message, isSuccess) {
    const popup = document.getElementById('messagePopup');
    const messageText = document.getElementById('messageText');
    messageText.textContent = message;
    popup.classList.remove('success', 'error');
    popup.classList.add(isSuccess ? 'success' : 'error');
    popup.style.display = 'flex';
}

function hideMessagePopup() {
    document.getElementById('messagePopup').style.display = 'none';
}

// Valider le formulaire avant soumission
function validateForm(formData) {
    const productName = formData.get('product_name');
    const price = parseFloat(formData.get('price'));
    const imageUrl = formData.get('image_url');
    const categoryId = formData.get('category_id');

    // Validation du nom du produit
    if (!productName || productName.trim().length < 3) {
        showMessagePopup('Le nom du produit doit contenir au moins 3 caractères.', false);
        return false;
    }

    // Validation du prix
    if (isNaN(price) || price <= 0) {
        showMessagePopup('Le prix doit être un nombre positif.', false);
        return false;
    }

    // Validation de l'URL de l'image (si fournie)
    if (imageUrl) {
        const urlPattern = /^(https?:\/\/[^\s$.?#].[^\s]*)$/;
        if (!urlPattern.test(imageUrl)) {
            showMessagePopup('L\'URL de l\'image n\'est pas valide.', false);
            return false;
        }
    }

    // Validation de la catégorie
    if (!categoryId) {
        showMessagePopup('Veuillez sélectionner une catégorie.', false);
        return false;
    }

    return true;
}

// Gérer l'ajout de produit via AJAX
document.querySelector('form[action="index.php?resource=admin"]').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);

    // Valider le formulaire
    if (!validateForm(formData)) {
        return;
    }

    try {
        showPageloader(); // Utiliser le pageloader global
        const response = await fetch('index.php?resource=admin', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        hidePopup();
        hidePageloader(); // Masquer le pageloader global
        if (data.success) {
            showMessagePopup('Produit ajouté avec succès !', true);
            // Rafraîchir la liste des produits sans recharger la page
            showPageloader(); // Afficher le pageloader pendant le rafraîchissement
            const productsList = document.querySelector('.products-list');
            const response = await fetch('index.php?resource=admin');
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newProductsList = doc.querySelector('.products-list');
            productsList.innerHTML = newProductsList.innerHTML;
            hidePageloader(); // Masquer le pageloader après le rafraîchissement
        } else {
            showMessagePopup(data.error || 'Erreur lors de l\'ajout.', false);
        }
    } catch (err) {
        hidePopup();
        hidePageloader(); // Masquer le pageloader en cas d'erreur
        showMessagePopup('Erreur : ' + err.message, false);
    }
});