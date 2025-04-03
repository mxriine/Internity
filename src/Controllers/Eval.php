<?php
require_once(__DIR__ . '/../Core/DataBase.php');
require_once(__DIR__ . '/../Controllers/Login.php');
require_once(__DIR__ . '/../Models/Companies.php');
require_once(__DIR__ . '/../Models/Eval.php');

use Models\Evaluations;
use Models\Companies;

// =========================================
// SECTION 0 : Initialisations
// =========================================
$EvaluationsModel = new Evaluations($conn);
$companiesModel = new Companies($conn);

$allCompanies = $companiesModel->getAllCompanies();

// Mettre a jour les entreprises
foreach ($allCompanies as $company) {
    $EvaluationsModel->updateCompanyAverage($company['company_id']);
}

?>