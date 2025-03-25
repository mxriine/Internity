<?php
// Démarrez la session
session_start();

$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'inconnu';
$surname = isset($_SESSION['name']) ? $_SESSION['name'] : '';

// Vérifier la page actuelle
$current_page = basename($_SERVER['PHP_SELF']);

echo '<header>
        <nav class="navbar">
            <div class="logo">LOGO</div>

            <div class="home">
                <img src="/assets/icons/home.svg" alt="">
            </div>

            <div class="search-bar">
                <img src="/assets/icons/menu-burger.svg" alt="">
                <img src="/assets/icons/search.svg" alt="">
                <input type="text" placeholder="Hinted search text">
            </div>

            <div class="user">
                <img src="/assets/icons/user.svg" alt="">
                <div>
                    <h1>' . $surname . '</h1>
                    <p>' . $role . '</p>
                </div>
            </div>
        </nav>
    </header>';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="stylesheet" href="/assets/styles.css">
</head>

</html>