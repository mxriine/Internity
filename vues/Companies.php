<!-- FORMULAIRE EN PHP -->
<?php
require_once('../src/Controllers/Login.php');
require_once('../src/Controllers/CheckAuth.php');
require_once('../src/Controllers/Offer.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Internity - Discover</title>
    <meta charset="UTF-8">
    <meta name="description" content="Internity - Le meilleur de l'Internet">
    <meta name="author" content="Internity">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/discover.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <!-- Barre de navigation -->
    <header class="header">
        <?php require_once('Navbar.php'); ?>
    </header> 

    <main>
    
    <?php include 'FilterDiscovery.php'; ?>

        <!-- Section des Entreprises -->
        <div class="header-section">
            <h1>Entreprises : </h1>

        <!-- Section des cartes des Entreprises -->
        <section class="cards">
            <?php foreach ($offers as $offer): ?>
                <?php

                // Créer un slug pour le titre de l'offre
                $offerSlug = createSlug($offer['offer_title']);
                $offerLink = "/vues/Offer.php?offer_id=" . urlencode($offer['offer_id']) . "&title=" . urlencode($offerSlug);
                ?>
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <h2><?= htmlspecialchars($offer['offer_title']) ?></h2>
                        <p><?= htmlspecialchars($offer['offer_desc']) ?></p>
                        <a href="<?= $offerLink ?>" class="btn">Voir l'entreprise</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page_actuelle > 1): ?>
                <a href="?page=<?= $page_actuelle - 1 ?>">Précédent</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= $i === $page_actuelle ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page_actuelle < $total_pages): ?>
                <a href="?page=<?= $page_actuelle + 1 ?>">Suivant</a>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
        <p>© 2025 - Internity</p>
    </footer>
</body>

</html>