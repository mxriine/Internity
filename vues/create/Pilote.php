<?php
require_once('../../src/Controllers/Login.php');
require_once('../../src/Controllers/CheckAuth.php');
require_once('../../src/Controllers/User.php');
require_once('../../src/Controllers/Promotion.php');
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
            <h1>Ajouter un pilote</h1>
            <form action="../../src/Controllers/User.php" method="POST" class="create-account-form">
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

                <!-- Promotion assigné -->
                <div class="form-group">
                    <label for="promotion">Promotion :</label>
                    <select id="promotion_id" name="promotion_id" required>
                        <option value="">-- Sélectionner une promotions --</option>
                        <?php foreach ($promotions as $promotion): ?>
                            <option value="<?= $promotion['user_id'] ?>">
                                <?= htmlspecialchars($promotion['promotion_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
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