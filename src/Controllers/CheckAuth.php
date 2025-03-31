<?php
/**
 * Vérification de l'authentification de l'utilisateur
 */

// SECTION 1 : Récupérer le rôle depuis la session
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'inconnu';

// SECTION 2 : Récupérer la page actuelle
$current_page = basename($_SERVER['PHP_SELF']);

// SECTION 3 : Redirection si l'utilisateur n'est pas authentifié
if ($role === 'inconnu' && $current_page !== 'Login.php') {
    // Rediriger vers la page de login
    header('Location: /vues/Login.php');
    exit(); // Terminer l'exécution du script
}