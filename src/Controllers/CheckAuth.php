<?php

// Récupérer le rôle et le nom depuis la session
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'inconnu';

if (($role) == 'inconnu') {
    header('Location: /vues/Login.php');
    exit();
}

?>