<?php

// Récupérer le rôle et le nom depuis la session
$role = $_SESSION['role'] ?? 'inconnu';
$surname = $_SESSION['name'] ?? '';

// Déterminer le contenu de la navbar en fonction du rôle et définir le lien correspondant
if ($role === 'admin' || $role === 'pilote') {
    $navbar = '<a href="/vues/dashboard/Home.php">Dashboard</a>';
} else {
    $navbar = '<a href="Profil.php">Mon compte</a>';
}
// Vérifier la page actuelle
$current_page = basename($_SERVER['PHP_SELF']);
?>

<header class="header">
    <div class="logo">
        <h1>INTERNITY</h1>
    </div>
    <nav class="nav">
        <ul class="menu-left">
            <li><a href="Discover.php" <?= ($current_page == 'Discover.php') ? 'class="active"' : '' ?>>Offre</a>
            </li>
            <li><a href="Companies.php" <?= ($current_page == 'Companies.php') ? 'class="active"' : '' ?>>Entreprise</a>
            </li>
            <li><a href="Home.twig.html" <?= ($current_page == 'Home.twig.html') ? 'class="active"' : '' ?>>À propos</a>
            </li>
        </ul>
        <div class="menu-right">
            <div class="user-info">
                <div class="avatar">
                    <img src="/assets/icons/user.svg" alt="Avatar" class="avatar-icon">
                </div>
                <div class="user-menu" id="userMenu">
                    <ul>
                        <li><?= $navbar ?></li>
                        <li><a href="Login.php">Se déconnecter</a></li>
                    </ul>
                </div>
                <div class="user-details">
                    <span><?= htmlspecialchars($surname) ?></span>
                    <span><?= htmlspecialchars($role) ?></span>
                </div>

            </div>
        </div>
    </nav>
</header>

<script src="/assets/js/navbar.js"></script>