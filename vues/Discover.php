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
    <?php include 'include/Navbar.php'; ?>

    <main>
    
        <?php include 'include/Filter.php'; ?>

        <!-- Section des offres -->
        <div class="header-section">
            <h1>Pour vous</h1>

            <!-- Barre d'étiquettes -->
            <div class="tags">
            <?php 
            $categories = ['Stage', 'Marketing', 'JavaScript', 'Électronique', 'Cybersécurité'];
            foreach ($categories as $index => $category): 
            ?>
                <input type="checkbox" id="category<?= $index + 1 ?>" class="hidden-checkbox" data-skill="<?= $category ?>">
                <label class="tag" for="category<?= $index + 1 ?>"><?= $category ?></label>
            <?php endforeach; ?>
            </div>
        </div>

        

        <!-- Section des cartes de stages -->
        <section class="cards">
            <?php foreach ($offers as $offer): ?>
                <?php
                // Récupérer les skills de l'offre
                $skills = $offerModel->getOfferSkills($offer['offer_id']);
                $limitedSkills = array_slice($skills, 0, 3);

                // Générer les labels pour chaque skill
                $skillsLabels = '';
                foreach ($limitedSkills as $skill) {
                    $skillsLabels .= '<span class="skills">' . htmlspecialchars($skill['skills_name']) . '</span> ';
                }

                // Créer un slug pour le titre de l'offre
                $offerSlug = createSlug($offer['offer_title']);
                $offerLink = "/vues/Offer.php?offer_id=" . urlencode($offer['offer_id']) . "&title=" . urlencode($offerSlug);
                ?>
                <div class="card">
                    <div class="card-header">
                        <div class="card-img">
                            <img src="/assets/icons/star-circle.svg" alt="Favori">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="skills-container">
                            <?= $skillsLabels ?>
                        </div>
                        <h2><?= htmlspecialchars($offer['offer_title']) ?></h2>
                        <p><?= htmlspecialchars($offer['offer_desc']) ?></p>
                        <a href="<?= $offerLink ?>" class="btn">Voir l'offre</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page_actuelle > 1): ?>
            <a href="?page=<?= $page_actuelle - 1 ?>&search=<?= urlencode($search) ?>&location=<?= urlencode($location) ?>">Précédent</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&location=<?= urlencode($location) ?>" class="<?= $i === $page_actuelle ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page_actuelle < $total_pages): ?>
            <a href="?page=<?= $page_actuelle + 1 ?>&search=<?= urlencode($search) ?>&location=<?= urlencode($location) ?>">Suivant</a>
            <?php endif; ?>
        </div>
    </main>


    <!-- Bandeau de consentement aux cookies -->
    <div id="cookie-consent-banner" class="cookie-consent-banner hidden">
        <div class="cookie-container">
            <!-- Bulle de dialogue -->
            <div class="speech-bubble">
                <p>Je mange tes cookies ! 🍪</p>
                <p>Nous utilisons des cookies pour améliorer ton expérience.</p>
                <a href="/politique-de-cookies" target="_blank" class="learn-more">En savoir plus</a>
            </div>
            <!-- Image du cookie -->
            <img src="/assets/images/COOKIE.png" alt="Cookie" class="cookie-image">
        </div>
        <div class="cookie-consent-actions">
            <button id="accept-cookies">Accepter</button>
            <button id="reject-cookies">Refuser</button>
        </div>
    </div>

    <footer>
        <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
        <p>© 2025 - Internity</p>
    </footer>

    <script src="/assets/js/discover.js"></script>

</body>

</html>