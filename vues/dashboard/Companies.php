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
            <a href=""><button>Ajouter</button></a>
        </div>

        <div class="container">
            <div class="name">
                <h4>Nom</h4>
            </div>

            <div class="name">
                <h4>Domaine d'expertise</h4>
            </div>

            <div class="name">
                <h4>Lieu</h4>
            </div>

            <div class="name">
                <h4>Email</h4>
            </div>

            <div class="name">
                <h4>Phone</h4>
            </div>

            <div class="action">
                <h4>Action</h4>
            </div>
        </div>



        <?php foreach ($companies as $company): ?>
            <?php
            // On récupère les détails de l'entreprise
            $cityDetails = $companiesModel->getCompanyById($company['offer_id']);
            ?>
            <div class="container">

                <div class="name">
                    <p><?= htmlspecialchars($company['company_name']) ?></p>
                </div>

                <div class="desc">
                    <p><?= htmlspecialchars($company['company_business']) ?></p>
                </div>

                <div class="city">
                    <p><?= htmlspecialchars($cityDetails['city_name']) ?>, <?= htmlspecialchars($cityDetails['region_name']) ?></p>
                </div>

                <div class="email">
                    <p><?= htmlspecialchars($company['company_email']) ?></p>
                </div>

                <div class="phone">
                    <p><?= htmlspecialchars($company['company_phone']) ?></p>
                </div>

                <div class="action">
                    <a href="/vues/update/Offer.php?offer_id=<?= $offer['offer_id'] ?>" class="update">Modifier</a>
                    <a href="/vues/delete/Offer.php?offer_id=<?= $offer['offer_id'] ?>" class="delete">Supprimer</a>
                </div>

            </div>
        <?php endforeach; ?>

    </main>

</body>

<style>
    .dashboard-menu>ul>li:nth-child(4)::before {
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