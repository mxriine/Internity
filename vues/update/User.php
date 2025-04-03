<?php
require_once('../../src/Controllers/Login.php');
require_once('../../src/Controllers/CheckAuth.php');
require_once('../../src/Controllers/User.php'); // Assurez-vous que ce fichier existe pour gérer les entreprises
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
            <h1>Modifier ce compte</h1>
            <form action="/api/create-account" method="POST" class="create-account-form">
                <!-- Nom de l'utilisateur -->
                <div class="form-group">
                    <label for="user_surname">Nom :</label>
                    <input type="text" id="user_surname" name="user_surname" placeholder="Ex: Dupont" maxlength="50"
                        required>
                </div>

                <!-- Prénom de l'utilisateur -->
                <div class="form-group">
                    <label for="user_name">Prénom :</label>
                    <input type="text" id="user_name" name="user_name" placeholder="Ex: Jean" maxlength="50" required>
                </div>

                <!-- Email de l'utilisateur -->
                <div class="form-group">
                    <label for="user_email">Email :</label>
                    <input type="email" id="user_email" name="user_email" placeholder="Ex: jean.dupont@example.com"
                        maxlength="50" required>
                </div>

                <!-- Mot de passe de l'utilisateur -->
                <div class="form-group">
                    <label for="user_password">Mot de passe :</label>
                    <input type="password" id="user_password" name="user_password"
                        placeholder="Choisissez un mot de passe" required>
                </div>

                <!-- Rôle de l'utilisateur -->
                <div class="form-group">
                    <label for="user_role">Rôle :</label>
                    <select id="user_role" name="user_role" required>
                        <option value="" disabled selected>Choisissez un rôle</option>
                        <option value="Etudiant">Étudiant</option>
                        <option value="Pilote">Pilote</option>
                        <option value="Admin">Administrateur</option>
                    </select>
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

<footer>
    <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
    <p>© 2025 - Internity</p>
</footer>

</html>