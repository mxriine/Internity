<?php
require_once('../../src/Controllers/Login.php');
require_once('../../src/Controllers/CheckAuth.php');
require_once('../../src/Controllers/Companies.php'); // Assurez-vous que ce fichier existe pour gérer les entreprises
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Internity - Modifier une entreprise</title>
    <meta charset="UTF-8">
    <meta name="description" content="Créez une nouvelle entreprise chez Internity">
    <meta name="author" content="Internity">
    <link rel="stylesheet" href="/assets/css/manage/companies.css">
    <!-- C'est le même fichier CSS que pour le formulaire de création d'entreprise -->
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>

<body>

    <?php require_once('../include/Navbar.php'); ?>

    <main>
        <!-- Formulaire pour créer une entreprise -->
        <div class="create-company-container">
            <a href="#" class="back" onclick="history.back(); return false;">
                <img src="/assets/icons/arrow.svg" alt="Retour">
                Retour
            </a>
            <h1>Modifier l'entreprise</h1>
            <h2><?= htmlspecialchars($companyDetails['company_name']) ?></h2>
            <form action="../../src/Controllers/Companies.php?edit=1" method="POST" class="modify-company-form">
            <input type="hidden" name="company_id" value="<?= htmlspecialchars($companyDetails['company_id']) ?>">

                <!-- Nom de l'entreprise -->
                <div class="form-group">
                    <label for="company_name">Nom de l'entreprise :</label>
                    <input type="text" id="company_name" name="company_name"
                        placeholder="<?= htmlspecialchars($companyDetails['company_name']) ?>">
                </div>

                <!-- Description de l'entreprise -->
                <div class="form-group">
                    <label for="company_desc">Description de l'entreprise :</label>
                    <textarea id="company_desc" name="company_desc"
                        placeholder="<?= htmlspecialchars($companyDetails['company_desc']) ?>" rows="5"></textarea>
                </div>

                <!-- Secteur d'activité -->
                <div class="form-group">
                    <label for="company_business">Secteur d'activité :</label>
                    <input type="text" id="company_business" name="company_business"
                        placeholder="<?= htmlspecialchars($companyDetails['company_business']) ?>">
                </div>

                <!-- Email de l'entreprise -->
                <div class="form-group">
                    <label for="company_email">Email de l'entreprise :</label>
                    <input type="email" id="company_email" name="company_email"
                        placeholder="<?= htmlspecialchars($companyDetails['company_email']) ?>">
                </div>

                <!-- Téléphone de l'entreprise -->
                <div class="form-group">
                    <label for="company_phone">Téléphone de l'entreprise :</label>
                    <div class="phone-input-wrapper">
                        <select id="phone_prefix" name="phone_prefix"></select>
                        <input type="tel" id="company_phone" name="company_phone"
                            placeholder="<?= htmlspecialchars($companyDetails['company_phone']) ?>" pattern="[0-9]{9}"
                        >
                    </div>
                </div>

                <!-- Adresse -->
                <div class="form-row">
                    <div class="form-group small">
                        <label for="company_rue">N° Rue :</label>
                        <input type="text" id="company_rue" name="company_rue" placeholder="">
                    </div>
                    <div class="form-group full">
                        <label for="company_namerue">Nom de la rue :</label>
                        <input type="text" id="company_namerue" name="company_namerue" placeholder="<?= htmlspecialchars($companyDetails['company_address']) ?>"
                        >
                    </div>
                </div>

                <!-- Code postal + Ville -->
                <div class="form-row">
                    <div class="form-group full">
                        <label for="company_city">Ville :</label>
                        <input type="text" id="company_city" name="company_city"
                            placeholder="<?= htmlspecialchars($companyDetails['city_name']) ?>">
                    </div>
                    <div class="form-group small">
                        <label for="company_postal_code">Code postal :</label>
                        <input type="text" id="company_postal_code" name="company_postal_code"
                            placeholder="<?= htmlspecialchars($companyDetails['city_code']) ?>">
                    </div>
                </div>

                <!-- Bouton de soumission -->
                <div class="form-group">
                    <button type="submit" class="submit-button">Modifier l'entreprise</button>
                </div>
            </form>
        </div>
    </main>

    <!--<script src="/assets/js/manage/companies.js" defer></script>-->

</body>

<footer>
    <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
    <p>© 2025 - Internity</p>
</footer>

</html>