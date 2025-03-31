// Gestion des cœurs interactifs
document.querySelectorAll('.wishlist-heart').forEach(heart => {
    heart.addEventListener('click', () => {
        const offerId = heart.getAttribute('data-offer-id'); // Récupère l'ID de l'offre

        if (heart.classList.contains('active')) {
            // Si le cœur est actif, le rendre inactif
            heart.src = '/assets/images/CoeurVide.png'; // Remplace par le cœur vide
            heart.classList.remove('active');
            console.log(`Offre ${offerId} retirée de la wishlist.`);
            alert(`Offre ${offerId} retirée de la wishlist.`);
        } else {
            // Si le cœur est inactif, le rendre actif
            heart.src = '/assets/images/CoeurRemplis.png'; // Remplace par le cœur rempli
            heart.classList.add('active');
            console.log(`Offre ${offerId} ajoutée à la wishlist.`);
            alert(`Offre ${offerId} ajoutée à la wishlist.`);
        }

    });
});