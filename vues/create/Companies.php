<?php
require_once('../src/Controllers/Login.php');
require_once('../src/Controllers/CheckAuth.php');
require_once('../src/Controllers/Offer.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Internity - Créer une entreprise</title>
    <meta charset="UTF-8">
    <meta name="description" content="Créez une nouvelle entreprise chez Internity">
    <meta name="author" content="Internity">
    <link rel="stylesheet" href="/assets/css/manage/companies.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>

<body>

    <!-- Navbar -->
    <?php require_once('include/Navbar.php'); ?>

    <main>
        <!-- Formulaire pour créer une entreprise -->
        <div class="create-company-container">
            <h1>Créer une nouvelle entreprise</h1>
            <form action="/api/create-company" method="POST" class="create-company-form">
                <!-- Nom de l'entreprise -->
                <div class="form-group">
                    <label for="company_name">Nom de l'entreprise :</label>
                    <input type="text" id="company_name" name="company_name" placeholder="Ex: Internity" required>
                </div>

                <!-- Description de l'entreprise -->
                <div class="form-group">
                    <label for="company_desc">Description de l'entreprise :</label>
                    <textarea id="company_desc" name="company_desc" placeholder="Décrivez l'entreprise en détail..."
                        rows="5"></textarea>
                </div>

                <!-- Secteur d'activité -->
                <div class="form-group">
                    <label for="company_business">Secteur d'activité :</label>
                    <input type="text" id="company_business" name="company_business"
                        placeholder="Ex: Technologie, Santé, Éducation" required>
                </div>

                <!-- Email de l'entreprise -->
                <div class="form-group">
                    <label for="company_email">Email de l'entreprise :</label>
                    <input type="email" id="company_email" name="company_email" placeholder="Ex: contact@internity.com"
                        required>
                </div>

                <!-- Téléphone de l'entreprise -->
                <div class="form-group">
                    <label for="company_phone">Téléphone de l'entreprise :</label>
                    <input type="tel" id="company_phone" name="company_phone" placeholder="Ex: +33612345678"
                        pattern="[0-9+]{10,15}">
                </div>

                <!-- Adresse de l'entreprise -->
                <div class="form-group">
                    <label for="company_street_number">Numéro de rue :</label>
                    <input type="text" id="company_street_number" name="company_street_number" placeholder="Ex: 123"
                        required>
                </div>

                <div class="form-group">
                    <label for="company_street_name">Nom de la rue :</label>
                    <input type="text" id="company_street_name" name="company_street_name"
                        placeholder="Ex: Rue de la République" required>
                </div>

                <div class="form-group">
                    <label for="company_postal_code">Code postal :</label>
                    <input type="text" id="company_postal_code" name="company_postal_code" placeholder="Ex: 75000"
                        required>
                </div>

                <div class="form-group">
                    <label for="company_city">Ville :</label>
                    <input type="text" id="company_city" name="company_city" placeholder="Ex: Paris" required>
                </div>

                <!-- Bouton de soumission -->
                <div class="form-group">
                    <button type="submit" class="submit-button">Créer l'entreprise</button>
                </div>
            </form>
        </div>
    </main>

    <script src="/assets/js/manage/companies.js" defer></script>

</body>

<footer>
    <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
    <p>© 2025 - Internity</p>
</footer>

</html>