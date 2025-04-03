document.addEventListener("DOMContentLoaded", () => {
    // WISHLIST
    const wishlistButton = document.getElementById("wishlistButton");
    const wishlistPopup = document.getElementById("wishlistPopup");
    const wishlistPopupMessage = document.getElementById("wishlistPopupMessage");
    const closeWishlistPopup = document.getElementById("closeWishlistPopup");

    wishlistButton.addEventListener("click", () => {
        const inWishlist = wishlistButton.dataset.inWishlist === '1';
        const offerTitle = wishlistButton.dataset.offerTitle;
        const img = wishlistButton.querySelector("img");

        // Inverser l'état
        const newInWishlist = !inWishlist;
        wishlistButton.dataset.inWishlist = newInWishlist ? '1' : '0';

        // Modifier image et tooltip
        img.src = newInWishlist ? '/assets/images/CoeurRemplis.png' : '/assets/images/CoeurVide.png';
        wishlistButton.title = newInWishlist ? 'Retirer de la wishlist' : 'Ajouter à la wishlist';

        // Afficher popup avec message dynamique
        wishlistPopupMessage.textContent = newInWishlist
            ? `Vous avez ajouté l'offre "${offerTitle}" à votre wishlist !`
            : `Vous avez retiré l'offre "${offerTitle}" de votre wishlist.`;

        wishlistPopup.classList.remove("hidden");

        // TODO : appel AJAX ici si besoin
    });

    closeWishlistPopup.addEventListener("click", () => {
        wishlistPopup.classList.add("hidden");
    });
});
