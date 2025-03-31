<?php

require_once(__DIR__ . '/../Core/DataBase.php');
require_once(__DIR__ . '/../Models/Companies.php');

use Models\Companies;

$companiesModel = new Companies($conn);
$nb_companies = $companiesModel->getCountCompanies();
$nb_users = $companiesModel->getCountUsers();
$nb_offers = $companiesModel->getCountOffers();