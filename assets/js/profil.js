// MON COMPTE

// Sélectionner tous les éléments du menu et les contenus des onglets
const menuItems = document.querySelectorAll('.menu-item');
const tabContents = document.querySelectorAll('.tab-content');

// Ajouter un écouteur d'événements à chaque élément du menu
menuItems.forEach(item => {
    item.addEventListener('click', () => {
        // Supprimer la classe 'active' de tous les éléments du menu et des contenus
        menuItems.forEach(i => i.classList.remove('active'));
        tabContents.forEach(content => content.classList.remove('active'));

        // Ajouter la classe 'active' à l'élément cliqué et au contenu correspondant
        item.classList.add('active');
        const tabId = item.getAttribute('data-tab');
        document.getElementById(tabId).classList.add('active');
    });
});

// Gestion des fichiers téléchargés
document.getElementById('cvUpload').addEventListener('change', function (event) {
    const fileName = event.target.files[0] ? event.target.files[0].name : 'Aucun fichier sélectionné';
    document.getElementById('cvFileName').textContent = fileName;
});

document.getElementById('coverLetterUpload').addEventListener('change', function (event) {
    const fileName = event.target.files[0] ? event.target.files[0].name : 'Aucun fichier sélectionné';
    document.getElementById('coverLetterFileName').textContent = fileName;
});

// Sauvegarde des documents
document.getElementById('saveDocuments').addEventListener('click', function () {
    alert('Documents enregistrés avec succès !'); // Remplacez par une logique backend si nécessaire
});

// WHISHLIST

document.addEventListener('DOMContentLoaded', () => {
    const menuItems = document.querySelectorAll('.menu-item');
    const tabContents = document.querySelectorAll('.tab-content');

    // Gestion des onglets
    menuItems.forEach(item => {
        item.addEventListener('click', (event) => {
            event.preventDefault();
            const tab = item.getAttribute('data-tab');
            if (tab) {
                window.history.pushState({}, '', `Profil.php?page=${tab}`);
                updateTabContent(tab);
            }
        });
    });

    function updateTabContent(tab) {
        menuItems.forEach(i => i.classList.remove('active'));
        tabContents.forEach(content => content.classList.remove('active'));

        const selectedItem = document.querySelector(`.menu-item[data-tab="${tab}"]`);
        if (selectedItem) selectedItem.classList.add('active');

        const selectedContent = document.getElementById(tab);
        if (selectedContent) selectedContent.classList.add('active');
    }

    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('page') || 'mon-compte';
    updateTabContent(activeTab);

    // Gestion suppression AJAX d'une offre
    const removeButtons = document.querySelectorAll('.remove-offer');
    removeButtons.forEach(button => {
        button.addEventListener('click', async (event) => {
            event.preventDefault();

            const offerId = button.getAttribute('data-offer-id');
            const offerElement = button.closest('.wishlist-item'); // <- attention, bien ".wishlist-item"
            if (!offerId || !offerElement) {
                showNotification('Erreur : offre introuvable.', 'error');
                return;
            }

            try {
                const response = await fetch('/src/Controllers/Wishlist.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ offer_id: offerId }) // ← envoyer offer_id dans le corps JSON
                });

                if (response.ok) {
                    offerElement.style.transition = 'opacity 0.4s ease';
                    offerElement.style.opacity = '0';
                    setTimeout(() => offerElement.remove(), 400);

                    updateWishlistCounter(-1);
                    showNotification('Offre supprimée avec succès.', 'success');
                } else {
                    const errorData = await response.json();
                    showNotification(`Erreur : ${errorData.message}`, 'error');
                }
            } catch (err) {
                console.error(err);
                showNotification('Erreur réseau. Veuillez réessayer.', 'error');
            }
        });
    });

    // Fonction pour afficher une notification temporaire
    function showNotification(message, type = 'success') {
        const notif = document.createElement('div');
        notif.textContent = message;
        notif.className = `notification ${type}`;
        document.body.appendChild(notif);
        setTimeout(() => notif.remove(), 3000);
    }

    // Fonction pour mettre à jour un compteur s’il existe
    function updateWishlistCounter(delta) {
        const counter = document.querySelector('#wishlist-counter');
        if (counter) {
            const newValue = Math.max(0, parseInt(counter.textContent) + delta);
            counter.textContent = newValue;
        }
    }
});

// SECURITY

// Gestion des formulaires de modification
document.addEventListener('DOMContentLoaded', () => {
    const editEmailButton = document.getElementById('editEmailButton');
    const editPasswordButton = document.getElementById('editPasswordButton');
    const cancelEmailEditButton = document.getElementById('cancelEmailEditButton');
    const cancelPasswordEditButton = document.getElementById('cancelPasswordEditButton');
    const editEmailForm = document.getElementById('editEmailForm');
    const editPasswordForm = document.getElementById('editPasswordForm');
    const emailForm = document.getElementById('emailForm');
    const passwordForm = document.getElementById('passwordForm');
    const currentEmail = document.getElementById('currentEmail');
    const currentPassword = document.getElementById('currentPassword');

    // Afficher le formulaire de modification d'email
    editEmailButton.addEventListener('click', () => {
        editEmailForm.style.display = 'block';
        editPasswordForm.style.display = 'none'; // Masquer l'autre formulaire si ouvert
    });

    // Masquer le formulaire de modification d'email
    cancelEmailEditButton.addEventListener('click', () => {
        editEmailForm.style.display = 'none';
        emailForm.reset(); // Réinitialiser les champs
    });

    // Soumission du formulaire d'email
    emailForm.addEventListener('submit', async (event) => {
        event.preventDefault(); // Empêcher le rechargement de la page

        const newEmail = document.getElementById('newEmail').value;

        if (newEmail) {
            // Simuler une requête backend avec fetch (remplacez l'URL par votre endpoint réel)
            try {
                const response = await fetch('/update-email', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email: newEmail }),
                });

                if (response.ok) {
                    const data = await response.json();
                    currentEmail.textContent = data.email; // Mettre à jour l'email affiché
                    editEmailForm.style.display = 'none'; // Masquer le formulaire
                    emailForm.reset();
                    alert('Votre email a été mis à jour avec succès.');
                } else {
                    alert('Erreur lors de la mise à jour de l\'email.');
                }
            } catch (error) {
                console.error('Erreur réseau :', error);
                alert('Une erreur est survenue. Veuillez réessayer.');
            }
        } else {
            alert('Veuillez entrer un nouvel email.');
        }
    });

    // Afficher le formulaire de modification de mot de passe
    editPasswordButton.addEventListener('click', () => {
        editPasswordForm.style.display = 'block';
        editEmailForm.style.display = 'none'; // Masquer l'autre formulaire si ouvert
    });

    // Masquer le formulaire de modification de mot de passe
    cancelPasswordEditButton.addEventListener('click', () => {
        editPasswordForm.style.display = 'none';
        passwordForm.reset(); // Réinitialiser les champs
    });

    // Soumission du formulaire de mot de passe
    passwordForm.addEventListener('submit', async (event) => {
        event.preventDefault(); // Empêcher le rechargement de la page

        const newPassword = document.getElementById('newPassword').value;

        if (newPassword) {
            // Simuler une requête backend avec fetch (remplacez l'URL par votre endpoint réel)
            try {
                const response = await fetch('/update-password', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ password: newPassword }),
                });

                if (response.ok) {
                    currentPassword.textContent = '●'.repeat(newPassword.length); // Masquer le mot de passe
                    editPasswordForm.style.display = 'none'; // Masquer le formulaire
                    passwordForm.reset();
                    alert('Votre mot de passe a été mis à jour avec succès.');
                } else {
                    alert('Erreur lors de la mise à jour du mot de passe.');
                }
            } catch (error) {
                console.error('Erreur réseau :', error);
                alert('Une erreur est survenue. Veuillez réessayer.');
            }
        } else {
            alert('Veuillez entrer un nouveau mot de passe.');
        }
    });

    // Gestion de l'affichage/masquage du mot de passe grâce à l'œil
    const togglePassword = document.getElementById('togglePassword');
    let isPasswordVisible = false;

    togglePassword.addEventListener('click', () => {
        if (isPasswordVisible) {
            // Masquer le mot de passe
            currentPassword.textContent = '●'.repeat(currentPassword.textContent.length);
            isPasswordVisible = false;
        } else {
            // Afficher le mot de passe
            currentPassword.textContent = 'votreMotDePasse'; // Remplacez par la valeur réelle du mot de passe
            isPasswordVisible = true;
        }
    });
});