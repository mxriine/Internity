<?php
// Récupérer le rôle et le nom depuis la session
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'inconnu';
$surname = isset($_SESSION['name']) ? $_SESSION['name'] : '';

// Déterminer le contenu de la navbar en fonction du rôle
switch ($role) {
    case 'admin':
        $navbar = 'Dashboard';
        break;
    case 'pilote':
        $navbar = 'Dashboard';
        break;
    case 'student':
        $navbar = 'Mon compte';
        break;
    default:
        $navbar = 'Mon compte';
        break;
}

// Vérifier la page actuelle
$current_page = basename($_SERVER['PHP_SELF']);

// Générer la barre de navigation
echo '<header class="header">
        <div class="logo">
            <h1>INTERNITY</h1>
        </div>
        <nav class="nav">
            <ul class="menu-left">
                <li><a href="Discover.php"' . ($current_page == 'Discover.php' ? ' class="active"' : '') . '>Offre</a></li>
                <li><a href="Companies.php"' . ($current_page == 'Companies.php' ? ' class="active"' : '') . '>Entreprise</a></li>
                <li><a href="Home.twig.html"' . ($current_page == 'Home.twig.html' ? ' class="active"' : '') . '>À propos</a></li>
            </ul>
            <div class="menu-right">
                <div class="user-info">
                    <div class="avatar">
                        <img src="/assets/icons/user.svg" alt="Avatar" class="avatar-icon">
                    </div>
                    <div class="user-menu" id="userMenu">
                        <ul>
                            <li><a href="/#">' . htmlspecialchars($navbar) . '</a></li>
                            <li><a href="Login.php">Se déconnecter</a></li>
                        </ul>
                    </div>
                    <div class="user-details">
                        <span>' . htmlspecialchars($surname) . '</span>
                        <span>' . htmlspecialchars($role) . '</span>
                    </div>
                </div>
            </div>
        </nav>
    </header>';
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="/assets/styles.css">
    <script src="/assets/js/navbar.js" defer></script>
</head>
</html>