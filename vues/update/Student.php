<?php
require_once('../../src/Controllers/Login.php');
require_once('../../src/Controllers/CheckAuth.php');
require_once('../../src/Controllers/User.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Internity - Modifier un compte</title>
    <meta charset="UTF-8">
    <meta name="description" content="Créer un compte chez Internity">
    <meta name="author" content="Internity">
    <link rel="stylesheet" href="/assets/css/manage/user.css">
    <!-- C'est le même fichier CSS que pour le formulaire de création d'offre -->
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>

<body>

    <!-- Navbar -->
    <?php require_once('../include/Navbar.php'); ?>

    <main>
        <!-- Formulaire pour créer un compte -->
        <div class="create-account-container">
            <a href="#" class="back" onclick="history.back(); return false;">
                <img src="/assets/icons/arrow.svg" alt="Retour">
                Retour
            </a>
            <h1>Modifier l'étudiant</h1>

            <form action="../../src/Controllers/User.php?edit=1" method="POST" class="create-account-form">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($userDetails['user_id']) ?>">
                <!-- Nom de l'utilisateur -->
                <div class="form-group">
                    <label for="user_surname">Nom :</label>
                    <input type="text" id="user_surname" name="user_surname" value="<?= htmlspecialchars($userDetails['user_surname']) ?>" maxlength="50"
                        required>
                </div>

                <!-- Prénom de l'utilisateur -->
                <div class="form-group">
                    <label for="user_name">Prénom :</label>
                    <input type="text" id="user_name" name="user_name" value="<?= htmlspecialchars($userDetails['user_name']) ?>" maxlength="50" required>
                </div>

                <!-- Email de l'utilisateur -->
                <div class="form-group">
                    <label for="user_email">Email :</label>
                    <input type="email" id="user_email" name="user_email" value="<?= htmlspecialchars($userDetails['user_email']) ?>"
                        maxlength="50" required>
                </div>

                <!-- Mot de passe de l'utilisateur -->
                <div class="form-group">
                    <label for="user_password">Mot de passe :</label>
                    <input type="password" id="user_password" name="user_password"
                        value="Choisissez un mot de passe" required>
                </div>

                <!-- Bouton de soumission -->
                <div class="form-group">
                    <button type="submit" class="submit-button">Modifier le compte</button>
                </div>
            </form>
        </div>
    </main>

    <script src="/assets/js/manage/user.js" defer></script>
    <!-- C'est le même fichier JS que pour le formulaire de création d'utilisateurs -->

</body>

<footer style="position: fixed;">
    <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
    <p>© 2025 - Internity</p>
</footer>

</html>