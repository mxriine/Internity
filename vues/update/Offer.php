<?php
require_once('../../src/Controllers/Login.php');
require_once('../../src/Controllers/CheckAuth.php');
require_once('../../src/Controllers/Offer.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Internity - Modifier cette offre</title>
    <meta charset="UTF-8">
    <meta name="description" content="Gérez votre profil chez Internity">
    <meta name="author" content="Internity">
    <link rel="stylesheet" href="/assets/css/manage/offer.css">
    <!-- C'est le même fichier CSS que pour le formulaire de création d'offre -->
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>

<body>

    <!-- Navbar -->
    <?php require_once('../include/Navbar.php'); ?>

    <main>
        <!-- Formulaire pour modifier une offre -->
        <div class="create-offer-container">
            <a href="#" class="back" onclick="history.back(); return false;">
                <img src="/assets/icons/arrow.svg" alt="Retour">
                Retour
            </a>
            <h1>Modifier l'offre</h1>
            <h2><?= htmlspecialchars($offerDetails['offer_title']) ?></h2>
            <h3>Entreprise : <?= htmlspecialchars($companiesDetails['company_name']) ?></h3>

            <!-- Formulaire unique pour la modification -->
            <form action="../../src/Controllers/Offer.php?edit=1" method="POST" class="create-offer-form">
                <input type="hidden" name="offer_id" value="<?= htmlspecialchars($offerDetails['offer_id']) ?>">

                <!-- Titre de l'offre -->
                <div class="form-group">
                    <label for="offer_title">Titre de l'offre :</label>
                    <input type="text" id="offer_title" name="offer_title"
                        placeholder="<?= htmlspecialchars($offerDetails['offer_title']) ?>">
                </div>

                <!-- Description de l'offre -->
                <div class="form-group">
                    <label for="offer_desc">Description de l'offre :</label>
                    <textarea name="offer_desc" rows="5" placeholder="<?= htmlspecialchars($offerDetails['offer_desc']) ?>"></textarea>
                </div>

                <!-- Salaire proposé -->
                <div class="form-group">
                    <label for="offer_salary">Salaire proposé (€) :</label>
                    <input type="number" id="offer_salary" name="offer_salary" step="0.01"
                        placeholder="<?= htmlspecialchars($offerDetails['offer_salary']) ?>">
                </div>

                <!-- Date de début -->
                <div class="form-group">
                    <label for="offer_start">Date de début :</label>
                    <input type="date" id="offer_start" name="offer_start"
                        value="<?= htmlspecialchars(date('Y-m-d', strtotime($offerDetails['offer_start']))) ?>">
                </div>

                <!-- Date de fin -->
                <div class="form-group">
                    <label for="offer_end">Date de fin :</label>
                    <input type="date" id="offer_end" name="offer_end"
                        value="<?= htmlspecialchars(date('Y-m-d', strtotime($offerDetails['offer_end']))) ?>">
                </div>

                <!-- Bouton de soumission -->
                <div class="form-group">
                    <button type="submit" class="submit-button">Modifier cette offre</button>
                </div>
            </form>
        </div>
    </main>

    <script src="/assets/js/manage/offer.js" defer></script>
    <!-- C'est le même fichier JS que pour le formulaire de création d'offre -->

</body>

<footer>
    <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
    <p>© 2025 - Internity</p>
</footer>

</html>