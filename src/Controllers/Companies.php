<?php
require_once(__DIR__ . '/../Core/DataBase.php');
require_once(__DIR__ . '/../Controllers/Login.php');
require_once(__DIR__ . '/../Models/Companies.php');

use Models\Companies;

// =========================================
// SECTION 0 : Initialisations
// =========================================
$companiesModel = new Companies($conn);

$elements_par_page = 9;
$current_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$current_file = basename($current_path);

// =========================================
// SECTION 1 : Fonction utilitaire
// =========================================
function createSlug($string)
{
    $string = strtolower($string);
    $string = preg_replace('/\s+/', '-', $string);
    $string = preg_replace('/[^a-z0-9\-]/', '', $string);
    return $string;
}

// =========================================
// SECTION 2 : Pagination des entreprises
// =========================================
$page_actuelle = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$offset = ($page_actuelle - 1) * $elements_par_page;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$location = isset($_GET['location']) ? trim($_GET['location']) : '';

$companies = $companiesModel->getPaginatedCompanies($elements_par_page, $offset, $search, $location);
$total_companies = $companiesModel->getTotalPaginatedCompaniesCount($search, $location);
$total_pages = ceil($total_companies / $elements_par_page);

// =========================================
// SECTION 3 : CRUD Entreprise
// =========================================

// ➤ Création
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['create'])) {
    $data = [
        'company_name' => trim($_POST['company_name'] ?? ''),
        'company_desc' => trim($_POST['company_desc'] ?? ''),
        'company_business' => trim($_POST['company_business'] ?? ''),
        'company_email' => trim($_POST['company_email'] ?? ''),
        'company_phone' => trim($_POST['phone_prefix'] ?? '') . ' ' . trim($_POST['company_phone'] ?? ''),
        'company_address' => trim($_POST['company_rue'] ?? '') . ' ' . trim($_POST['company_namerue'] ?? ''),
        'city_name' => trim($_POST['company_city'] ?? ''),
        'city_code' => trim($_POST['company_postal_code'] ?? ''),
    ];

    if ($data['company_name'] && $data['company_email'] && $data['company_phone'] && $data['city_name'] && $data['city_code'] && $data['company_business']) {
        try {
            $companiesModel->createCompany($data);
            header('Location: /vues/dashboard/Companies.php?success=create');
            exit;
        } catch (Exception $e) {
            die("Erreur lors de la création : " . htmlspecialchars($e->getMessage()));
        }
    } else {
        die("Tous les champs requis doivent être remplis.");
    }
}

// ➤ Modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['edit']) && isset($_POST['company_id'])) {
    $companyId = intval($_POST['company_id']);
    $name = trim($_POST['company_name'] ?? '');
    $desc = trim($_POST['company_desc'] ?? '');
    $business = trim($_POST['company_business'] ?? '');
    $email = trim($_POST['company_email'] ?? '');
    $phone = trim($_POST['phone_prefix'] ?? '') . ' ' . trim($_POST['company_phone'] ?? '');
    $address = trim($_POST['company_rue'] ?? '') . ' ' . trim($_POST['company_namerue'] ?? '');
    $city = trim($_POST['company_city'] ?? '');
    $code = trim($_POST['company_postal_code'] ?? '');

    try {
        $companyData = [
            'company_name' => $name,
            'company_desc' => $desc,
            'company_business' => $business,
            'company_email' => $email,
            'company_phone' => $phone,
            'company_address' => $address,
            'city_name' => $city,
            'city_code' => $code
        ];


        $success = $companiesModel->updateCompany($companyId, $companyData);

        if ($success) {
            header('Location: /vues/dashboard/Companies.php?success=update');
            exit;
        } else {
            die("Échec de la mise à jour de l'entreprise.");
        }

    } catch (Exception $e) {
        die("Erreur serveur : " . htmlspecialchars($e->getMessage()));
    }
}


// ➤ Suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['delete']) && isset($_POST['company_id'])) {
    $id = intval($_POST['company_id']);

    if ($id > 0) {
        try {
            $deleted = $companiesModel->deleteCompany($id);
            if ($deleted) {
                header("Location: /vues/dashboard/Companies.php?success=delete");
                exit;
            } else {
                die("Échec de la suppression.");
            }
        } catch (Exception $e) {
            die("Erreur serveur : " . htmlspecialchars($e->getMessage()));
        }
    } else {
        die("ID de l'entreprise invalide.");
    }
}

// =========================================
// SECTION 4 : Détail d'une entreprise
// =========================================
if (in_array($current_file, ['Company.php', 'Details.php'])) {
    if (isset($_GET['company_id'])) {
        $companyId = intval($_GET['company_id']);

        $companyDetails = $companiesModel->getCompanyById($companyId);
        if (!$companyDetails) {
            die("Entreprise non trouvée.");
        }

        $offers = $companiesModel->getCompanyOffers($companyId);

        // 🔧 On récupère l'adresse
        $adresse = $companyDetails['company_address'] ?? '';

        // 🔍 Extraction du numéro de rue
        if (preg_match('/^\d+/', $adresse, $matches)) {
            $numeroRue = $matches[0];
        } else {
            $numeroRue = "Numéro non trouvé";
        }
    } else {
        die("ID de l'entreprise manquant.");
    }
}

