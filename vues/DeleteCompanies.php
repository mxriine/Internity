<?php
require_once('../src/Controllers/Login.php');
require_once('../src/Controllers/CheckAuth.php');
require_once('../src/Controllers/Offer.php'); 

require_once('Navbar.php'); 

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Internity - Supprimer une entreprise</title>
    <meta charset="UTF-8">
    <meta name="description" content="Supprimer une entreprise chez Internity">
    <meta name="author" content="Internity">
    <link rel="stylesheet" href="/assets/css/createCompanies.css"> <!-- C'est le même fichier CSS que pour le formulaire de création d'entreprise -->
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>

<body>

    <main>
        <!-- Conteneur pour afficher les informations de l'entreprise -->
        <div class="create-company-container">
            <h1>Supprimer cette entreprise</h1>
            
            <!-- Informations de l'entreprise en lecture seule -->
            <div class="form-group">
                <label>Nom de l'entreprise :</label>
                <p>entreprise</p>
            </div>

            <div class="form-group">
                <label>Description de l'entreprise :</label>
                <p>description</p>
            </div>

            <div class="form-group">
                <label>Secteur d'activité :</label>
                <p>secteur</p>
            </div>

            <div class="form-group">
                <label>Email de l'entreprise :</label>
                <p>mail</p>
            </div>

            <div class="form-group">
                <label>Téléphone de l'entreprise :</label>
                <p>tel</p>
            </div>

            <div class="form-group">
                <label>Adresse :</label>
                <p>@</p>
            </div>

            <!-- Formulaire de suppression -->
            <form action="#" method="POST" class="delete-company-form">
                <input type="hidden" name="company_id" value="<?php echo $companyData['id']; ?>">
                <div class="form-group">
                    <button type="submit" class="submit-button">Supprimer cette entreprise</button>
                </div>
            </form>
        </div>

        <!-- Modal de confirmation -->
        <div id="confirmationModal" class="modal">
            <div class="modal-content">
                <h2>Êtes-vous sûr ?</h2>
                <p>Vous êtes sur le point de supprimer cette entreprise. Cette action est irréversible.</p>
                <div class="modal-buttons">
                    <button id="confirmDelete" class="btn btn-danger">Supprimer</button>
                    <button id="cancelDelete" class="btn btn-secondary">Annuler</button>
                </div>
            </div>
        </div>
        
    </main>

    <script src="/assets/js/createCompanies.js" defer></script> <!-- C'est le même fichier JS que pour le formulaire de création d'entreprise -->

</body>

<footer>
    <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
    <p>© 2025 - Internity</p>
</footer>

</html>