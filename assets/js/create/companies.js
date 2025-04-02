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