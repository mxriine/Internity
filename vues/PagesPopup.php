<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Internity - Postuler</title>
    <meta charset="UTF-8">
    <meta name="description" content="Postulez à une offre chez Internity">
    <meta name="author" content="Internity">
    <link rel="stylesheet" href="/assets/css/pagesPopup.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>

<body class="page-apply">

    <!-- Navbar -->
    <?php require_once('include/Navbar.php'); ?>

    <main>


        <!-- BOUTON AJOUTER À LA WISHLIST -->
        <div class="ButtonEvaluate">
            <button class="button-evaluate" id="addToWishlistButton">Ajouter à la wishlist</button>
        </div>

        <!-- Popup pour ajouter à la wishlist -->
        <div id="addToWishlistPopup" class="popup hidden">
            <div class="popup-content">
                <h2>Vous avez ajouté l'offre X à votre wishlist !</h2>
                <button id="closeAddToWishlistPopup" class="submit-rating">OK</button>
            </div>
        </div>

        <!-- BOUTON SUPPRIMER DE LA WISHLIST -->
        <div class="ButtonEvaluate">
            <button class="button-evaluate" id="removeFromWishlistButton">Supprimer de la wishlist</button>
        </div>

        <!-- Popup pour supprimer de la wishlist -->
        <div id="removeFromWishlistPopup" class="popup hidden">
            <div class="popup-content">
                <h2>Vous avez supprimé l'offre X de votre wishlist !</h2>
                <button id="closeRemoveFromWishlistPopup" class="submit-rating">OK</button>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer>
        <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
        <p>© 2025 - Internity</p>
    </footer>

    <!-- Inclusion du fichier JavaScript -->
    <script src="/assets/js/pagesPopup.js"></script>

</body>

</html>