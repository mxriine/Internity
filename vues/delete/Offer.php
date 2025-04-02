<?php
require_once('../src/Controllers/Login.php');
require_once('../src/Controllers/CheckAuth.php');
require_once('../src/Controllers/Offer.php');

require_once('Navbar.php'); 
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Internity - Supprimer une offre</title>
    <meta charset="UTF-8">
    <meta name="description" content="Supprimer une offre chez Internity">
    <meta name="author" content="Internity">
    <link rel="stylesheet" href="/assets/css/manage/offer.css"> <!-- C'est le même fichier CSS que pour le formulaire de création d'offre -->
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>

<body>

    <main>
        <!-- Conteneur pour afficher les informations de l'offre -->
        <div class="create-offer-container">
            <h1>Supprimer cette Offre</h1>

            <!-- Informations de l'offre en lecture seule -->
            <div class="form-group">
                <label>Titre de l'offre :</label>
                <p>titre</p>
            </div>

            <div class="form-group">
                <label>Description de l'offre :</label>
                <p>description</p>
            </div>

            <div class="form-group">
                <label>Salaire proposé :</label>
                <p>€</p>
            </div>

            <div class="form-group">
                <label>Date de début :</label>
                <p>date</p>
            </div>

            <div class="form-group">
                <label>Date de fin :</label>
                <p>date</p>
            </div>

            <!-- Formulaire de suppression -->
            <form action="../../src/Controllers/Offer.php?delete=1" method="POST" class="delete-offer-form">
            <input type="hidden" name="offer_id" value="<?php echo $offerDetails['offer_id']; ?>">
                <div class="form-group">
                    <button type="submit" class="submit-button">Supprimer cette offre</button>
                </div>
            </form>
        </div>

        <!-- Modal de confirmation -->
        <div id="confirmationModal" class="modal">
            <div class="modal-content">
                <h2>Êtes-vous sûr ?</h2>
                <p>Vous êtes sur le point de supprimer cette offre. Cette action est irréversible.</p>
                <div class="modal-buttons">
                    <button id="confirmDelete" class="btn btn-danger">Supprimer</button>
                    <button id="cancelDelete" class="btn btn-secondary">Annuler</button>
                </div>
            </div>
        </div>
    </main>

    <script src="/assets/js/manage/offer.js" defer></script> <!-- Fichier JS personnalisé pour ce formulaire -->

</body>

<footer>
    <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
    <p>© 2025 - Internity</p>
</footer>

</html>