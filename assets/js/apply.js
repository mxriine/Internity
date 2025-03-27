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

    // Success message display function
    function showSuccessMessage() {
        const successMessage = document.createElement("div");
        successMessage.textContent = "Votre application a bien été soumise.";
        successMessage.style.position = "fixed";
        successMessage.style.top = "20px";
        successMessage.style.left = "50%";
        successMessage.style.transform = "translateX(-50%)";
        successMessage.style.backgroundColor = "green";
        successMessage.style.color = "white";
        successMessage.style.padding = "15px 30px";
        successMessage.style.borderRadius = "5px";
        successMessage.style.zIndex = "1000";
        successMessage.style.textAlign = "center";
        document.body.appendChild(successMessage);

        // Remove the success message after 5 seconds
        setTimeout(() => {
            successMessage.remove();
        }, 5000);
    }

    // Form submission handler
    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent form submission until validation passes
        clearErrors();

        let isValid = true;

        // Validate Name
        const nameInput = document.getElementById("name");
        if (!nameInput.value.trim()) {
            showError("nameError", "Le nom et prénom sont requis.");
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
        const coverLetterInput = document.getElementById("cover-letter");
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

        // If all validations pass, submit the form
        if (isValid) {
            // Simulate form submission (you can replace this with actual backend logic)
            setTimeout(() => {
                showSuccessMessage(); // Show success message
                form.reset(); // Reset the form fields
                clearErrors(); // Clear any remaining error messages
            }, 500); // Simulate a slight delay for submission
        }
    });
});