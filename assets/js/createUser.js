document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.create-account-form');

    // Fonction pour échapper les caractères spéciaux (prévention XSS)
    function escapeHTML(str) {
        return str.replace(/[&<>"'@]/g, (match) => {
            const escapeMap = {
                '&': '&amp;',
                '<': '<',
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
        const userSurname = form.querySelector('#user_surname').value.trim();
        const userName = form.querySelector('#user_name').value.trim();
        const userEmail = form.querySelector('#user_email').value.trim();
        const userPassword = form.querySelector('#user_password').value.trim();
        const userRole = form.querySelector('#user_role').value;

        // Regex pour exclure les caractères dangereux (<, >, @, etc.)
        const forbiddenCharsRegex = /[<>@]/;

        // Validation du nom
        if (!userSurname || userSurname.length < 2 || userSurname.length > 50 || forbiddenCharsRegex.test(userSurname)) {
            alert('Le nom doit contenir entre 2 et 50 caractères et ne pas inclure les caractères spéciaux.');
            isValid = false;
        }

        // Validation du prénom
        if (!userName || userName.length < 2 || userName.length > 50 || forbiddenCharsRegex.test(userName)) {
            alert('Le prénom doit contenir entre 2 et 50 caractères et ne pas inclure les caractères spéciaux.');
            isValid = false;
        }

        // Validation de l'email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!userEmail || !emailRegex.test(userEmail)) {
            alert('Veuillez entrer une adresse email valide.');
            isValid = false;
        }

        // Validation du mot de passe
        if (!userPassword || userPassword.length < 5 || userPassword.length > 255 || forbiddenCharsRegex.test(userPassword)) {
            alert('Le mot de passe doit contenir entre 5 et 255 caractères et ne pas inclure les caractères spéciaux.');
            isValid = false;
        }

        // Validation du rôle
        if (!userRole || !['Etudiant', 'Pilote', 'Admin'].includes(userRole)) {
            alert('Veuillez sélectionner un rôle valide.');
            isValid = false;
        }

        // Échapper les données pour éviter les injections XSS
        form.querySelector('#user_surname').value = escapeHTML(userSurname);
        form.querySelector('#user_name').value = escapeHTML(userName);
        form.querySelector('#user_email').value = escapeHTML(userEmail);
        form.querySelector('#user_password').value = escapeHTML(userPassword);

        // Empêcher la soumission si le formulaire n'est pas valide
        if (!isValid) {
            event.preventDefault(); // Bloque la soumission
        }
    });
});