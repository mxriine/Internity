document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.querySelector('.wishlist-toggle');
    if (!toggleButton) return;

    toggleButton.addEventListener('click', async (e) => {
        e.preventDefault();

        const offerId = toggleButton.getAttribute('data-offer-id');
        const isInWishlist = toggleButton.getAttribute('data-in-wishlist') === '1';
        const action = isInWishlist ? 'remove' : 'add';

        // Optimisme UX : changer l’image tout de suite pour meilleure réactivité
        const img = toggleButton.querySelector('.wishlist-heart');
        if (img) {
            img.src = isInWishlist
                ? '/assets/images/CoeurVide.png'
                : '/assets/images/CoeurRemplis.png';
            toggleButton.title = isInWishlist
                ? 'Ajouter à la wishlist'
                : 'Retirer de la wishlist';
        }
        toggleButton.setAttribute('data-in-wishlist', isInWishlist ? '0' : '1');

        try {
            const response = await fetch('/src/Controllers/Wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ offer_id: offerId, action: action })
            });

            if (!response.ok) {
                const errorData = await response.json();
                console.error('Erreur AJAX :', errorData.message);
                alert('Erreur lors de la mise à jour de la wishlist.');

                // Si erreur : revenir à l’état précédent (rollback visuel)
                if (img) {
                    img.src = isInWishlist
                        ? '/assets/images/CoeurRemplis.png'
                        : '/assets/images/CoeurVide.png';
                    toggleButton.title = isInWishlist
                        ? 'Retirer de la wishlist'
                        : 'Ajouter à la wishlist';
                }
                toggleButton.setAttribute('data-in-wishlist', isInWishlist ? '1' : '0');
            }
        } catch (error) {
            console.error('Erreur réseau :', error);
            alert('Erreur réseau. Veuillez réessayer.');

            // Rollback visuel en cas d’échec
            if (img) {
                img.src = isInWishlist
                    ? '/assets/images/CoeurRemplis.png'
                    : '/assets/images/CoeurVide.png';
                toggleButton.title = isInWishlist
                    ? 'Retirer de la wishlist'
                    : 'Ajouter à la wishlist';
            }
            toggleButton.setAttribute('data-in-wishlist', isInWishlist ? '1' : '0');
        }
    });
});


//POUR LE POPUP

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