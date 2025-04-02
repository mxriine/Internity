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
    <title>Internity - Modifier cette une offre</title>
    <meta charset="UTF-8">
    <meta name="description" content="Gérez votre profil chez Internity">
    <meta name="author" content="Internity">
    <link rel="stylesheet" href="/assets/css/createOffer.css">  <!-- C'est le même fichier CSS que pour le formulaire de création d'offre -->
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>

<body>

    <main>
        <!-- Formulaire pour créer une offre -->
        <div class="create-offer-container">
            <h1>Modifier cette Offre</h1>
            <h3>Nom entreprise</h3>
            <form action="/api/create-offer" method="POST" class="create-offer-form">
                <!-- Titre de l'offre -->
                <div class="form-group">
                    <label for="offer_title">Titre de l'offre :</label>
                    <input type="text" id="offer_title" name="offer_title" placeholder="Ex: Développeur Web" required>
                </div>
    
                <!-- Description de l'offre -->
                <div class="form-group">
                    <label for="offer_desc">Description de l'offre :</label>
                    <textarea id="offer_desc" name="offer_desc" placeholder="Décrivez l'offre en détail..." rows="5" required></textarea>
                </div>
    
                <!-- Salaire proposé -->
                <div class="form-group">
                    <label for="offer_salary">Salaire proposé (€) :</label>
                    <input type="number" id="offer_salary" name="offer_salary" step="0.01" placeholder="Ex: 3000.00" value="0.00">
                </div>
    
                <!-- Date de début -->
                <div class="form-group">
                    <label for="offer_start">Date de début :</label>
                    <input type="date" id="offer_start" name="offer_start" required>
                </div>
    
                <!-- Date de fin -->
                <div class="form-group">
                    <label for="offer_end">Date de fin :</label>
                    <input type="date" id="offer_end" name="offer_end" required>
                </div>
    
                <!-- Bouton de soumission -->
                <div class="form-group">
                    <button type="submit" class="submit-button">Modifier l'offre</button>
                </div>
            </form>
        </div>
    </main>

    <script src="/assets/js/createOffer.js" defer></script> <!-- C'est le même fichier JS que pour le formulaire de création d'offre -->

</body>

<footer>
    <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
    <p>© 2025 - Internity</p>
</footer>
