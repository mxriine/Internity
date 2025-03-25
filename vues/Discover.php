<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover Page</title>
    <link rel="stylesheet" href="/assets/discover.css">
    <link rel="stylesheet" href="/assets/styles.css">
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
            </div>
        </nav>

        <main>
            <!-- Conteneur principal pour le titre et les étiquettes -->
            <div class="header-section">
                <h1>Pour vous</h1>

                <!-- Barre d'étiquettes -->
                <div class="tags">
                    <input type="checkbox" id="category1" class="hidden-checkbox">
                    <label class="tag" for="category1">Label 1</label>

                    <input type="checkbox" id="category2" class="hidden-checkbox">
                    <label class="tag" for="category2">Label 2</label>

                    <input type="checkbox" id="category3" class="hidden-checkbox">
                    <label class="tag" for="category3">Label 3</label>

                    <input type="checkbox" id="category4" class="hidden-checkbox">
                    <label class="tag" for="category4">Label 4</label>

                    <input type="checkbox" id="category5" class="hidden-checkbox">
                    <label class="tag" for="category5">Label 5</label>
                </div>

                <!-- Bouton "Entreprises" -->
                <div class="enterprise-btn-container">
                    <button class="btn-enterprise">Entreprises</button>
                </div>
            </div>

            <!-- Section des cartes de stages -->
            <section class="cards">
                <div class="card">
                    <div class="card-header">
                        <img src="assets/icons/star.svg" alt="Favori">
                    </div>
                    <div class="card-body">
                        <div class="label-container">
                            <a href="#" class="label">Label</a>
                        </div>
                        <h2>Titre du stage</h2>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ut gravida quam. Aliquam quis cursus tortor.
                        </p>
                        <button class="btn">Voir l'offre</button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <img src="assets/icons/star.svg" alt="Favori">
                    </div>
                    <div class="card-body">
                        <div class="label-container">
                            <a href="#" class="label">Label</a>
                        </div>
                        <h2>Titre du stage</h2>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ut gravida quam. Aliquam quis cursus tortor.
                        </p>
                        <button class="btn">Voir l'offre</button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <img src="assets/icons/star.svg" alt="Favori">
                    </div>
                    <div class="card-body">
                        <div class="label-container">
                            <a href="#" class="label">Label</a>
                        </div>
                        <h2>Titre du stage</h2>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ut gravida quam. Aliquam quis cursus tortor.
                        </p>
                        <button class="btn">Voir l'offre</button>
                    </div>
                </div>
            </section>
        </main>

        <footer>
            <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
            <p>© 2025 - Internity</p>
        </footer>
    </body>
</html>