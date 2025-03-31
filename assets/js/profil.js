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