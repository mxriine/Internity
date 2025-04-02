<?php

require_once(__DIR__ . '/../Core/DataBase.php');
require_once(__DIR__ . '/../Controllers/Login.php');
require_once(__DIR__ . '/../Models/Companies.php');

use Models\Companies;

// Instanciation du modèle Companies
$companiesModel = new Companies($conn);
$companies = $companiesModel->getAllCompanies();

$elements_par_page = 9;

// Récupérer le numéro de la page actuelle depuis l'URL
$page_actuelle = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page_actuelle < 1) {
    $page_actuelle = 1; // Assurer que la page actuelle est au minimum 1
}

// Calculer l'offset pour la pagination
$offset = ($page_actuelle - 1) * $elements_par_page;

$companies = $companiesModel->getPaginatedCompanies($elements_par_page, $offset);

// Compter le nombre total d'entreprises disponibles
$total_companies = $companiesModel->getTotalCompaniesCount();

// Calculer le nombre total de pages nécessaires
$total_pages = ceil($total_companies / $elements_par_page);