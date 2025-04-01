<!-- FORMULAIRE EN PHP -->
<?php
require_once('../src/Controllers/Login.php');
require_once('../src/Controllers/CheckAuth.php');
require_once('../src/Controllers/Offer.php');
require_once('Navbar.php');
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
                <div>
                    <a href="#" class="back" onclick="history.back(); return false;">
                        <img src="/assets/icons/arrow.svg" alt="Retour">
                        Retour
                    </a>
                </div>
                <h1>Postuler à l'offre</h1>
                <form id="applicationForm" action="/src/Controllers/Application.php" method="POST" enctype="multipart/form-data">
                    <!-- Nom et Prénom -->
                    <div class="form-group">
                        <div class="form-group-align">
                            <label for="surname">Nom :</label>
                            <input type="text" id="surname" name="surname" placeholder="Entrez votre nom et prénom"
                                required onblur="this.value = this.value.toUpperCase();">
                            <span class="error-message" id="surnameError"></span>

                            <label for="name">Prénom :</label>
                            <input type="text" id="name" name="name" placeholder="Entrez votre nom et prénom" required>
                            <span class="error-message" id="nameError"></span>
                        </div>

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
                        <label for="cv">Importer votre CV :</label>
                        <input type="file" id="cv" name="cv" accept=".pdf" required>
                        <span class="error-message" id="cvError"></span>
                    </div>

                    <!-- Lettre de Motivation Upload -->
                    <div class="form-group">
                        <label for="coverletter">Importer votre lettre de motivation :</label>
                        <input type="file" id="coverletter" name="coverletter" accept=".pdf" required>
                        <span class="error-message" id="coverLetterError"></span>
                    </div>

                    <p style="font-size: 12px; margin-bottom: 0px;">Poids max. 2Mo</p>
                    <p style="font-size: 12px; margin: 0px;">Formats .pdf</p>

                    <!-- Message -->
                    <div class="form-group">
                        <label for="message">Message supplémentaire :</label>
                        <textarea id="message" name="message" rows="5"
                            placeholder="Ajoutez un message si nécessaire"></textarea>
                        <span class="error-message" id="messageError"></span>
                    </div>

                    <!-- Pour sauvegarder le CV et la LM-->
                    <div class="remember">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Sauvegarder mes informations</label>
                    </div>



                    <!-- Submit Button -->
                    <input type="hidden" name="offer_id" value="<?= $offerDetails['offer_id'] ?>">
                    <button type="submit" class="submit-button">Soumettre ma candidature</button>

                </form>
            </section>

            <!-- Card Section -->
            <section class="card-section">
                <div class="card">
                    <div class="card-header">
                        <div class="card-img">
                            <img src="/assets/icons/star-circle.svg" alt="Favori">
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        $skills = $offerModel->getOfferSkills($offerDetails['offer_id']);
                        $limitedSkills = array_slice($skills, 0, 3);
                        ?>

                        <div class="label-container">
                            <?php foreach ($limitedSkills as $skill): ?>
                                <span class="label"><?= htmlspecialchars($skill['skills_name']) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <h2><?= htmlspecialchars($offerDetails['offer_title']) ?></h2>
                        <p><?= htmlspecialchars($offerDetails['offer_desc']) ?></p>
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