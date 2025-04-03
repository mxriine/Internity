<?php
require_once('../src/Controllers/Login.php');
require_once('../src/Controllers/CheckAuth.php');
require_once('../src/Controllers/Companies.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Internity - <?= htmlspecialchars($companyDetails['company_name'] ?? 'Entreprise inconnue') ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="Internity - Le meilleur de l'Internet">
    <meta name="author" content="Internity">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/company.css">
</head>

<body>
    <!-- Barre de navigation -->
    <?php include 'Navbar.php'; ?>

    <main>
        <!-- Hero Image Section -->
        <div class="hero-section">
            <div class="hero-header">
                <div class="hero-img">
                    <img src="/assets/icons/star-circle.svg" alt="Entreprise">
                </div>
            </div>
            <div class="hero-title"><?= htmlspecialchars($companyDetails['company_name'] ?? 'Entreprise inconnue') ?></div>
        </div>

        <!-- Main Content Section -->
        <div class="content-container">
            <!-- Text Section -->
            <div class="text-section">
                <p>
                    <?= nl2br(htmlspecialchars($companyDetails['company_desc'] ?? 'Description non disponible')) ?>
                </p>
            </div>

            <!-- Card Section -->
            <div class="card-section">
                <div class="card">
                    <div class="card-header">
                        <div class="card-img">
                            <img src="/assets/icons/star-circle.svg" alt="Entreprise">
                        </div>
                    </div>
            
                    <div class="card-body">
                        <p><strong>Ville :</strong> <?= htmlspecialchars($companyDetails['city_name'] ?? 'Non spécifiée') ?></p>
                        <p><strong>Téléphone :</strong> <?= htmlspecialchars($companyDetails['company_phone'] ?? 'Non spécifié') ?></p>
                        <p><strong>Email :</strong> <?= htmlspecialchars($companyDetails['company_email'] ?? 'Non spécifié') ?></p>
                        <p><strong>Domaine d'expertise :</strong> <?= htmlspecialchars($companyDetails['company_business'] ?? 'Non spécifié') ?></p>
                    </div>
            
                    <div class="card-footer">
                        <?php
                        $companyId = $_GET['id'] ?? null;
                        $companySlug = createSlug($companyDetails['company_name'] ?? 'Entreprise inconnue');
                        $companyLink = "/vues/CompanyOffers.php?company_id=" . urlencode($companyDetails['company_id']) . "&name=" . urlencode($companySlug);
                        ?>
            
                        <!-- Bouton VOIR LES OFFRES -->
                        <a href="/vues/Discover.php?search=<?= urlencode($companyDetails['company_name']) ?>" class="btn-offer">
                            <button class="card-button">VOIR LES OFFRES</button>
                        </a>
                        <a href="<?= $companyLink ?>" class="btn-evaluer">
                            <button class="card-button">EVALUER</button>
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

    <!-- Inclusion du fichier JavaScript -->
    <script src="/assets/js/company.js"></script>
</body>

</html>
