document.addEventListener('DOMContentLoaded', function () {
    const avatarIcon = document.querySelector('.avatar-icon');
    const userMenu = document.getElementById('userMenu');

    // Afficher/masquer le menu lors du clic sur l'icône d'avatar
    avatarIcon.addEventListener('click', function () {
        if (userMenu.style.display === 'block') {
            userMenu.style.display = 'none';
        } else {
            userMenu.style.display = 'block';
        }
    });

    // Masquer le menu si l'utilisateur clique en dehors de l'icône ou du menu
    window.addEventListener('click', function (event) {
        if (!avatarIcon.contains(event.target) && !userMenu.contains(event.target)) {
            userMenu.style.display = 'none';
        }
    });
});

function toggleMenu() {
    document.getElementById("navMenu").classList.toggle("show");
}