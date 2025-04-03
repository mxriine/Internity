<!-- FORMULAIRE EN PHP -->
<?php
require_once('../../src/Controllers/Login.php');
require_once('../../src/Controllers/CheckAuth.php');
require_once('../../src/Controllers/Offer.php');
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
        <h3>Offres</h3>

        <div class="options">
            <a href="../create/Offer.php">+ Ajouter</a>
        </div>

        <?php foreach ($offers as $offer): ?>
            <?php
            // On récupère les détails de l'entreprise liée à l'OFFRE (pas par company_id, mais par offer_id ici)
            $offerDetails = $offerModel->getOffersCompanies($offer['offer_id']);
            ?>
            <div class="container">

                <div class="title">
                    <h4>Nom :</h4>
                </div>

                <div class="title">
                    <h4>Description :</h4>
                </div>

                <div class="title">
                    <h4>Entreprise :</h4>
                </div>

                <div class="title">
                    <h4>Lieu :</h4>
                </div>

                <div class="title">
                    <h4>Dates :</h4>
                </div>

                <div class="title">
                    <h4>Gratification :</h4>
                </div>

                <div class="name value">
                    <p><?= htmlspecialchars($offer['offer_title']) ?></p>
                </div>

                <div class="desc value">
                    <p><?= htmlspecialchars(explode(':', $offer['offer_desc'])[0] . '. . .') ?>
                    </p>
                </div>

                <div class="company value">
                    <p><?= htmlspecialchars($offerDetails['company_name']) ?></p>
                </div>

                <div class="company-pos value">
                    <p><?= htmlspecialchars($offerDetails['city']) ?>, <?= htmlspecialchars($offerDetails['region']) ?>
                    </p>
                </div>


                <div class="date value">
                    <p><?= date('d/m/Y', strtotime($offer['offer_start'])) ?> -
                        <?= date('d/m/Y', strtotime($offer['offer_end'])) ?>
                    </p>
                </div>

                <div class="salaire value">
                    <p><?= htmlspecialchars($offer['offer_salary']) ?> €</p>
                </div>

                <div class="action">
                    <a href="/vues/update/Offer.php?offer_id=<?= $offer['offer_id'] ?>" class="update">Modifier</a>
                    <a href="/vues/delete/Offer.php?offer_id=<?= $offer['offer_id'] ?>" class="delete">Supprimer</a>
                </div>

            </div>
        <?php endforeach; ?>

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
    .dashboard-menu>ul>li:nth-child(5)::before {
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