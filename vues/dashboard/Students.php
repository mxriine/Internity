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
        <h3>Étudiants</h3>

        <div class="options">
            <a href="">Ajouter</a>
        </div>

        <?php if (!empty($error_message)): ?>
            <p style="font-size: 2.5vh; margin-left: 40vw"><?= $error_message ?></p>
        <?php endif; ?>

        <?php foreach ($students as $student): ?>
            <div class="container">

                <div class="title"><h4>Nom :</h4></div>
                <div class="title"><h4>Prénom :</h4></div>
                <div class="title"><h4>Pilote :</h4></div>
                <div class="title"><h4>Promotion :</h4></div>
                <div class="title"><h4>Email :</h4></div>

                <div class="name value">
                    <p><?= htmlspecialchars($student['user_surname']) ?></p>
                </div>

                <div class="desc value">
                    <p><?= htmlspecialchars($student['user_name']) ?></p>
                </div>

                <div class="company value">
                    <p><?= htmlspecialchars($student['pilote_name'] ?? 'Aucune') . " " . htmlspecialchars($student['pilote_surname'] ?? '') ?></p>
                </div>

                <div class="company value">
                    <p><?= htmlspecialchars($student['promotion_name'] ?? 'Aucune') ?></p>
                </div>

                <div class="company-pos value">
                    <p><?= htmlspecialchars($student['user_email']) ?></p>
                </div>

                <div class="action">
                    <a href="/vues/update/User.php?user_id=<?= $student['user_id'] ?>" class="update">Modifier</a>
                    <a href="/vues/delete/User.php?user_id=<?= $student['user_id'] ?>" class="delete">Supprimer</a>
                </div>

            </div>
        <?php endforeach; ?>

        <div class="pagination">
            <?php if (isset($page_actuelle) && $page_actuelle > 1): ?>
                <a href="?page=<?= $page_actuelle - 1 ?>&search=<?= urlencode($search ?? '') ?>&location=<?= urlencode($location ?? '') ?>">Précédent</a>
            <?php endif; ?>

            <?php for ($i = 1; isset($total_pages) && $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>&location=<?= urlencode($location ?? '') ?>"
                   class="<?= ($i === $page_actuelle) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if (isset($page_actuelle) && isset($total_pages) && $page_actuelle < $total_pages): ?>
                <a href="?page=<?= $page_actuelle + 1 ?>&search=<?= urlencode($search ?? '') ?>&location=<?= urlencode($location ?? '') ?>">Suivant</a>
            <?php endif; ?>
        </div>
    </main>
</body>

<style>
    .dashboard-menu > ul > li:nth-child(2)::before {
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