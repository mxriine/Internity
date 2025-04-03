<!-- FORMULAIRE EN PHP -->
<?php
require_once('../../src/Controllers/Login.php');
require_once('../../src/Controllers/CheckAuth.php');
require_once('../../src/Controllers/Companies.php');
?>

<!doctype html>
<html lang="fr">

<head>
    <title>Internity - Discover</title>
    <meta charset="UTF-8">
    <meta name="description" content="Internity - Le meilleur de l'Internet">
    <meta name="author" content="Internity">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/dashboard/style.css">
    <link rel="stylesheet" href="/assets/css/dashboard/list.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <!-- Barre de navigation -->
    <?php include '../include/Navbar.php'; ?>

    <?php include 'includes/Menu.php'; ?>

    <main>

        <h3>Entreprises</h3>

        <div class="options">
            <input type="text" placeholder="Pilote">
            <input type="text" placeholder="Promotion">
            <a href="../create/Company.php">Ajouter</a>
        </div>

        <?php foreach ($companies as $company): ?>
            <?php
            // On récupère les détails de l'entreprise
            $Details = $companiesModel->getCompanyById($company['company_id']);
            ?>
            <div class="container">

                <div class="title">
                    <h4>Nom :</h4>
                </div>

                <div class="title">
                    <h4>Domaine d'expertise :</h4>
                </div>

                <div class="title">
                    <h4>Lieu :</h4>
                </div>

                <div class="title">
                    <h4>Email :</h4>
                </div>

                <div class="title">
                    <h4>Phone :</h4>
                </div>

                <div class="name value">
                    <p><?= htmlspecialchars($company['company_name']) ?></p>
                </div>

                <div class="desc value">
                    <p><?= htmlspecialchars($company['company_business']) ?></p>
                </div>

                <div class="city value">
                    <p><?= htmlspecialchars($company['company_address']) ?>,
                        <?= htmlspecialchars($Details['city_name']) ?></p>
                </div>

                <div class="email value">
                    <p><?= htmlspecialchars($company['company_email']) ?></p>
                </div>

                <div class="phone value">
                    <p><?= htmlspecialchars($company['company_phone']) ?></p>
                </div>

                <div class="action">
                    <a href="/vues/update/Company.php?company_id=<?= $company['company_id'] ?>"
                        class="update">Modifier</a>
                    <a href="/vues/delete/Company.php?company_id=<?= $company['company_id'] ?>"
                        class="delete">Supprimer</a>
                </div>

            </div>
        <?php endforeach; ?>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page_actuelle > 1): ?>
                <a
                    href="?page=<?= $page_actuelle - 1 ?>&search=<?= urlencode($search) ?>&location=<?= urlencode($location) ?>">Précédent</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&location=<?= urlencode($location) ?>"
                    class="<?= $i === $page_actuelle ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page_actuelle < $total_pages): ?>
                <a
                    href="?page=<?= $page_actuelle + 1 ?>&search=<?= urlencode($search) ?>&location=<?= urlencode($location) ?>">Suivant</a>
            <?php endif; ?>
        </div>

    </main>

</body>

<style>
    .dashboard-menu>ul>li:nth-child(<?php if ($_SESSION['role']!="pilote"): ?>4<?php endif; ?><?php if ($_SESSION['role']=="pilote"): ?>3<?php endif; ?>)::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: var(--widthslect);
        background-color: var(--CpBlue);
    }
</style>

</html>