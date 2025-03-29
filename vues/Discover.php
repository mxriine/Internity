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
    <?php include 'Navbar.php'; ?>

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
                $offerLink = "../../vues/Offer.php?offer_id=" . urlencode($offer['offer_id']) . "&title=" . urlencode($offerSlug);
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