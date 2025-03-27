<!-- FORMULAIRE DE CONNEXION (EN PHP) -->
<?php
require_once('../src/Controllers/LoginController.php');
require_once('Navbar.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Internity - Home</title>
    <meta charset="UTF-8">
    <meta name="description" content="Internity - Le meilleur de l'Internet">
    <meta name="author" content="Internity">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/discover.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <!-- Barre de navigation -->

    <main>
        <!-- Section principale avec image et formulaire -->
        <section class="hero-section">
            <div class="background-image">
                <img src="/assets/images/discover.jpg" alt="Image de fond">
            </div>
            <div class="search-form">
                <form action="#" method="get">
                    <div class="form-group">
                        <label for="what">QUOI ?</label>
                        <input type="text" id="what" placeholder="Métier, entreprise, compétence...">
                    </div>
                    <div class="form-group">
                        <label for="where">OÙ ?</label>
                        <input type="text" id="where" placeholder="Ville, département, code postal...">
                    </div>
                    <button type="submit" class="btn btn-search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
        </section>

        <!-- Section des offres -->
        <div class="header-section">
            <h1>Pour vous</h1>

            <!-- Barre d'étiquettes -->
            <div class="tags">
                <input type="checkbox" id="category1" class="hidden-checkbox">
                <label class="tag" for="category1">Stage</label>

                <input type="checkbox" id="category2" class="hidden-checkbox">
                <label class="tag" for="category2">Marketing</label>

                <input type="checkbox" id="category3" class="hidden-checkbox">
                <label class="tag" for="category3">Data</label>

                <input type="checkbox" id="category4" class="hidden-checkbox">
                <label class="tag" for="category4">Informatique</label>

                <input type="checkbox" id="category5" class="hidden-checkbox">
                <label class="tag" for="category5">Cyber-sécurité</label>
            </div>
        </div>

        <!-- Section des cartes de stages -->
        <?php require_once('../src/Controllers/OfferController.php'); ?>
    </main>

    <footer>
        <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
        <p>© 2025 - Internity</p>
    </footer>

</body>

</html>