document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("applicationForm");

    // Écouter la soumission du formulaire
    form.addEventListener("submit", (event) => {
        event.preventDefault(); // Empêcher la soumission par défaut

        // Réinitialiser les messages d'erreur
        resetErrors();

        // Récupérer les valeurs des champs
        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const phone = document.getElementById("phone").value.trim();
        const cv = document.getElementById("cv").files[0];
        const coverLetter = document.getElementById("cover-letter").files[0];
        const message = document.getElementById("message").value.trim();

        // Validation des champs
        let isValid = true;

        if (!validateName(name)) {
            showError("nameError", "Le nom et prénom sont requis.");
            isValid = false;
        }

        if (!validateEmail(email)) {
            showError("emailError", "L'adresse e-mail est invalide.");
            isValid = false;
        }

        if (!validatePhone(phone)) {
            showError("phoneError", "Le numéro de téléphone est invalide.");
            isValid = false;
        }

        if (!validateFile(cv, "cvError")) {
            isValid = false;
        }

        if (!validateFile(coverLetter, "coverLetterError")) {
            isValid = false;
        }

        if (!validateMessage(message)) {
            showError("messageError", "Le message contient des caractères non autorisés.");
            isValid = false;
        }

        // Si tout est valide, soumettre le formulaire
        if (isValid) {
            alert("Formulaire soumis avec succès !");
            form.submit(); // Soumettre le formulaire
        }
    });

    // Fonction pour réinitialiser les messages d'erreur
    function resetErrors() {
        const errorMessages = document.querySelectorAll(".error-message");
        errorMessages.forEach((msg) => {
            msg.textContent = "";
            msg.style.display = "none";
        });
    }

    // Fonction pour afficher un message d'erreur
    function showError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        errorElement.textContent = message;
        errorElement.style.display = "block";
    }

    // Validation du nom et prénom
    function validateName(name) {
        const nameRegex = /^[a-zA-ZÀ-ÿ\s'-]{2,50}$/; // Autorise les lettres, accents, espaces, apostrophes et tirets
        return nameRegex.test(name);
    }

    // Validation de l'email
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Format standard d'une adresse email
        return emailRegex.test(email);
    }

    // Validation du numéro de téléphone
    function validatePhone(phone) {
        const phoneRegex = /^[+]?[0-9\s\-().]{7,15}$/; // Autorise +, chiffres, espaces, tirets, et parenthèses
        return phoneRegex.test(phone);
    }

    // Validation des fichiers PDF
    function validateFile(file, errorId) {
        if (!file) {
            showError(errorId, "Ce fichier est requis.");
            return false;
        }
        if (file.type !== "application/pdf") {
            showError(errorId, "Seuls les fichiers PDF sont autorisés.");
            return false;
        }
        return true;
    }

    // Validation du message (protection contre XSS)
    function validateMessage(message) {
        const sanitizedMessage = escapeHtml(message); // Échapper les caractères spéciaux
        document.getElementById("message").value = sanitizedMessage; // Mettre à jour le champ avec la version échappée

        // Vérifier si le message contient des caractères suspects
        const suspiciousChars = /[<>{}()[\]\\/|";':]/; // Caractères potentiellement dangereux
        return !suspiciousChars.test(sanitizedMessage);
    }

    // Échapper les caractères spéciaux pour prévenir les attaques XSS
    function escapeHtml(str) {
        if (!str) return ""; // Gère les chaînes vides ou null
        return str.replace(/[&<>"'`=\/]/g, (char) => {
            const escapeMap = {
                "&": "&amp;",
                "<": "<",
                ">": ">",
                '"': "&quot;",
                "'": "&#39;",
                "/": "&#x2F;",
                "`": "&#x60;",
                "=": "&#x3D;"
            };
            return escapeMap[char] || char; // Retourne le caractère original s'il n'est pas dans la liste
        });
    }
});