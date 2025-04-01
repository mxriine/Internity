//MON COMPTE

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


//WHISHLIST

// Gestion de l'argument "page"
document.addEventListener('DOMContentLoaded', () => {
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        item.addEventListener('click', () => {
            const tab = item.getAttribute('data-tab');
            if (tab) {
                window.location.href = `Profil.php?page=${tab}`;
            }
        });
    });
});

//SECURITY


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
    emailForm.addEventListener('submit', (event) => {
        event.preventDefault(); // Empêcher le rechargement de la page

        const newEmail = document.getElementById('newEmail').value;

        if (newEmail) {
            currentEmail.textContent = newEmail; // Mettre à jour l'email affiché
            editEmailForm.style.display = 'none'; // Masquer le formulaire
            emailForm.reset();
            alert('Votre email a été mis à jour avec succès.');
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
    passwordForm.addEventListener('submit', (event) => {
        event.preventDefault(); // Empêcher le rechargement de la page

        const newPassword = document.getElementById('newPassword').value;

        if (newPassword) {
            currentPassword.textContent = '●'.repeat(newPassword.length); // Masquer le mot de passe
            editPasswordForm.style.display = 'none'; // Masquer le formulaire
            passwordForm.reset();
            alert('Votre mot de passe a été mis à jour avec succès.');
        } else {
            alert('Veuillez entrer un nouveau mot de passe.');
        }
    });
});

// Gestion de l'affichage/masquage du mot de passe grace à l'oeil
document.addEventListener('DOMContentLoaded', () => {
    const togglePassword = document.getElementById('togglePassword');
    const currentPassword = document.getElementById('currentPassword');

    // État initial : mot de passe masqué
    let isPasswordVisible = false;

    togglePassword.addEventListener('click', () => {
        if (isPasswordVisible) {
            // Masquer le mot de passe
            currentPassword.textContent = '●'.repeat(currentPassword.textContent.length);
            isPasswordVisible = false;
        } else {
            // Afficher le mot de passe
            currentPassword.textContent = 'votreMotDePasse'; // POUR MARINE
            isPasswordVisible = true;
        }
    });
});