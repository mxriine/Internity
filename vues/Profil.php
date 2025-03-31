<!-- FORMULAIRE EN PHP -->
<?php
require_once('../src/Controllers/Login.php');
require_once('../src/Controllers/CheckAuth.php');
require_once('Navbar.php');

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

    <!-- Main Content -->
    <main class="two-columns">
        <!-- Sidebar Menu -->
        <aside class="sidebar">
            <ul class="menu">
                <li class="menu-item active" data-tab="account">Mon compte</li>
                <li class="menu-item" data-tab="wishlist">Wishlist</li>
                <li class="menu-item" data-tab="password">Mots de passe</li>
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
                            <input type="text" id="firstName" name="firstName" placeholder="Entrez votre prénom" value="<?php echo htmlspecialchars($name); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Nom :</label>
                            <input type="text" id="lastName" name="lastName" placeholder="Entrez votre nom" value="<?php echo htmlspecialchars($surname); ?>" required>
                        </div>
                        <div class="form-group">
                        <label for="email">Email :</label>
                            <input type="email" id="email" name="email" placeholder="Entrez votre email" value="<?php echo htmlspecialchars($email); ?>" required>
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

            <!-- Wishlist -->
            <div id="wishlist" class="tab-content">
                <h2>Wishlist</h2>
                <p>Contenu lié à votre liste de souhaits.</p>
            </div>

            <!-- Mots de passe -->
            <div id="password" class="tab-content">
                <h2>Mots de passe</h2>
                <p>Contenu lié à la gestion de vos mots de passe.</p>
            </div>

            <!-- Mes offres -->
            <div id="offers" class="tab-content">
                <h2>Mes offres</h2>
                <p>Contenu lié à vos offres postulées ou enregistrées.</p>
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