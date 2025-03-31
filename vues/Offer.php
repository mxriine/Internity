<!-- FORMULAIRE EN PHP -->
<?php
require_once('../src/Controllers/Login.php');
require_once('../src/Controllers/CheckAuth.php');
require_once('../src/Controllers/Offer.php');
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
</head>

<body>
    <!-- Barre de navigation -->
    <?php include 'Navbar.php'; ?>

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
                    <div class="card-title"><?= htmlspecialchars($companiesDetails['company_name'] ?? 'Entreprise inconnue') ?></div>
                    <div class="card-body">
                        <p><strong>Ville :</strong> <?= htmlspecialchars($companiesDetails['city'] ?? 'Non spécifiée') ?></p>
                        <p><strong>Téléphone :</strong> <?= htmlspecialchars($companiesDetails['phone'] ?? 'Non spécifié') ?></p>
                        <p><strong>Email :</strong> <?= htmlspecialchars($companiesDetails['email'] ?? 'Non spécifié') ?></p>
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
                        <a href="<?= $offerLink ?>" class="btn"><button class="card-button">POSTULER</button></a>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
        <p>© 2025 - Internity</p>
    </footer>
</body>

</html>