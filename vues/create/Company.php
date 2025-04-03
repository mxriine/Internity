<?php
require_once('../../src/Controllers/Login.php');
require_once('../../src/Controllers/CheckAuth.php');
require_once('../../src/Controllers/Offer.php');
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
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
</head>

<body>

    <!-- Navbar -->
    <?php require_once('../include/Navbar.php'); ?>

    <main>
        <!-- Formulaire pour créer une entreprise -->
        <div class="create-company-container">
            <a href="#" class="back" onclick="history.back(); return false;">
                <img src="/assets/icons/arrow.svg" alt="Retour">
                Retour
            </a>
            <h1>Ajouter une entreprise</h1>
            <form action="../../src/Controllers/Companies.php?create=1" method="POST" class="create-company-form">
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
                    <div class="phone-input-wrapper">
                        <select id="phone_prefix" name="phone_prefix"></select>
                        <input type="tel" id="company_phone" name="company_phone" placeholder="6 12 34 56 78"
                            pattern="[0-9 ]{13}" required>
                    </div>
                    <script>
                        document.getElementById('company_phone').addEventListener('input', function (e) {
                            let value = e.target.value.replace(/\D/g, ''); // Remove non-digit characters
                            let formatted = value.replace(/(\d{1})(\d{2})(\d{2})(\d{2})(\d{2})/, '$1 $2 $3 $4 $5');
                            e.target.value = formatted.trim();
                        });
                    </script>
                </div>

                <!-- Adresse -->
                <div class="form-row">
                    <div class="form-group small">
                        <label for="company_rue">N° Rue :</label>
                        <input type="text" id="company_rue" name="company_rue" placeholder="Ex: 81" required>
                    </div>
                    <div class="form-group full">
                        <label for="company_namerue">Nom de la rue :</label>
                        <input type="text" id="company_namerue" name="company_namerue" placeholder="Ex: Avenue"
                            required>
                    </div>
                </div>


                <!-- Code postal + Ville -->
                <div class="form-row">
                    <div class="form-group full">
                        <label for="company_city">Ville :</label>
                        <input type="text" id="company_city" name="company_city" placeholder="Ex: Paris" required>
                    </div>
                    <div class="form-group small">
                        <label for="company_postal_code">Code postal :</label>
                        <input type="text" id="company_postal_code" name="company_postal_code" placeholder="Ex: 75000"
                            required>
                    </div>
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