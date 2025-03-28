<?php

// Récupérer le rôle depuis la session
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'inconnu';

// Récupérer la page actuelle
$current_page = basename($_SERVER['PHP_SELF']);

// Vérifier si le rôle est inconnu et si on n'est pas sur la page Login
if ($role === 'inconnu' && $current_page !== 'Login.php') {
    header('Location: /vues/Login.php');
    exit();
}

?>