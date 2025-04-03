<?php
/**
 * Vérification de l'authentification de l'utilisateur
 */

session_start(); // Assurez-vous que la session est démarrée

// SECTION 1 : Récupérer le rôle depuis la session
$role = $_SESSION['role'] ?? 'inconnu';

// SECTION 2 : Récupérer la page actuelle
$current_page = basename($_SERVER['PHP_SELF']);
$current_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// SECTION 3 : Redirection si l'utilisateur n'est pas authentifié
if ($role === 'inconnu' && $current_page !== 'Login.php') {
    // Rediriger vers la page de login
    header('Location: /vues/Login.php');
    exit(); // Terminer l'exécution du script
}

// SECTION 4 : Redirection si l'étudiant essaie d'accéder au dossier dashboard
if ($role === 'student' && str_starts_with($current_path, 'vues/dashboard/')) {
    // Rediriger vers la page Discover
    header('Location: /vues/Discover.php');
    exit();
}

// SECTION 4 : Redirection si l'étudiant essaie d'accéder au dossier dashboard
if ($role === 'pilote' && str_starts_with($current_path, 'vues/dashboard/Pilotes.php')) {
    // Rediriger vers la page Discover
    header('Location: /vues/dashboard/Home.php');
    exit();
}
