<?php

// Récupérer le rôle et le nom depuis la session
$role = $_SESSION['role'] ?? 'inconnu';
$name = $_SESSION['name'] ?? '';

// Déterminer le contenu de la navbar en fonction du rôle et définir le lien correspondant
if ($role === 'admin' || $role === 'pilote') {
    $navbar = '<a href="/vues/dashboard/Home.php">Dashboard</a>';
} else {
    $navbar = '<a href="Profil.php?page=account">Mon compte</a>';
}
// Vérifier la page actuelle
$current_page = basename($_SERVER['PHP_SELF']);
$current_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
?>

<header class="header">
    <div class="logo">
        <h1>INTERNITY</h1>
    </div>
    <nav class="nav">
        <!-- Bouton burger -->
        <div class="burger-menu" onclick="toggleMenu()">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>

        <!-- Menu -->
        <ul class="menu-left" id="navMenu">
            <li><a href="/vues/Discover.php" <?= ($current_page == 'Discover.php') ? 'class="active"' : '' ?>>Offre</a>
            </li>
            <li><a href="/vues/Companies.php" <?= ($current_path == 'vues/Companies.php') ? 'class="active"' : '' ?>>Entreprise</a></li>
            <li><a href="/" <?= ($current_page == '/') ? 'class="active"' : '' ?>>À propos</a></li>
        </ul>
        <div class="menu-right">
            <div class="user-info">
                <div class="avatar">
                    <img src="/assets/icons/user.svg" alt="Avatar" class="avatar-icon">
                </div>
                <div class="user-menu" id="userMenu">
                    <ul>
                        <li><?= $navbar ?></li>
                        <li><a href="../../src/Controllers/Logout.php">Se déconnecter</a></li>
                    </ul>
                </div>
                <div class="user-details">
                    <span><?= htmlspecialchars($name) ?></span>
                    <span><?= htmlspecialchars($role) ?></span>
                </div>

            </div>
        </div>
    </nav>
</header>

<script src="/assets/js/navbar.js"></script>