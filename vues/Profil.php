<!-- FORMULAIRE DE CONNEXION (EN PHP) -->
<?php
require_once('../src/Controllers/LoginController.php');
require_once('Navbar.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Internity - Profil</title>
    <meta charset="UTF-8">
    <meta name="description" content="Gérez votre profil chez Internity">
    <meta name="author" content="Internity">
    <link rel="stylesheet" href="/assets/css/profil.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>

<body class="page-profile">

    <!-- Main Content -->
    <main class="two-columns">
        <!-- Sidebar Menu -->
        <aside class="sidebar">
            <ul class="menu">
                <li class="menu-item active" data-tab="account">Mon compte</li>
                <li class="menu-item" data-tab="password">Sécurité et Confidentialité</li>
                <li class="menu-item" data-tab="wishlist">Wishlist</li>
                <li class="menu-item" data-tab="offers">Mes offres</li>
            </ul>
        </aside>

        <!-- Dynamic Content -->
        <section class="content">


            <!-- Mon compte -->


            <div id="account" class="tab-content active">
                <h2>Mon compte</h2>

                <!-- Informations personnelles -->
                <div class="account-info">
                    <h3>Mes informations</h3>
                    <form id="accountForm">
                        <div class="form-group">
                            <label for="firstName">Prénom :</label>
                            <input type="text" id="firstName" name="firstName" placeholder="Entrez votre prénom" value="John" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Nom :</label>
                            <input type="text" id="lastName" name="lastName" placeholder="Entrez votre nom" value="Doe" required>
                        </div>
                        <div class="form-group">
                        <label for="email">Email :</label>
                            <input type="email" id="email" name="email" placeholder="Entrez votre email" value="john.doe@example.com" required>
                        </div>
                        <button type="submit" class="submit-button">Mettre à jour mes informations</button>
                    </form>
                </div>

                <!-- Gestion des documents -->
                <div class="account-documents">
                    <h3>Mes documents</h3>
                    <div class="document-upload">
                        <label for="cvUpload">Mon CV (PDF uniquement) :</label>
                        <input type="file" id="cvUpload" name="cvUpload" accept=".pdf">
                        <p id="cvFileName">Aucun fichier sélectionné</p>
                    </div>
                    <div class="document-upload">
                        <label for="coverLetterUpload">Ma lettre de motivation (PDF uniquement) :</label>
                        <input type="file" id="coverLetterUpload" name="coverLetterUpload" accept=".pdf">
                        <p id="coverLetterFileName">Aucun fichier sélectionné</p>
                    </div>
                    <button class="submit-button" id="saveDocuments">Enregistrer les documents</button>
                </div>
            </div>


            <!-- Sécurité -->


            <!-- Sécurité et Confidentialité -->
            <div id="password" class="tab-content">
                <h2>Sécurité et Confidentialité</h2>
            
                <!-- Informations actuelles -->
                <div class="security-info">
                    <div class="info-row">
                        <label>Email :</label>
                        <span id="currentEmail">john.doe@example.com</span>
                        <button id="editEmailButton" class="edit-button">Changer l'email</button>
                    </div>
                    <div class="info-row">
                        <label>Mot de passe :</label>
                        <span id="currentPassword">●●●●●●●●●●●●●●●</span>
                        <img src="/assets/images/oeil.png" alt="Afficher/Masquer" id="togglePassword" class="password-toggle" style="cursor: pointer; width: 20px; margin-left: 10px;">
                        <button id="editPasswordButton" class="edit-button">Changer le mot de passe</button>
                    </div>
                </div>
            
                <!-- Formulaire de modification pour l'email (caché par défaut) -->
                <div id="editEmailForm" class="edit-form" style="display: none;">
                    <h3>Modifier votre email</h3>
                    <form id="emailForm">
                        <div class="form-group">
                            <label for="newEmail">Nouvel email :</label>
                            <input type="email" id="newEmail" name="newEmail" placeholder="Entrez votre nouvel email" required>
                        </div>
                        <button type="submit" class="submit-button">Confirmer</button>
                        <button type="button" id="cancelEmailEditButton" class="cancel-button">Annuler</button>
                    </form>
                </div>
            
                <!-- Formulaire de modification pour le mot de passe (caché par défaut) -->
                <div id="editPasswordForm" class="edit-form" style="display: none;">
                    <h3>Modifier votre mot de passe</h3>
                    <form id="passwordForm">
                        <div class="form-group">
                            <label for="newPassword">Nouveau mot de passe :</label>
                            <input type="password" id="newPassword" name="newPassword" placeholder="Entrez votre nouveau mot de passe" required>
                        </div>
                        <button type="submit" class="submit-button">Confirmer</button>
                        <button type="button" id="cancelPasswordEditButton" class="cancel-button">Annuler</button>
                    </form>
                </div>
            </div>

            <!-- Wishlist -->
            <div id="wishlist" class="tab-content">
                <h2>Ma wishlist</h2>
            
                <!-- Liste des offres -->
                <div class="wishlist-container">
                    <!-- Offre 1 -->
                    <div class="wishlist-item">
                        <div class="wishlist-header">
                            <h3>Stage Développeur Web</h3>
                            <img src="/assets/images/CoeurRemplis.png" alt="Cœur rempli" class="wishlist-heart active" data-offer-id="1">
                        </div>
                        <p><strong>Entreprise :</strong> Internity</p>
                        <p><strong>Durée :</strong> 6 mois</p>
                        <p><strong>Lieu :</strong> Paris, France</p>
                        <p><strong>Date limite :</strong> 30/11/2023</p>
                        <button class="wishlist-button">Voir plus</button>
                    </div>
            
                    <!-- Offre 2 -->
                    <div class="wishlist-item">
                        <div class="wishlist-header">
                            <h3>Stage Designer UX/UI</h3>
                            <img src="/assets/images/CoeurVide.png" alt="Cœur vide" class="wishlist-heart" data-offer-id="2">
                        </div>
                        <p><strong>Entreprise :</strong> CréaTech</p>
                        <p><strong>Durée :</strong> 4 mois</p>
                        <p><strong>Lieu :</strong> Lyon, France</p>
                        <p><strong>Date limite :</strong> 15/12/2023</p>
                        <button class="wishlist-button">Voir plus</button>
                    </div>
            
                    <!-- Offre 3 -->
                    <div class="wishlist-item">
                        <div class="wishlist-header">
                            <h3>Stage Marketing Digital</h3>
                            <img src="/assets/images/CoeurRemplis.png" alt="Cœur rempli" class="wishlist-heart active" data-offer-id="3">
                        </div>
                        <p><strong>Entreprise :</strong> MarketPro</p>
                        <p><strong>Durée :</strong> 3 mois</p>
                        <p><strong>Lieu :</strong> Toulouse, France</p>
                        <p><strong>Date limite :</strong> 01/01/2024</p>
                        <button class="wishlist-button">Voir plus</button>
                    </div>
                </div>
            </div>



            <!-- Mes offres -->



            <!-- Offres -->
            <div id="offers" class="tab-content">
                <h2>Mes offres</h2>
            
                <!-- Liste des offres -->
                <div class="offers-container">
                    <!-- Offre 1 -->
                    <div class="offer-item">
                        <div class="offer-header">
                            <h3>Stage Développeur Web</h3>
                            <span class="offer-status pending">En attente</span>
                        </div>
                        <p><strong>Entreprise :</strong> Internity</p>
                        <p><strong>Durée :</strong> 6 mois</p>
                        <p><strong>Lieu :</strong> Paris, France</p>
                        <p><strong>Date limite :</strong> 30/11/2023</p>
                        <button class="offer-button">Voir plus</button>
                    </div>
            
                    <!-- Offre 2 -->
                    <div class="offer-item">
                        <div class="offer-header">
                            <h3>Stage Designer UX/UI</h3>
                            <span class="offer-status accepted">Accepté</span>
                        </div>
                        <p><strong>Entreprise :</strong> CréaTech</p>
                        <p><strong>Durée :</strong> 4 mois</p>
                        <p><strong>Lieu :</strong> Lyon, France</p>
                        <p><strong>Date limite :</strong> 15/12/2023</p>
                        <button class="offer-button">Voir plus</button>
                    </div>
            
                    <!-- Offre 3 -->
                    <div class="offer-item">
                        <div class="offer-header">
                            <h3>Stage Marketing Digital</h3>
                            <span class="offer-status rejected">Refusé</span>
                        </div>
                        <p><strong>Entreprise :</strong> MarketPro</p>
                        <p><strong>Durée :</strong> 3 mois</p>
                        <p><strong>Lieu :</strong> Toulouse, France</p>
                        <p><strong>Date limite :</strong> 01/01/2024</p>
                        <button class="offer-button">Voir plus</button>
                    </div>
                </div>
            </div>



        </section>
    </main>

    <!-- Footer -->
    <footer>
        <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
        <p>© 2025 - Internity</p>
    </footer>

    <!-- Inclusion du fichier JavaScript -->
    <script src="/assets/js/profil.js"></script>
</body>

</html>