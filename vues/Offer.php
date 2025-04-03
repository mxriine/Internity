<!-- FORMULAIRE EN PHP -->
<?php
require_once('../src/Controllers/Login.php');
require_once('../src/Controllers/CheckAuth.php');
require_once('../src/Controllers/Offer.php');
require_once('../src/Controllers/Wishlist.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Internity - <?= htmlspecialchars($offerDetails['offer_title'] ?? 'Offre inconnue') ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="Internity - Le meilleur de l'Internet">
    <meta name="author" content="Internity">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/offer.css">
    <link rel="stylesheet" href="/assets/css/popup.css">
</head>

<body>
    <!-- Barre de navigation -->
    <?php include 'include/Navbar.php'; ?>

    <main>
        <!-- Hero Image Section -->
        <div class="hero-section">
            <div class="hero-header">
                <div class="hero-img">
                    <img src="/assets/icons/star-circle.svg" alt="Favori">
                </div>
            </div>
            <div class="hero-title"><?= htmlspecialchars($offerDetails['offer_title'] ?? 'Offre inconnue') ?></div>
        </div>

        <!-- Main Content Section -->
        <div class="content-container">
            <!-- Text Section -->
            <div class="text-section">
                <p>
                    <?= nl2br(htmlspecialchars($offerDetails['offer_desc'] ?? 'Description non disponible')) ?>
                </p>
            </div>

            <!-- Card Section -->
            <div class="card-section">
                <div class="card">
                    <div class="card-header">
                        <div class="card-img">
                            <img src="/assets/icons/star-circle.svg" alt="Favori">
                        </div>
                    </div>

                    <!-- Nouveau conteneur pour le titre et le cœur -->
                    <div class="title-container">
                        <div class="card-title">
                            <?= htmlspecialchars($companiesDetails['company_name'] ?? 'Entreprise inconnue') ?>
                        </div>
                        <button class="wishlist-toggle" id="addToWishlistButton"
                            data-offer-id="<?= htmlspecialchars($offerDetails['offer_id'] ?? '') ?>"
                            data-in-wishlist="<?= in_array($offerDetails['offer_id'], array_column($wishlist, 'offer_id')) ? '1' : '0' ?>"
                            title="<?= in_array($offerDetails['offer_id'], array_column($wishlist, 'offer_id')) ? 'Retirer de la wishlist' : 'Ajouter à la wishlist' ?>">
                            <img src="<?= in_array($offerDetails['offer_id'], array_column($wishlist, 'offer_id')) ? '/assets/images/CoeurRemplis.png' : '/assets/images/CoeurVide.png' ?>"
                                alt="Icône wishlist" class="wishlist-heart">
                        </button>
                    </div>

                    <div class="card-body">
                        <p><strong>Ville :</strong>
                            <?= htmlspecialchars($companiesDetails['city'] ?? 'Non spécifiée') ?></p>
                        <p><strong>Téléphone :</strong>
                            <?= htmlspecialchars($companiesDetails['phone'] ?? 'Non spécifié') ?></p>
                        <p><strong>Email :</strong>
                            <?= htmlspecialchars($companiesDetails['email'] ?? 'Non spécifié') ?></p>
                        <p><strong>Compétences requises :</strong></p>
                        <ul>
                            <?php foreach ($skills as $skill): ?>
                                <li><?= htmlspecialchars($skill['skills_name'] ?? 'Compétence inconnue') ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="card-footer">
                        <?php
                        $offerId = $_GET['id'] ?? null;
                        $offerSlug = createSlug($offerDetails['offer_title'] ?? 'Offre inconnue');
                        $offerLink = "/vues/Apply.php?offer_id=" . urlencode($offerDetails['offer_id']) . "&title=" . urlencode($offerSlug);
                        ?>

                        <!-- Bouton POSTULER -->
                        <a href="<?= $offerLink ?>" class="btn">
                            <button class="card-button">POSTULER</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Footer -->
    <footer style="position: fixed;">
        <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
        <p>© 2025 - Internity</p>
    </footer>

    <!-- Popup pour ajouter à la wishlist -->
    <div id="addToWishlistPopup" class="popup hidden">
        <div class="popup-content">
            <h2 id="popupMessage">Vous avez ajouté une offre à votre wishlist !</h2>
            <button id="closeAddToWishlistPopup" class="submit-rating">OK</button>
        </div>
    </div>

    <div id="removeFromWishlistPopup" class="popup hidden">
            <div class="popup-content">
                <h2>Vous avez supprimé l'offre X de votre wishlist !</h2>
                <button id="closeRemoveFromWishlistPopup" class="submit-rating">OK</button>
            </div>
        </div>

    <!-- Inclusion du fichier JavaScript -->
    <script src="/assets/js/offer.js"></script>
    <script src="/assets/js/popup.js"></script>
</body>

</html>