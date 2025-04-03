document.addEventListener("DOMContentLoaded", () => {
    const cookieBanner = document.getElementById("cookie-consent-banner");
    const acceptButton = document.getElementById("accept-cookies");
    const rejectButton = document.getElementById("reject-cookies");

    // Vérifier si le consentement a déjà été donné
    const userConsent = getCookie("user_consent");
    if (!userConsent) {
        // Afficher le bandeau si aucun consentement n'a été donné
        cookieBanner.classList.remove("hidden");
    }

    // Accepter les cookies
    acceptButton.addEventListener("click", () => {
        setCookie("user_consent", "accepted", 365); // Stocker le consentement pour 1 an
        cookieBanner.classList.add("hidden");
    });

    // Refuser les cookies
    rejectButton.addEventListener("click", () => {
        setCookie("user_consent", "rejected", 365); // Stocker le refus pour 1 an
        cookieBanner.classList.add("hidden");
    });
});

// Fonction pour définir un cookie
function setCookie(name, value, days) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = "expires=" + date.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

// Fonction pour récupérer un cookie
function getCookie(name) {
    const nameEQ = name + "=";
    const cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i].trim();
        if (cookie.indexOf(nameEQ) === 0) {
            return cookie.substring(nameEQ.length, cookie.length);
        }
    }
    return null;
}