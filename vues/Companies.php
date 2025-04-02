<!-- FORMULAIRE EN PHP -->
<?php
require_once('../src/Controllers/Login.php');
require_once('../src/Controllers/CheckAuth.php');
require_once('../src/Controllers/Companies.php');

// Initialize pagination variables
$page_actuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$total_pages = isset($total_pages) ? $total_pages : 1; // Replace with actual logic to calculate total pages

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
    
        <?php include 'FilterDiscovery.php'; ?>

        <!-- Section des entreprises -->
        <div class="header-section">
            <h1>Entreprises pour vous</h1>

            <!-- Barre d'étiquettes -->
            <div class="tags">
                <input type="checkbox" id="sector1" class="hidden-checkbox">
                <label class="tag" for="sector1">Technologie</label>

                <input type="checkbox" id="sector2" class="hidden-checkbox">
                <label class="tag" for="sector2">Finance</label>

                <input type="checkbox" id="sector3" class="hidden-checkbox">
                <label class="tag" for="sector3">Santé</label>

                <input type="checkbox" id="sector4" class="hidden-checkbox">
                <label class="tag" for="sector4">Éducation</label>

                <input type="checkbox" id="sector5" class="hidden-checkbox">
                <label class="tag" for="sector5">Énergie</label>
            </div>
        </div>

        <!-- Section des cartes d'entreprises -->
        <section class="cards">
            <?php foreach ($companies as $company): ?>
                <?php
                // Récupérer les secteurs de l'entreprise
                $sector = is_array($company['company_business']) && isset($company['company_business'][0]) ? $company['company_business'][0] : null;

                // Générer le label pour le secteur
                $sectorsLabels = $sector && isset($sector['sector_name']) ? '<span class="skills">' . htmlspecialchars($sector['sector_name']) . '</span>' : '';

                // Créer un slug pour le nom de l'entreprise
                $companySlug = createSlug($company['company_name']);
                $companyLink = "/vues/Company.php?company_id=" . urlencode($company['company_id']) . "&name=" . urlencode($companySlug);
                ?>
                <div class="card">
                    <div class="card-header">
                        <div class="card-img">
                            <img src="/assets/icons/star-circle.svg" alt="Entreprise">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="skills-container">
                            <?= $sectorsLabels ?>
                        </div>
                        <h2><?= htmlspecialchars($company['company_name']) ?></h2>
                        <p><?= htmlspecialchars($company['company_desc']) ?></p>
                        <a href="<?= $companyLink ?>" class="btn">Voir l'entreprise</a>
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