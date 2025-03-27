<!-- FORMULAIRE DE CONNEXION (EN PHP) -->
<?php
require_once('../src/Controllers/LoginController.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Internity - Postuler</title>
    <meta charset="UTF-8">
    <meta name="description" content="Postulez à une offre chez Internity">
    <meta name="author" content="Internity">
    <link rel="stylesheet" href="/assets/css/apply.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>

<body class="page-apply">

    <!-- Navbar -->
    <?php require_once('Navbar.php'); ?>

    <main>
        <div class="two-columns">
            <!-- Form Section -->
            <section class="application-form">
                <h1>Postuler à une offre</h1>
                <form id="applicationForm" action="/submit-application" method="POST" enctype="multipart/form-data">
                    <!-- Nom et Prénom -->
                    <div class="form-group">
                        <label for="name">Nom et prénom :</label>
                        <input type="text" id="name" name="name" placeholder="Entrez votre nom et prénom" required>
                        <span class="error-message" id="nameError"></span>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Adresse e-mail :</label>
                        <input type="email" id="email" name="email" placeholder="Entrez votre adresse e-mail" required>
                        <span class="error-message" id="emailError"></span>
                    </div>

                    <!-- Téléphone -->
                    <div class="form-group">
                        <label for="phone">Numéro de téléphone :</label>
                        <input type="tel" id="phone" name="phone" placeholder="Entrez votre numéro de téléphone"
                            required>
                        <span class="error-message" id="phoneError"></span>
                    </div>

                    <!-- CV Upload -->
                    <div class="form-group">
                        <label for="cv">Importer votre CV (PDF uniquement) :</label>
                        <input type="file" id="cv" name="cv" accept=".pdf" required>
                        <span class="error-message" id="cvError"></span>
                    </div>

                    <!-- Lettre de Motivation Upload -->
                    <div class="form-group">
                        <label for="cover-letter">Importer votre lettre de motivation (PDF uniquement) :</label>
                        <input type="file" id="cover-letter" name="cover-letter" accept=".pdf" required>
                        <span class="error-message" id="coverLetterError"></span>
                    </div>

                    <!-- Message -->
                    <div class="form-group">
                        <label for="message">Message supplémentaire :</label>
                        <textarea id="message" name="message" rows="5"
                            placeholder="Ajoutez un message si nécessaire"></textarea>
                        <span class="error-message" id="messageError"></span>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="submit-button">Soumettre ma candidature</button>
                </form>
            </section>



            <!-- Card Section -->
            <section class="card-section">
                <div class="card">
                    <div class="card-header">
                        <img src="assets/icons/xxx.svg" alt="image">
                    </div>
                    <div class="card-body">
                        <div class="label-container">
                            <a class="label">Label</a>
                        </div>
                        <h2>Titre du stage</h2>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ut gravida quam. Aliquam quis
                            cursus tortor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ut gravida
                            quam. Aliquam quis cursus tortor.
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ut gravida quam. Aliquam quis
                            cursus tortor.
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ut gravida quam. Aliquam quis
                            cursus tortor.
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ut gravida quam. Aliquam quis
                            cursus tortor.
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ut gravida quam. Aliquam quis
                            cursus tortor.
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ut gravida quam. Aliquam quis
                            cursus tortor.
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ut gravida quam. Aliquam quis
                            cursus tortor.
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ut gravida quam. Aliquam quis
                            cursus tortor.
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ut gravida quam. Aliquam quis
                            cursus tortor.
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ut gravida quam. Aliquam quis
                            cursus tortor.
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ut gravida quam. Aliquam quis
                            cursus tortor.
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ut gravida quam. Aliquam quis
                            cursus tortor.

                        </p>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
        <p>© 2025 - Internity</p>
    </footer>

    <!-- Inclusion du fichier JavaScript -->
    <script src="/assets/js/apply.js"></script>

</body>



</html>