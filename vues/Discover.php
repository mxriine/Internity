<!-- FORMULAIRE DE SESSION (EN PHP) -->
<?php
require_once('Navbar.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover Page</title>
    <link rel="stylesheet" href="/assets/discover.css">
    <link rel="stylesheet" href="/assets/styles.css">
</head>

<body>
    <main>
        <!-- Conteneur principal pour le titre et les étiquettes -->
        <div class="header-section">
            <h1>Pour vous</h1>

            <!-- Barre d'étiquettes -->
            <div class="tags">
                <input type="checkbox" id="category1" class="hidden-checkbox">
                <label class="tag" for="category1">Label 1</label>

                <input type="checkbox" id="category2" class="hidden-checkbox">
                <label class="tag" for="category2">Label 2</label>

                <input type="checkbox" id="category3" class="hidden-checkbox">
                <label class="tag" for="category3">Label 3</label>

                <input type="checkbox" id="category4" class="hidden-checkbox">
                <label class="tag" for="category4">Label 4</label>

                <input type="checkbox" id="category5" class="hidden-checkbox">
                <label class="tag" for="category5">Label 5</label>
            </div>

            <!-- Bouton "Entreprises" -->
            <div class="enterprise-btn-container">
                <button class="btn-enterprise">Entreprises</button>
            </div>
        </div>

        <hr>

        <?php require_once(__DIR__ . '/../src/Controllers/OfferController.php'); ?>

    </main>

    <footer>
        <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
        <p>© 2025 - Internity</p>
    </footer>
</body>

</html>