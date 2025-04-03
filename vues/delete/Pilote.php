<?php
require_once('../../src/Controllers/Login.php');
require_once('../../src/Controllers/CheckAuth.php');
require_once('../../src/Controllers/User.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Internity - Supprimer un compte</title>
    <meta charset="UTF-8">
    <meta name="description" content="Supprimer un compte chez Internity">
    <meta name="author" content="Internity">
    <link rel="stylesheet" href="/assets/css/manage/user.css">
    <!-- C'est le même fichier CSS que pour le formulaire de création d'offre -->
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>

<body>

    <main>
        <!-- Conteneur pour afficher les informations du compte -->
        <div class="create-account-container">
            <a href="#" class="back" onclick="history.back(); return false;">
                <img src="/assets/icons/arrow.svg" alt="Retour">
                Retour
            </a>
            <h1>Supprimer le pilote</h1>

            <!-- Informations du compte en lecture seule -->
            <div class="form-group">
                <label>Nom :</label>
                <p><?= htmlspecialchars($userDetails['user_surname']) ?></p>
            </div>

            <div class="form-group">
                <label>Prénom :</label>
                <p><?= htmlspecialchars($userDetails['user_name']) ?></p>
            </div>

            <div class="form-group">
                <label>Promotion :</label>
                <p><?= htmlspecialchars($userDetails['promotion_name'] ?? 'Aucune') ?></p>
            </div>

            <div class="form-group">
                <label>Email :</label>
                <p><?= htmlspecialchars($userDetails['user_email']) ?></p>
            </div>

            <!-- Formulaire de suppression -->
            <form action="../../src/Controllers/User.php?delete=1" method="POST" class="delete-account-form">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($userDetails['user_id']) ?>">
                <div class="form-group">
                    <button type="submit" class="submit-button">Supprimer ce compte</button>
                </div>
            </form>
        </div>

        <!-- Modal de confirmation -->
        <div id="confirmationModal" class="modal">
            <div class="modal-content">
                <h2>Êtes-vous sûr ?</h2>
                <p>Vous êtes sur le point de supprimer ce compte. Cette action est irréversible.</p>
                <div class="modal-buttons">
                    <button id="confirmDelete" class="btn btn-danger">Supprimer</button>
                    <button id="cancelDelete" class="btn btn-secondary">Annuler</button>
                </div>
            </div>
        </div>
    </main>

    <script src="/assets/js/manage/user.js" defer></script> <!-- Fichier JS personnalisé pour ce formulaire -->

</body>

<footer style="position: fixed;">
    <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
    <p>© 2025 - Internity</p>
</footer>

</html>