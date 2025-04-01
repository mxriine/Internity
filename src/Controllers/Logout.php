<?php
// logout.php

// Détruire toutes les données de session
session_unset();
session_destroy();

// Rediriger vers la page de connexion
header('Location: ../vues/login.php');
exit();
?>