<!-- FORMULAIRE EN PHP -->
<?php
require_once('../src/Controllers/Login.php');
require_once('../src/Controllers/CheckAuth.php');
require_once('../src/Controllers/Application.php');
require_once('../src/Controllers/Wishlist.php');

$showSuccess = isset($_GET['success']) && $_GET['success'] == 1;

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$surname = isset($_SESSION['surname']) ? $_SESSION['surname'] : '';

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

    <!-- Barre de navigation -->
    <?php include 'include/Navbar.php'; ?>

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
        <?php
        $page = $_GET['page'] ?? 'password'; // Par défaut, afficher "Mon compte"
        ?>

        <section class="content">

            <!-- Mon compte -->


            <div id="account" class="tab-content <?= $page === 'account' ? 'active' : '' ?>">
                <h2>Mon compte</h2>

                <!-- Informations personnelles -->
                <div class="account-info">
                    <h3>Mes informations</h3>
                    <form id="accountForm">
                        <div class="form-group">
                            <label for="firstName">Prénom :</label>
                            <input type="text" id="firstName" name="firstName" value="<?php echo $name; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Nom :</label>
                            <input type="text" id="lastName" name="lastName" value="<?php echo $surname; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="pilote">Pilote :</label>
                            <input type="pilote" id="pilote" name="pilote" value="Muriel Raynaud" disabled>
                        </div>
                        <div class="form-group">
                            <label for="promotion">Promotion :</label>
                            <input type="promotion" id="promotion" name="promotion" value="CPI A1" disabled>
                        </div>
                        <!-- <button type="submit" class="submit-button">Mettre à jour mes informations</button> -->
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
            <div id="password" class="tab-content <?= $page === 'password' ? 'active' : '' ?>">
                <h2>Sécurité et Confidentialité</h2>

                <!-- Informations actuelles -->
                <div class="security-info">
                    <div class="info-row">
                        <label>Email :</label>
                        <span id="currentEmail"><?php echo htmlspecialchars($email); ?></span>
                        <button id="editEmailButton" class="edit-button">Changer l'email</button>
                    </div>
                    <div class="info-row">
                        <label>Mot de passe :</label>
                        <span id="currentPassword">●●●●●●●●●●●●●●●</span>
                        <img src="/assets/images/oeil.png" alt="Afficher/Masquer" id="togglePassword"
                            class="password-toggle" style="cursor: pointer; width: 20px; margin-left: 10px;">
                        <button id="editPasswordButton" class="edit-button">Changer le mot de passe</button>
                    </div>
                </div>

                <!-- Formulaire de modification pour l'email (caché par défaut) -->
                <div id="editEmailForm" class="edit-form" style="display: none;">
                    <h3>Modifier votre email</h3>
                    <form id="emailForm">
                        <div class="form-group">
                            <label for="newEmail">Nouvel email :</label>
                            <input type="email" id="newEmail" name="newEmail" placeholder="Entrez votre nouvel email"
                                required>
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
                            <input type="password" id="newPassword" name="newPassword"
                                placeholder="Entrez votre nouveau mot de passe" required>
                        </div>
                        <button type="submit" class="submit-button">Confirmer</button>
                        <button type="button" id="cancelPasswordEditButton" class="cancel-button">Annuler</button>
                    </form>
                </div>
            </div>

            <!-- Wishlist -->
            <div id="wishlist" class="tab-content <?= $page === 'wishlist' ? 'active' : '' ?>">
                <h2>Ma wishlist</h2>

                <!-- Liste des offres -->
                <div class="wishlist-container">
                    <?php foreach ($wishlist as $item): ?>
                        <div class="wishlist-item" data-offer-id="<?= htmlspecialchars($item['offer_id']) ?>">
                            <div class="wishlist-header">
                                <h3><?= htmlspecialchars($item['offer_title'] ?? 'Offre inconnue') ?></h3>

                                <!-- Bouton pour retirer l'offre -->
                                <button class="remove-offer" data-offer-id="<?= htmlspecialchars($item['offer_id']) ?>"
                                    title="Retirer de la wishlist">
                                    <img src="/assets/images/CoeurRemplis.png" alt="Retirer de la wishlist"
                                        class="wishlist-heart">
                                </button>
                            </div>

                            <p><strong>Entreprise :</strong>
                                <?= htmlspecialchars($item['company_name'] ?? 'Entreprise inconnue') ?>
                            </p>

                            <p><strong>Durée :</strong>
                                <?php
                                if (!empty($item['offer_start']) && !empty($item['offer_end'])) {
                                    $startDate = new DateTime($item['offer_start']);
                                    $endDate = new DateTime($item['offer_end']);
                                    $interval = $startDate->diff($endDate);
                                    echo htmlspecialchars($interval->m . ' mois et ' . $interval->d . ' jours');
                                } else {
                                    echo "Durée inconnue";
                                }
                                ?>
                            </p>

                            <p><strong>Lieu :</strong>
                                <?= htmlspecialchars($item['city_name'] ?? 'Ville inconnue') . ', ' . htmlspecialchars($item['region_name'] ?? 'Région inconnue') ?>
                            </p>

                            <button class="wishlist-button" onclick="window.location.href='/vues/Offer.php?offer_id=<?= htmlspecialchars($item['offer_id']) ?>'">Voir plus</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Mes offres -->
            <!-- Offres -->
            <div id="offers" class="tab-content <?= $page === 'offers' ? 'active' : '' ?>">
                <h2>Mes offres</h2>

                <!-- Liste des offres -->
                <!-- Liste des offres -->
                <div class="offers-container">
                    <?php foreach ($applications as $application): ?>
                        <div class="offer-item">
                            <div class="offer-header">
                                <h3><?= htmlspecialchars($application['offer_title'] ?? 'Offre inconnue') ?></h3>
                                <?php
                                $status = strtolower($application['apply_status'] ?? 'unknown'); // Met en minuscule pour éviter les erreurs de casse
                            
                                $statusMapping = [
                                    'en cours' => ['offer-status pending', 'En cours'],
                                    'acceptée' => ['offer-status accepted', 'Acceptée'],
                                    'rejetée' => ['offer-status rejected', 'Rejetée']
                                ];

                                $statusClass = $statusMapping[$status][0] ?? 'offer-status unknown';
                                $statusText = $statusMapping[$status][1] ?? 'Statut inconnu';
                                ?>
                                <span class="<?= htmlspecialchars($statusClass) ?>">
                                    <?= htmlspecialchars($statusText) ?>
                                </span>
                            </div>
                            <p><strong>Entreprise :</strong>
                                <?= htmlspecialchars($application['company_name'] ?? 'Entreprise inconnue') ?></p>
                            <p><strong>Durée :</strong>
                                <?php
                                if (!empty($application['offer_start']) && !empty($application['offer_end'])) {
                                    $startDate = new DateTime($application['offer_start']);
                                    $endDate = new DateTime($application['offer_end']);
                                    $interval = $startDate->diff($endDate);
                                    echo htmlspecialchars($interval->m . ' mois et ' . $interval->d . ' jours');
                                } else {
                                    echo "Durée inconnue";
                                }
                                ?>
                            </p>
                            <p><strong>Lieu :</strong>
                                <?= htmlspecialchars($application['city_name'] ?? 'Ville inconnue') . ', ' . htmlspecialchars($application['region_name'] ?? 'Region inconnue') ?>
                            </p>
                            <p><strong>Date limite :</strong>
                                <?= htmlspecialchars($application['company_name'] ?? 'Entreprise inconnue') ?></p>
                            <button class="offer-button">Voir plus</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer style="position: fixed;">
        <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
        <p>© 2025 - Internity</p>
    </footer>

    <!-- Inclusion du fichier JavaScript -->
    <script src="/assets/js/profil.js"></script>
</body>

</html>