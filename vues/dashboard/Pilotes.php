<!-- FORMULAIRE EN PHP -->
<?php
require_once('../../src/Controllers/Login.php');
require_once('../../src/Controllers/CheckAuth.php');
require_once('../../src/Controllers/User.php');
?>

<!doctype html>
<html lang="fr">

<head>
    <title>Internity - Discover</title>
    <meta charset="UTF-8">
    <meta name="description" content="Internity - Le meilleur de l'Internet">
    <meta name="author" content="Internityq">
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
        <h3>Pilotes</h3>

        <div class="options">
            <input type="text" placeholder="Pilote">
            <input type="text" placeholder="Promotion">
            <a href="../create/Pilote.php">+ Ajouter</a>
        </div>

        <?php foreach ($pilotes as $pilote): ?>
            <?php
            ?>
            <div class="container">

                <div class="title">
                    <h4>Nom :</h4>
                </div>

                <div class="title">
                    <h4>Prénom :</h4>
                </div>

                <div class="title">
                    <h4>Promotion :</h4>
                </div>

                <div class="title">
                    <h4>Email :</h4>
                </div>


                <div class="name value">
                    <p><?= htmlspecialchars($pilote['user_surname']) ?></p>
                </div>

                <div class="desc value">
                    <p><?= htmlspecialchars($pilote['user_name']) ?>
                    </p>
                </div>

                <div class="company value">
                    <p><?= htmlspecialchars($pilote['promotion_name'] ?? 'Aucune') ?></p>
                </div>

                <div class="company-pos value">
                    <p><?= htmlspecialchars($pilote['user_email']) ?></p>
                </div>

                <div class="action">
                    <a href="/vues/update/Pilote.php?pilote_id=<?= $pilote['user_id'] ?>" class="update">Modifier</a>
                    <a href="/vues/delete/Pilote.php?pilote_id=<?= $pilote['user_id'] ?>" class="delete">Supprimer</a>
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
    .dashboard-menu>ul>li:nth-child(3)::before {
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