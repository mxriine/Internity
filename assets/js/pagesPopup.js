//POUR LA WISHLIST



document.addEventListener("DOMContentLoaded", () => {
    // Récupération des éléments HTML
    const addToWishlistButton = document.getElementById("addToWishlistButton");
    const removeFromWishlistButton = document.getElementById("removeFromWishlistButton");
    const addToWishlistPopup = document.getElementById("addToWishlistPopup");
    const removeFromWishlistPopup = document.getElementById("removeFromWishlistPopup");
    const closeAddToWishlistPopup = document.getElementById("closeAddToWishlistPopup");
    const closeRemoveFromWishlistPopup = document.getElementById("closeRemoveFromWishlistPopup");

    // Ouvrir le popup "Ajouter à la wishlist"
    addToWishlistButton.addEventListener("click", () => {
        addToWishlistPopup.classList.remove("hidden");
    });

    // Fermer le popup "Ajouter à la wishlist"
    closeAddToWishlistPopup.addEventListener("click", () => {
        addToWishlistPopup.classList.add("hidden");
    });

    // Ouvrir le popup "Supprimer de la wishlist"
    removeFromWishlistButton.addEventListener("click", () => {
        removeFromWishlistPopup.classList.remove("hidden");
    });

    // Fermer le popup "Supprimer de la wishlist"
    closeRemoveFromWishlistPopup.addEventListener("click", () => {
        removeFromWishlistPopup.classList.add("hidden");
    });
});

