<!-- FORMULAIRE EN PHP -->
<?php
require_once('../../src/Controllers/Login.php');
require_once('../../src/Controllers/CheckAuth.php');
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

    <?php include 'includes/menu.php'; ?>

    <main>

        <h3>Pilotes</h3>

        <div class="options">
            <a href=""><button>Ajouter</button></a>
        </div>

        <div class="container">
            <div class="name">
                <h5>Nom</h5>
            </div>

            <div class="second-name">
                <h4>Prénom</h4>
            </div>

            <div class="email">
                <h4>Adresse Email</h4>
            </div>

            <div class="promo">
                <h4>Promotion</h4>
            </div>

            <div class="action">
                <h4>Action</h4>
            </div>
        </div>



        <div class="container">
            <div class="name">
                <p>Raynaud</p>
            </div>

            <div class="second-name">
                <p>Muriel</p>
            </div>

            <div class="email">
                <p>muriel.raynaud@cesi.fr</p>
            </div>

            <div class="promo">
                <p>CPI A2 Informatique</p>
            </div>

            <div class="action">
                <a href="" class="Modify">Modifier</a>
                <a href="" class="delete">Supprimer</a>
            </div>
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