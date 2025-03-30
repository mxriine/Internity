document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("applicationForm");

    // Validation functions
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function isValidPhone(phone) {
        const phoneRegex = /^[+]?[(]?[0-9]{1,4}[)]?[-\s./0-9]*$/;
        return phoneRegex.test(phone);
    }

    function isSafeMessage(message) {
        const unsafeCharsRegex = /[<>@]/g; // Block <, >, and @
        return !unsafeCharsRegex.test(message);
    }

    // Error display function
    function showError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        errorElement.textContent = message;
    }

    function clearErrors() {
        document.querySelectorAll(".error-message").forEach((element) => {
            element.textContent = "";
        });
    }

    // Form submission handler
    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Empêche temporairement le submit
        clearErrors();

        let isValid = true;

        // Validate Surname
        const surnameInput = document.getElementById("surname");
        if (!surnameInput.value.trim()) {
            showError("surnameError", "Le nom est requis.");
            isValid = false;
        }

        // Validate Name
        const nameInput = document.getElementById("name");
        if (!nameInput.value.trim()) {
            showError("nameError", "Le prénom est requis.");
            isValid = false;
        }

        // Validate Email
        const emailInput = document.getElementById("email");
        if (!emailInput.value.trim()) {
            showError("emailError", "L'adresse e-mail est requise.");
            isValid = false;
        } else if (!isValidEmail(emailInput.value)) {
            showError("emailError", "Veuillez entrer une adresse e-mail valide.");
            isValid = false;
        }

        // Validate Phone
        const phoneInput = document.getElementById("phone");
        if (!phoneInput.value.trim()) {
            showError("phoneError", "Le numéro de téléphone est requis.");
            isValid = false;
        } else if (!isValidPhone(phoneInput.value)) {
            showError("phoneError", "Veuillez entrer un numéro de téléphone valide.");
            isValid = false;
        }

        // Validate CV Upload
        const cvInput = document.getElementById("cv");
        if (!cvInput.files.length) {
            showError("cvError", "Veuillez importer un fichier PDF pour votre CV.");
            isValid = false;
        } else if (cvInput.files[0].type !== "application/pdf") {
            showError("cvError", "Seuls les fichiers PDF sont acceptés pour le CV.");
            isValid = false;
        }

        // Validate Cover Letter Upload
        const coverLetterInput = document.getElementById("coverletter");
        if (!coverLetterInput.files.length) {
            showError("coverLetterError", "Veuillez importer un fichier PDF pour votre lettre de motivation.");
            isValid = false;
        } else if (coverLetterInput.files[0].type !== "application/pdf") {
            showError("coverLetterError", "Seuls les fichiers PDF sont acceptés pour la lettre de motivation.");
            isValid = false;
        }

        // Validate Message
        const messageInput = document.getElementById("message");
        if (messageInput.value.trim() && !isSafeMessage(messageInput.value)) {
            showError("messageError", "Le message contient des caractères non autorisés (<, >, @).");
            isValid = false;
        }

        // Submit form if valid
        if (isValid) {
            form.submit(); // Envoie réel vers le back (Apply.php)
        }
    });
});
