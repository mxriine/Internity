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

$needsCompany = in_array($current_page, ['Company.php', 'Details.php']);

if ($needsCompany && isset($_GET['company_id'])) {
    $companyId = intval($_GET['company_id']); // Convertir en entier pour éviter les injections SQL

    // Charger les détails de l'entreprise depuis la base de données
    $companyDetails = $companiesModel->getCompanyById($companyId);

    if (!$companyDetails) {
        // Si l'entreprise n'existe pas, afficher un message d'erreur ou rediriger
        die("Entreprise non trouvée.");
    }

    // Récupérer les offres associées à l'entreprise
    $offers = $companiesModel->getCompanyOffers($companyId);

} elseif ($needsCompany) {
    die("ID de l'entreprise manquant.");
}


function createSlug($string)
{
    // Convertir en minuscules
    $string = strtolower($string);

    // Remplacer les espaces par des tirets
    $string = preg_replace('/\s+/', '-', $string);

    // Supprimer les caractères spéciaux
    $string = preg_replace('/[^a-z0-9\-]/', '', $string);

    return $string;
}
