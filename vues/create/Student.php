<?php
require_once('../../src/Controllers/Login.php');
require_once('../../src/Controllers/CheckAuth.php');
require_once('../../src/Controllers/User.php'); // Assurez-vous que ce fichier existe pour gérer les entreprises; 
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Internity - Créer un compte</title>
    <meta charset="UTF-8">
    <meta name="description" content="Créer un compte chez Internity">
    <meta name="author" content="Internity">
    <link rel="stylesheet" href="/assets/css/manage/user.css">
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
            <h1>Ajouter un étudiant</h1>
            <form action="../../src/Controllers/User.php" method="POST" class="create-account-form">
                <!-- Pilote assigné -->
                <div class="form-group">
                    <label for="pilote">Pilote :</label>

                    <?php if ($role === 'pilote'): ?>
                        <input type="text" id="pilote_display" name="pilote_display"
                            value="<?= htmlspecialchars($name . ' ' . $surname) ?>" readonly
                            class="bg-gray-100 text-gray-700 cursor-not-allowed">
                        <input type="hidden" name="pilote_id" value="<?= $user_id ?>">
                    <?php elseif ($role === 'admin'): ?>
                        <select id="pilote_id" name="pilote_id" required>
                            <option value="">-- Sélectionner un pilote --</option>
                            <?php foreach ($pilotes as $pilote): ?>
                                <option value="<?= $pilote['user_id'] ?>">
                                    <?= htmlspecialchars($pilote['user_name'] . ' ' . $pilote['user_surname']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>

                <!-- Nom de l'utilisateur -->
                <div class="form-group">
                    <label for="user_surname">Nom :</label>
                    <input type="text" id="user_surname" name="user_surname" placeholder="Ex: Dupont" maxlength="50"
                        required onblur="this.value = this.value.toUpperCase();">
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

                <!-- Bouton de soumission -->
                <div class="form-group">
                    <button type="submit" class="submit-button">Créer le compte</button>
                </div>
            </form>
        </div>
    </main>

    <script src="/assets/js/manage/user.js" defer></script>

</body>

<footer style="position: fixed;">
    <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
    <p>© 2025 - Internity</p>
</footer>

</html>