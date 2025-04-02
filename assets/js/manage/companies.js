document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.create-company-form');

    // Fonction pour échapper les caractères spéciaux (prévention XSS)
    function escapeHTML(str) {
        return str.replace(/[&<>"'@]/g, (match) => {
            const escapeMap = {
                '&': '&amp;',
                '<': '<',
                '>': '>',
                '"': '&quot;',
                "'": '&#39;',
                '@': '&#64;'
            };
            return escapeMap[match];
        });
    }

    // Validation du formulaire
    form.addEventListener('submit', async (event) => {
        let isValid = true;

        // Récupérer les valeurs des champs
        const companyName = form.querySelector('#company_name').value.trim();
        const companyDesc = form.querySelector('#company_desc').value.trim();
        const companyBusiness = form.querySelector('#company_business').value.trim();
        const companyEmail = form.querySelector('#company_email').value.trim();
        const companyPhone = form.querySelector('#company_phone').value.trim();
        const postalCode = form.querySelector('#company_postal_code').value.trim();
        const city = form.querySelector('#company_city').value.trim();

        // Regex pour exclure les caractères dangereux (&, <, >, @, etc.)
        const forbiddenCharsRegex = /[&<>@]/;

        // Validation du nom de l'entreprise
        if (!companyName || companyName.length < 3 || companyName.length > 100 || forbiddenCharsRegex.test(companyName)) {
            alert('Le nom de l\'entreprise doit contenir entre 3 et 100 caractères et ne pas inclure les caractères spéciaux.');
            isValid = false;
        }

        // Validation de la description
        if (companyDesc && (companyDesc.length > 255 || forbiddenCharsRegex.test(companyDesc))) {
            alert('La description ne doit pas dépasser 255 caractères et ne pas inclure les caractères spéciaux.');
            isValid = false;
        }

        // Validation du secteur d'activité
        if (!companyBusiness || companyBusiness.length < 3 || companyBusiness.length > 50 || forbiddenCharsRegex.test(companyBusiness)) {
            alert('Le secteur d\'activité doit contenir entre 3 et 50 caractères et ne pas inclure les caractères spéciaux.');
            isValid = false;
        }

        // Validation de l'email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!companyEmail || !emailRegex.test(companyEmail)) {
            alert('Veuillez entrer une adresse email valide.');
            isValid = false;
        }

        // Validation du téléphone
        const phoneRegex = /^[0-9+]{10,15}$/;
        if (companyPhone && !phoneRegex.test(companyPhone)) {
            alert('Le numéro de téléphone doit contenir entre 10 et 15 chiffres ou le caractère "+".');
            isValid = false;
        }

        // Validation du code postal et de la ville
        if (postalCode && city) {
            const isPostalCodeValid = await validatePostalCode(postalCode, city);

            if (!isPostalCodeValid) {
                alert('Le code postal ne correspond pas à la ville. Veuillez vérifier vos informations.');
                isValid = false;
            }
        }

        // Échapper les données pour éviter les injections XSS
        form.querySelector('#company_name').value = escapeHTML(companyName);
        form.querySelector('#company_desc').value = escapeHTML(companyDesc);
        form.querySelector('#company_business').value = escapeHTML(companyBusiness);

        // Empêcher la soumission si le formulaire n'est pas valide
        if (!isValid) {
            event.preventDefault(); // Bloque la soumission
        }
    });

});

//Spécial à DeleteCompanies.php


document.addEventListener('DOMContentLoaded', function () {
    const deleteForm = document.querySelector('.delete-company-form');
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
        deleteForm.submit(); // Soumettre le formulaire pour supprimer l'entreprise
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

document.addEventListener("DOMContentLoaded", function () {
    const prefixSelect = document.getElementById("phone_prefix");

    const countries = [
        { code: "fr", name: "France", dial: "+33", flag: "🇫🇷" },
        { code: "es", name: "Espagne", dial: "+34", flag: "🇪🇸" },
        { code: "it", name: "Italie", dial: "+39", flag: "🇮🇹" },
        { code: "uk", name: "Royaume-Uni", dial: "+44", flag: "🇬🇧" },
        { code: "de", name: "Allemagne", dial: "+49", flag: "🇩🇪" },
        { code: "us", name: "États-Unis", dial: "+1", flag: "🇺🇸" },
        { code: "be", name: "Belgique", dial: "+32", flag: "🇧🇪" },
        { code: "ch", name: "Suisse", dial: "+41", flag: "🇨🇭" }

    ];

    countries.forEach(country => {
        const option = document.createElement("option");
        option.value = country.dial;
        option.textContent = `${country.flag} ${country.name} (${country.dial})`;
        prefixSelect.appendChild(option);
    });

    // Optionnel : définir France comme valeur par défaut
    prefixSelect.value = "+33";
});