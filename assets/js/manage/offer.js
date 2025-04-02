document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.create-offer-form');

    // Fonction pour échapper les caractères spéciaux (prévention XSS)
    function escapeHTML(str) {
        return str.replace(/[&<>"'@]/g, (match) => {
            const escapeMap = {
                '&': '&amp;',
                '<': '<', // Correction : ces caractères doivent être échappés correctement
                '>': '>',
                '"': '&quot;',
                "'": '&#39;',
                '@': '&#64;' // Échappe le caractère @
            };
            return escapeMap[match];
        });
    }

    // Validation du formulaire
    form.addEventListener('submit', (event) => {
        let isValid = true;

        // Récupérer les valeurs des champs
        const offerTitle = form.querySelector('#offer_title').value.trim();
        const offerDesc = form.querySelector('#offer_desc').value.trim();
        const offerStart = form.querySelector('#offer_start').value; // Récupérer la date de début
        const offerEnd = form.querySelector('#offer_end').value; // Récupérer la date de fin

        // Regex pour exclure les caractères dangereux (<, >, @, etc.)
        const forbiddenCharsRegex = /[<>@]/;

        // Validation du titre
        if (!offerTitle || offerTitle.length < 5 || offerTitle.length > 50 || forbiddenCharsRegex.test(offerTitle)) {
            alert('Le titre doit contenir entre 5 et 50 caractères et ne pas inclure les caractères spéciaux.');
            isValid = false;
        }

        // Validation de la description
        if (!offerDesc || offerDesc.length < 10 || offerDesc.length > 255 || forbiddenCharsRegex.test(offerDesc)) {
            alert('La description doit contenir entre 10 et 255 caractères et ne pas inclure les caractères spéciaux.');
            isValid = false;
        }

        // Validation des dates
        if (!offerStart || !offerEnd) {
            alert('Les dates de début et de fin doivent être renseignées.');
            isValid = false;
        } else {
            const startDate = new Date(offerStart);
            const endDate = new Date(offerEnd);

            if (startDate >= endDate) {
                alert('La date de début doit précéder la date de fin.');
                isValid = false;
            }
        }

        // Échapper les données pour éviter les injections XSS
        form.querySelector('#offer_title').value = escapeHTML(offerTitle);
        form.querySelector('#offer_desc').value = escapeHTML(offerDesc);

        // Empêcher la soumission si le formulaire n'est pas valide
        if (!isValid) {
            event.preventDefault(); // Bloque la soumission
        }
    });
});







//Spécial à DeleteOffer.php

document.addEventListener('DOMContentLoaded', function () {
    const deleteForm = document.querySelector('.delete-offer-form');
    const modal = document.getElementById('confirmationModal');
    const confirmButton = document.getElementById('confirmDelete');
    const cancelButton = document.getElementById('cancelDelete');

    // Afficher le modal lors du clic sur le bouton de suppression
    deleteForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Empêcher la soumission immédiate
        modal.style.display = 'flex'; // Afficher le modal
    });

    // Confirmer la suppression
    confirmButton.addEventListener('click', function () {
        deleteForm.submit(); // Soumettre le formulaire pour supprimer l'offre
    });

    // Annuler la suppression
    cancelButton.addEventListener('click', function () {
        modal.style.display = 'none'; // Masquer le modal
    });

    // Fermer le modal en cliquant en dehors
    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});