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
    <?php require_once('Navbar.php'); ?>

    <main>

        <!-- BOUTON ÉVALUER -->
        <div class="ButtonEvaluate">
            <button class="button-evaluate" id="evaluateButton">Évaluer</button>
        </div>

        <!-- Popup pour l'évaluation -->
        <div id="evaluationPopup" class="popup hidden">
            <div class="popup-content">
                <h2>Évaluez cette entreprise</h2>
                <div class="stars-container" id="starsContainer">
                    <img src="/assets/images/EtoileVide.png" alt="Étoile 1" data-value="1">
                    <img src="/assets/images/EtoileVide.png" alt="Étoile 2" data-value="2">
                    <img src="/assets/images/EtoileVide.png" alt="Étoile 3" data-value="3">
                    <img src="/assets/images/EtoileVide.png" alt="Étoile 4" data-value="4">
                    <img src="/assets/images/EtoileVide.png" alt="Étoile 5" data-value="5">
                </div>
                <p id="selectedRating"></p>
                <button id="submitRating" class="submit-rating hidden">Soumettre</button>
            </div>
        </div>

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