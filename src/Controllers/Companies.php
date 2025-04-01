<?php

require_once(__DIR__ . '/../Core/DataBase.php');
require_once(__DIR__ . '/../Controllers/Login.php');
require_once(__DIR__ . '/../Models/Companies.php');

use Models\Companies;

// Instanciation du modèle Companies
$companiesModel = new Companies($conn);