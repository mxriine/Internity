<?php
// a faire apres
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offre Stage</title>
    <link rel="stylesheet" href="/assets/styles.css">
    <link rel="stylesheet" href="/assets/styles_offer.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">LOGO</div>

            <div class="home">
                <a href="/index.php"><img src="/assets/icons/home.svg" alt="Home Icon"></a>
            </div>

            <div class="search-bar">
                <img src="assets/icons/menu-burger.svg" alt="">
                <img src="assets/icons/search.svg" alt="">
                <input type="text" placeholder="Hinted search text">
            </div>

            <div class="user">
                <a href="/vues/Login.php">
                    <img src="/assets/icons/user.svg" alt="Icône utilisateur">
                </a>

                <div>
                    <h1>{{ user_name }}</h1>
                    <p>{{ user_role }}</p>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <div class="stage-title">
            <text class="title">Titre de l'offre</text>
        </div>
        <div class="stage-content">
            <div class="stage-description">
                <text class="description">Description de l'offre</text>
            </div>
            <div class="stage-popup">
                <div class="card">
                    <img src="/assets/photo.jpg" alt="Photo de l'offre" class="photo-popup">
                    <div class="text-card">
                        <h2>Titre de l'offre</h2>
                        <p>Description de l'offre</p>
                        <p>Compétences requises : </p>
                        <ul>
                            <li>Compétence 1</li>
                            <li>Compétence 2</li>
                            <li>Compétence 3</li>
                    </div>
                </div>
            </div>

        </div>
    </main>
    <footer>
        <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
        <p>© 2025 - Internity</p>
    </footer>
</body>

</html>