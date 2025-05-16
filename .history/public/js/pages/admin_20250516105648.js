document.addEventListener("DOMContentLoaded", function () {
    // Hide popup initially
    document.querySelector(".popup").style.display = "none";

    // Show popup when "Add Product" button is clicked
    document.querySelector(".btnadd").addEventListener("click", function (e) {
        e.preventDefault();
        document.querySelector(".popup").style.display = "flex";
    });

    // Hide popup when close button is clicked
    document.querySelector(".close").addEventListener("click", function () {
        document.querySelector(".popup").style.display = "none";
    });
});

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
    const price = parseFloat(formData.get('product_price'));
    const description = formData.get('product_description');
    const ingredients = formData.get('product_ingredient');
    const categoryId = formData.get('product_categorie');
    const image = formData.get('product_image');

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

    // Validation de la description
    if (!description || description.trim().length < 5) {
        showMessagePopup('La description doit contenir au moins 5 caractères.', false);
        return false;
    }

    // Validation des ingrédients
    if (!ingredients || ingredients.trim().length < 5) {
        showMessagePopup('Les ingrédients doivent contenir au moins 5 caractères.', false);
        return false;
    }

    // Validation de la catégorie
    if (!categoryId) {
        showMessagePopup('Veuillez sélectionner une catégorie.', false);
        return false;
    }

    // Validation de l'image (facultatif, mais si présente, vérifier l'extension)
    if (image && image.size > 0) {
        const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
        if (!allowedTypes.includes(image.type)) {
            showMessagePopup('L\'image doit être au format PNG, JPEG ou JPG.', false);
            return false;
        }
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
        const response = await fetch('index.php?resource=admin', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        document.querySelector(".popup").style.display = "none";
        if (data.success) {
            showMessagePopup('Produit ajouté avec succès !', true);
            // Rafraîchir la liste des produits sans recharger la page
            const productsList = document.querySelector('.products-list');
            const response = await fetch('index.php?resource=admin');
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newProductsList = doc.querySelector('.products-list');
            productsList.innerHTML = newProductsList.innerHTML;
        } else {
            showMessagePopup(data.error || 'Erreur lors de l\'ajout.', false);
        }
    } catch (err) {
        document.querySelector(".popup").style.display = "none";
        showMessagePopup('Erreur : ' + err.message, false);
    }
});