<?php
// logout.php

// Démarrer la session
session_start();

// Détruire toutes les données de session
session_unset();
session_destroy();

// Rediriger vers la page de connexion
header('Location: ../vues/login.php');
exit();
?>