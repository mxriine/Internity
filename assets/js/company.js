document.addEventListener("DOMContentLoaded", () => {
    const evaluateButton = document.getElementById("evaluateButton");
    const evaluationPopup = document.getElementById("evaluationPopup");
    const starsContainer = document.getElementById("starsContainer");
    const submitRatingButton = document.getElementById("submitRating");
    const selectedRatingDisplay = document.getElementById("selectedRating");

    let selectedRating = 0;

    // Ouvrir le popup lorsqu'on clique sur "Évaluer"
    evaluateButton.addEventListener("click", () => {
        evaluationPopup.classList.remove("hidden");
    });

    // Gestion du clic sur les étoiles
    starsContainer.addEventListener("click", (event) => {
        if (event.target.tagName === "IMG") {
            const value = parseInt(event.target.getAttribute("data-value"));

            // Mettre à jour la note sélectionnée
            selectedRating = value;

            // Afficher la note sélectionnée
            selectedRatingDisplay.textContent = `Vous avez choisi : ${selectedRating} étoile(s)`;

            // Remplir les étoiles jusqu'à la note sélectionnée
            fillStars(selectedRating);

            // Afficher le bouton Soumettre
            submitRatingButton.classList.remove("hidden");
        }
    });

    // Remplir les étoiles jusqu'à une certaine valeur
    function fillStars(value) {
        const stars = starsContainer.querySelectorAll("img");
        stars.forEach((star, index) => {
            star.classList.toggle("selected", index < value); // Ajoute ou retire la classe "selected"
        });
    }

    // Soumettre la note sélectionnée
    submitRatingButton.addEventListener("click", () => {
        alert(`Note enregistrée : ${selectedRating}`);
        evaluationPopup.classList.add("hidden"); // Fermer le popup
        submitRatingButton.classList.add("hidden"); // Cacher le bouton de soumission
        selectedRatingDisplay.textContent = ""; // Réinitialiser le texte
        selectedRating = 0; // Réinitialiser la note
        fillStars(0); // Vider les étoiles
    });
});